<?php
namespace WB\PriceSlider\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

class Slider extends Template
{
    protected $layerResolver;
    protected $currencyFactory;
    protected $scopeConfig;
    protected $storeManager;

    /**
     * Constructor method to initialize dependencies
     */
    public function __construct(
        Template\Context $context,
        Resolver $layerResolver,
        CurrencyFactory $currencyFactory,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager, 
        array $data = []
    ) {
        $this->layerResolver = $layerResolver;
        $this->currencyFactory = $currencyFactory;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * Determines if the price slider should be displayed based on store configuration
     * 
     * @return bool
     */
    public function canShowSlider()
    {
        return $this->scopeConfig->isSetFlag(
            'wb_priceslider/general/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Calculates the minimum price from the entire category
     * 
     * @return float
     */
    public function getMinPrice()
    {
        $productCollection = $this->getCategoryProductCollection();
        $minPrice = PHP_INT_MAX;

        foreach ($productCollection as $product) {
            $price = $this->formatPrice($product->getPrice());
            if ($price > 0 && $price < $minPrice) {
                $minPrice = $price;
            }
        }

        if ($minPrice === PHP_INT_MAX) {
            $minPrice = 0;
        }

        return $this->convertPrice($minPrice);
    }

    /**
     * Calculates the maximum price from the entire category
     * 
     * @return float
     */
    public function getMaxPrice()
    {
        $productCollection = $this->getCategoryProductCollection();
        $maxPrice = 0;

        foreach ($productCollection as $product) {
            $price = $this->formatPrice($product->getPrice());
            if ($price > $maxPrice) {
                $maxPrice = $price;
            }
        }

        return $this->convertPrice($maxPrice);
    }

    /**
     * Retrieves the entire product collection for the current category
     * 
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function getCategoryProductCollection()
    {
        $layer = $this->layerResolver->get();
        $category = $layer->getCurrentCategory();
        $storeId = $this->storeManager->getStore()->getId(); // Get the current store ID

        // Fetch the product collection for the current category
        $productCollection = $category->getProductCollection();
        $productCollection->addAttributeToSelect('price');
        $productCollection->addFinalPrice(); // Ensure final price including discounts is used
        $productCollection->setStoreId($storeId); // Filter by the current store ID
        $productCollection->addStoreFilter($storeId); // Ensure filtering only by the current store
        $productCollection->setPageSize(0); // Ensure all products are loaded
        $productCollection->setCurPage(1); // Start from the first page

        // Exclude products with zero price
        $productCollection->addAttributeToFilter('price', ['gt' => 0]);

        // Reset any potential filters or limitations
        $productCollection->clear();
        $productCollection->getSelect()->reset(\Zend_Db_Select::LIMIT_COUNT);
        $productCollection->getSelect()->reset(\Zend_Db_Select::LIMIT_OFFSET);

        // For debugging: output product data to ensure proper store filtering
        /* ($productCollection as $product) {
            echo "Store ID: " . $storeId . "<br>";
            echo "Product ID: " . $product->getId() . "<br>";
            echo "Product Price: " . $product->getPrice() . "<br>";
            echo "Final Price: " . $product->getFinalPrice() . "<br><br>";
        }*/

        return $productCollection;
    }


    /**
     * Converts a price to the store's base currency if necessary
     * 
     * @param float $price
     * @return float
     */
    protected function convertPrice($price)
    {
        $currentCurrencyCode = $this->storeManager->getStore()->getCurrentCurrencyCode();
        $baseCurrencyCode = $this->storeManager->getStore()->getBaseCurrencyCode();
        
        if ($currentCurrencyCode !== $baseCurrencyCode) {
            $rate = $this->currencyFactory->create()->load($baseCurrencyCode)->getRate($currentCurrencyCode);
            if ($rate) {
                $price = $price / $rate; // Convert price to the base currency
            }
        }

        return $this->formatPrice($price);
    }

    /**
     * Retrieves the currency symbol for the current store
     * 
     * @return string
     */
    public function getCurrencySymbol()
    {
        $currencyCode = $this->storeManager->getStore()->getCurrentCurrencyCode();
        $currency = $this->currencyFactory->create()->load($currencyCode);
        return $currency->getCurrencySymbol();
    }

    /**
     * Formats the price to two decimal places and rounds it
     * 
     * @param float $price
     * @return float
     */
    protected function formatPrice($price)
    {
        return round($price, 2); // Ensure the price is rounded to 2 decimal places
    }

    /**
     * Controls whether the HTML of the block should be rendered
     * 
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->canShowSlider()) {
            return ''; // Do not render if the slider is disabled
        }
        return parent::_toHtml();
    }
}
