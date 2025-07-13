<?php

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get language from query string
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';

// Validate language (only allow en or ar)
if (!in_array($lang, ['en', 'ar'])) {
    $lang = 'en';
}

// Set session variables
$_SESSION['locale'] = $lang;
if ($lang === 'ar') {
    $_SESSION['isRTL'] = true;
} else {
    unset($_SESSION['isRTL']);
}

// Set cookie that persists for a year
setcookie('locale', $lang, time() + 60 * 60 * 24 * 365, '/');

// Get redirect URL from query string or default to home
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '../';

// Redirect back
header('Location: ' . $redirect);
exit; 