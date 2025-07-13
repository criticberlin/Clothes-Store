<?php

// Show server information
echo "<h1>Server Information</h1>";
echo "<pre>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "SERVER_NAME: " . $_SERVER['SERVER_NAME'] . "\n";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "\n";
echo "</pre>";

// Calculate base path
$basePath = dirname($_SERVER['SCRIPT_NAME']);
echo "<h2>Calculated Base Path: " . $basePath . "</h2>";

// Show available routes
echo "<h2>Test Links</h2>";
echo "<ul>";
echo "<li><a href='" . $basePath . "/set-language/en'>English</a></li>";
echo "<li><a href='" . $basePath . "/set-language/ar'>Arabic</a></li>";
echo "</ul>"; 