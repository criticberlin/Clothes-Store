<?php

require __DIR__ . '/vendor/autoload.php';

// Test if the helper function exists
if (function_exists('emailFromLoginCertificate')) {
    echo "Helper function exists\n";
} else {
    echo "Helper function does NOT exist\n";
}

// Manually include the helper file and test again
require_once __DIR__ . '/app/helpers.php';

if (function_exists('emailFromLoginCertificate')) {
    echo "Helper function exists after manual include\n";
} else {
    echo "Helper function still does NOT exist after manual include\n";
}