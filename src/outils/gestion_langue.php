<?php
require_once __DIR__ . '/../config/traductions.php';

const COOKIE_NAME = 'lang';
const DEFAULT_LANG = 'fr';

// 1. Change lang via URL (?lang=en)
if (isset($_GET['lang'])) {
    $choix = $_GET['lang'];
    if (array_key_exists($choix, $translations)) {
        setcookie(COOKIE_NAME, $choix, time() + (30 * 24 * 60 * 60), "/"); // 30 days
        // Strip the ?lang= parameter and reload to keep URL clean
        $url = strtok($_SERVER["REQUEST_URI"], '?');
        header("Location: " . $url);
        exit();
    }
}

// 2. Read existing cookie
$langue = DEFAULT_LANG;
if (isset($_COOKIE[COOKIE_NAME]) && array_key_exists($_COOKIE[COOKIE_NAME], $translations)) {
    $langue = $_COOKIE[COOKIE_NAME];
}

// 3. Set text variable for use in pages
$textes = $translations[$langue];
?>