<?php
require_once __DIR__ . '/../src/outils/gestion_langue.php';
session_start();

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) { header('Location: ./auth/connexion.php'); exit(); }
$username = $_SESSION['nom_utilisateur'];
?>

<!DOCTYPE html>
<html lang="<?= $langue ?>">
<head>
    <meta charset="UTF-8">
    <title>WAVE - <?= $textes['nav_blog'] ?></title>
    <link rel="stylesheet" href="css/blog.css">
</head>
<body>
    <?php include 'nav/nav.php'; ?>

    <main class="blog-page">
        <h1 class="blog-title"><?= $textes['title_blog'] ?></h1>
        <p class="blog-intro"><?= $textes['intro_blog'] ?></p>

        <section class="blog-posts">
            <article class="blog-post">
                <h2 class="post-title">🚨 Un rappeur emblématique entendu par la justice</h2>
                <p class="post-meta">Publié récemment • Sources proches du dossier</p>
                <p class="post-content">Booba aurait été entendu au tribunal de Paris cette semaine...</p>
            </article>
            <article class="blog-post">
                <h2 class="post-title">😳 Un artiste US aperçu dans une clinique genevoise</h2>
                <p class="post-meta">Publié il y a quelques jours • Genève</p>
                <p class="post-content">Kanye West s'est rendu dans un centre dentaire à Genève...</p>
            </article>
            </section>
    </main>
    <footer><?= $textes['footer_copyright'] ?></footer>
</body>
</html>