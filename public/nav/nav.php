<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sécurité : Si $textes n'est pas chargé, on met un tableau vide pour éviter le crash
if (!isset($textes)) { $textes = []; }

$estConnecté = isset($_SESSION['user_id']);
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? '';
?>

<nav>
    <div class="logo">WAVE</div>
    <div class="nav-links">
        <a href="/public/index.php"><?= $textes['nav_spotlight'] ?? 'Spotlight' ?></a>
        <a href="/public/lastTop10.php"><?= $textes['nav_top10'] ?? 'Top 10' ?></a>
        <a href="/public/sondage.php"><?= $textes['nav_vote'] ?? 'Vote' ?></a>
        <a href="/public/calendar.php"><?= $textes['nav_calendar'] ?? 'Calendrier' ?></a>
        <a href="/public/blog.php"><?= $textes['nav_blog'] ?? 'Blog' ?></a>

        <?php if ($estConnecté): ?>
            <a href="/public/compte/monCompte.php">
                <?= $textes['nav_account'] ?? 'Mon Compte' ?> (<?= htmlspecialchars($nom_utilisateur) ?>)
            </a>
            <a href="/public/auth/deconnexion.php" style="color:red;">
                <?= $textes['nav_logout'] ?? 'Déconnexion' ?>
            </a>
        <?php else: ?>
            <a href="/public/auth/connexion.php">
                <?= $textes['nav_login'] ?? 'Connexion' ?>
            </a>
        <?php endif; ?>

        <span style="margin-left:15px; font-size:0.9em; border-left:1px solid #444; padding-left:15px; font-weight:bold;">
            <a href="?lang=fr" style="text-decoration:none; color:<?= ($langue ?? 'fr') === 'fr' ? '#4da6ff' : '#888' ?>;">FR</a>
            <span style="color:#444; margin:0 5px;">|</span>
            <a href="?lang=en" style="text-decoration:none; color:<?= ($langue ?? 'fr') === 'en' ? '#4da6ff' : '#888' ?>;">EN</a>
        </span>
    </div>
</nav>