# Price Slider Module

## Overview

The **WB Price Slider** module provides a customizable and responsive price slider for Magento 2, allowing users to filter products by price on the category page. The slider is easy to integrate, supports multiple stores and currencies, and includes a "Clear" button to reset the filter.

## Features

- Customizable price slider that displays minimum and maximum prices.
- Fully responsive design for desktop, tablet, and mobile views.
- "Clear" button to reset the filter and reload the page.
- Supports different base currencies on multiple store views within the same website.
- Module can be enabled or disabled from the Magento Admin Store Configuration.

## Installation

### 1. Manual Installation

1. Download the module package and extract it.
2. Copy the extracted files to the `app/code/WB/PriceSlider` directory of your Magento 2 installation.

### 2. Install via Composer

Alternatively, you can install the module via Composer (assuming it's available via a repository).

```bash
composer require wb/price-slider
```

### 3. Enable the Module

After installing, you need to enable the module and run the Magento upgrade script.

```bash
php bin/magento module:enable WB_PriceSlider
php bin/magento setup:upgrade
php bin/magento cache:clean
```

## Configuration

1. Navigate to the Magento Admin Panel.
2. Go to `Stores > Configuration > WB Extensions > Price Slider`.
3. From here, you can enable or disable the module for your store. You can also choose to enable the slider for specific store views.

## Usage

1. After installation and enabling the module from the configuration, navigate to a category page where the price slider will be displayed.
2. The slider allows you to select a minimum and maximum price to filter products.
3. Click "Apply filter" to filter the products based on the selected price range.
4. Use the "Clear" button to reset the filter and reload the page.

## Compatibility

- The module supports different base currencies on multiple store views within the same website.
- Works seamlessly across various devices with a responsive design.

## Customization

You can customize the appearance and behavior of the price slider by modifying the following files:

- `view/frontend/templates/slider.phtml`: The main template file for the slider.
- `view/frontend/web/css/price-slider.css`: Custom CSS for styling the slider.
- `view/frontend/web/js/price-slider.js`: JavaScript file for handling slider functionality.

## Uninstallation

To uninstall the module, you can disable it and remove the files.

```bash
php bin/magento module:disable WB_PriceSlider
rm -rf app/code/WB/PriceSlider
php bin/magento setup:upgrade
php bin/magento cache:clean
```

## Improvements Needed

While this module provides a robust price slider solution, there may still be areas for improvement. Feedback and suggestions are welcome to enhance its functionality and compatibility further.

## Support

If you encounter any issues with this module, please create an issue on the repository or contact the module's support team.

## License

This module is licensed under the MIT License.
```

### Explanation of Updates:

1. **Store Configuration**:
   - Added a section under "Configuration" that explains how the module can be enabled or disabled from the Magento Admin Store Configuration.

2. **Compatibility**:
   - Highlighted the module's compatibility with different base currencies on multiple store views within the same website.

3. **Improvements Needed**:
   - Included a section acknowledging that further improvements may be needed and encouraging feedback.