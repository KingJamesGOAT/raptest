<?php
session_start();
require_once __DIR__ . '/../src/outils/autoloader.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: blog.php');
    exit();
}

$config = parse_ini_file(__DIR__ . '/../src/config/database.ini', true)['database'];
$pdo = new PDO(
    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4;port={$config['port']}",
    $config['username'],
    $config['password']
);

$title = $_POST['title'] ?? null;
$content = $_POST['content'] ?? null;

if ($title && $content) {

    $stmt = $pdo->prepare("
        INSERT INTO posts (title, content, author)
        VALUES (:title, :content, :author)
    ");

    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':author' => $_SESSION['nom_utilisateur'],
    ]);

    header('Location: blog.php?posted=1');
    exit();
}

header('Location: blog.php?error=1');
exit();
