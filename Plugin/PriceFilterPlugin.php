<?php
namespace WB\PriceSlider\Plugin;

use Magento\Catalog\Model\Layer\Filter\Price as Subject;
use Magento\Framework\App\RequestInterface;

class PriceFilterPlugin
{
    protected $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function aroundApply(Subject $subject, callable $proceed, ...$args)
    {
        $filter = $this->request->getParam($subject->getRequestVar());

        if ($filter) {
            $filter = explode('-', $filter);
            if (count($filter) == 2) {
                $minPrice = (float) $filter[0];
                $maxPrice = (float) $filter[1];

                // Apply custom price filtering logic here
                $subject->getLayer()->getProductCollection()->addFieldToFilter('price', ['gteq' => $minPrice]);
                $subject->getLayer()->getProductCollection()->addFieldToFilter('price', ['lteq' => $maxPrice]);
            }
        }

        return $proceed(...$args);
    }
}
