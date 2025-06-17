<?php

// Include Laravel's autoloader
require __DIR__.'/vendor/autoload.php';

// Create a new Laravel application
$app = require_once __DIR__.'/bootstrap/app.php';

// Get the database configuration
$db = $app->make('config')->get('database');
echo "Database Connection: " . $db['default'] . "\n";
echo "Database Name: " . $db['connections'][$db['default']]['database'] . "\n";

// Get the connection
$connection = $app->make('db')->connection();

// List all tables
echo "\nTables in the database:\n";
$tables = $connection->getDoctrineSchemaManager()->listTableNames();
foreach ($tables as $table) {
    echo "- $table\n";
}

// Try to get products table structure
try {
    echo "\nProducts table columns:\n";
    $columns = $connection->getDoctrineSchemaManager()->listTableColumns('products');
    foreach ($columns as $column) {
        echo "- " . $column->getName() . " (" . $column->getType() . ")\n";
    }
} catch (Exception $e) {
    echo "Error getting products table structure: " . $e->getMessage() . "\n";
}

// Try to query the products table
try {
    echo "\nNumber of products: ";
    $count = $connection->table('products')->count();
    echo $count . "\n";
    
    if ($count > 0) {
        echo "\nFirst product:\n";
        $product = $connection->table('products')->first();
        print_r($product);
    }
} catch (Exception $e) {
    echo "Error querying products table: " . $e->getMessage() . "\n";
} 