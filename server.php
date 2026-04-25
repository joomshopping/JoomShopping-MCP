<?php
/*
* version: 1.0.2
*/

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/src/ApiClient.php';
require_once __DIR__ . '/src/ManufacturersTools.php';
require_once __DIR__ . '/src/CategoriesTools.php';
require_once __DIR__ . '/src/ProductsTools.php';
require_once __DIR__ . '/src/ImagesTools.php';
require_once __DIR__ . '/src/CharacteristicsTools.php';
require_once __DIR__ . '/src/AttributesTools.php';
require_once __DIR__ . '/src/LabelsTools.php';
require_once __DIR__ . '/src/CurrenciesTools.php';
require_once __DIR__ . '/src/DeliveryTimesTools.php';
require_once __DIR__ . '/src/TaxesTools.php';
require_once __DIR__ . '/src/ProductSubResourcesTools.php';
require_once __DIR__ . '/src/McpServer.php';

$client = new ApiClient(BASE_URL, API_TOKEN);

$server = new McpServer([
    new ManufacturersTools($client),
    new CategoriesTools($client),
    new ProductsTools($client),
    new ImagesTools($client),
    new CharacteristicsTools($client),
    new AttributesTools($client),
    new LabelsTools($client),
    new CurrenciesTools($client),
    new DeliveryTimesTools($client),
    new TaxesTools($client),
    new ProductSubResourcesTools($client),
]);
$server->run();
