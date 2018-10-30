<?php
/**
 * Example file for shopping feed
 */

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

// Large
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