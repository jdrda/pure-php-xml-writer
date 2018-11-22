[![License](https://poser.pugx.org/jan-drda/pure-php-xml-writer/license)](https://packagist.org/packages/jan-drda/pure-php-xml-writer)
[![Latest Stable Version](https://poser.pugx.org/jan-drda/pure-php-xml-writer/v/stable)](https://packagist.org/packages/jan-drda/pure-php-xml-writer)
[![Total Downloads](https://poser.pugx.org/jan-drda/pure-php-xml-writer/downloads)](https://packagist.org/packages/jan-drda/pure-php-xml-writer)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jdrda/pure-php-xml-writer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jdrda/pure-php-xml-writer/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/jdrda/pure-php-xml-writer/badges/build.png?b=master)](https://scrutinizer-ci.com/g/jdrda/pure-php-xml-writer/build-status/master)

# Pure PHP XML Writer
Simple XML writer library written with basic PHP functions only. The main purpose of this project is generating large XML files without using large amount of memory (all elements are passed to the write buffer, there is no object containing all the XML in memory)

[![ko-fi](https://www.ko-fi.com/img/donate_sm.png)](https://ko-fi.com/A067ES5)

## Installation
```
composer require jan-drda/pure-php-xml-writer
```
Then copy example.php to your project root directory. You can modify it upon your requirements and run.
### If you do not have Composer
Install it, it is very simple:
https://getcomposer.org/doc/00-intro.md

## Documentantion
Please see example.php for basic usage, I am working at documentation (copying there):
```php
/**
 * Composer autoload (only if you do not use it anywhere else)
 *
 * It is needed for namespace mapping
 */
require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

/**
 * Simple initialize XML Writer and auto open the file
 */
$xmlWriter = new \PurePhpXmlWriter\PurePhpXmlWriter('feed.xml');

/**
 * Open root element "items" (true = expecting children elements)
 */
$xmlWriter->openXMLElement('products', true);

/**
 * Save simple product
 */
$xmlWriter->openXMLElement('product', true); // Open the parent element
$xmlWriter->saveElementWithValue('name', 'Breakfast white mug'); // Name
$xmlWriter->saveElementWithValue('description', 'Nice white mug used for breakfast'); // Description
$xmlWriter->saveElementWithValue('price', 5.00, 2); // Price with 2 decimals
$xmlWriter->saveElementWithValue('category', 'Mugs|Breakfast'); // Category
$xmlWriter->saveElementWithValue('quantity', 20); // Quantity available
$xmlWriter->closeXMLElement('product'); // Close the parent element

/**
 * /Save simple product
 */

/**
 * Save variable product where variants have individual prices
 */
$xmlWriter->openXMLElement('product', true); // Open the parent element
$xmlWriter->saveElementWithValue('name', 'Puma T-shirt'); // Name
$xmlWriter->saveElementWithValue('description', 'Puma t-shirt with some sizes'); // Description
$xmlWriter->saveElementWithValue('price', 10.00, 2); // Price with 2 decimals
$xmlWriter->saveElementWithValue('category', 'T-shirts|Nike'); // Category
$xmlWriter->saveElementWithValue('quantity', 10); // Quantity available
$xmlWriter->openXMLElement('sizes', true); // Open the parent element for sizes

// Small size
$xmlWriter->openXMLElement('size', true); // Open the parent element for size
$xmlWriter->saveElementWithValue('size_name', 'S'); // Size name
$xmlWriter->saveElementWithValue('size_price', 10.00); // Size price with 2 decimals
$xmlWriter->closeXMLElement('size', true); // Open the parent element for size

// Medium size
$xmlWriter->openXMLElement('size', true); // Open the parent element for size
$xmlWriter->saveElementWithValue('size_name', 'M'); // Size name
$xmlWriter->saveElementWithValue('size_price', 11.00); // Size price with 2 decimals
$xmlWriter->closeXMLElement('size', true); // Open the parent element for size

// Large size
$xmlWriter->openXMLElement('size', true); // Open the parent element for size
$xmlWriter->saveElementWithValue('size_name', 'L'); // Size name
$xmlWriter->saveElementWithValue('size_price', 13.00); // Size price with 2 decimals
$xmlWriter->closeXMLElement('size', true); // Open the parent element for size
$xmlWriter->closeXMLElement('sizes', true); // Close the parent element for sizes
$xmlWriter->closeXMLElement('product'); // Close the parent element

/**
 * /Save variable product
 */

/**
 * Close root element "items"
 */
$xmlWriter->closeXMLElement('products');
```
