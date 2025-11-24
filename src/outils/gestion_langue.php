<?php
// Charge le dictionnaire
require_once __DIR__ . '/../config/traductions.php';

const COOKIE_NAME = 'lang';
const DEFAULT_LANG = 'fr';

// Traitement du changement de langue par POST (formulaire)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['language'])) {
    $choix = $_POST['language'];
    if (array_key_exists($choix, $translations)) {
        // Crée le cookie pour 30 jours
        setcookie(COOKIE_NAME, $choix, time() + (30 * 24 * 60 * 60), "/");
        // Recharge la page pour prendre en compte le changement
        header("Location: " . $_SERVER["REQUEST_URI"]);
        exit();
    }
}

// 1. Changement de langue demandé via l'URL (?lang=en)
if (isset($_GET['lang'])) {
    $choix = $_GET['lang'];
    if (array_key_exists($choix, $translations)) {
        // Crée le cookie pour 30 jours
        setcookie(COOKIE_NAME, $choix, time() + (30 * 24 * 60 * 60), "/");
        // Recharge la page pour nettoyer l'URL
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
        exit();
    }
}

// 2. Lecture du cookie existant
$langue = DEFAULT_LANG;
if (isset($_COOKIE[COOKIE_NAME]) && array_key_exists($_COOKIE[COOKIE_NAME], $translations)) {
    $langue = $_COOKIE[COOKIE_NAME];
}

// 3. Mise à disposition des textes
$textes = $translations[$langue];
?>