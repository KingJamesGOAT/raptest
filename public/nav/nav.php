<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure variables exist if nav is included without the header (safety)
if (!isset($textes)) { $textes = []; }
if (!isset($langue)) { $langue = 'fr'; }

$estConnecté = isset($_SESSION['user_id']);
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? '';
?>

<nav>
    <div class="logo">WAVE</div>
    <div class="nav-links">
        <a href="/public/index.php"><?= $textes['nav_spotlight'] ?? 'Spotlight' ?></a>
        <a href="/public/lastTop10.php"><?= $textes['nav_top10'] ?? 'Top 10' ?></a>
        <a href="/public/sondage.php"><?= $textes['nav_vote'] ?? 'Vote musique' ?></a>
        <a href="/public/calendar.php"><?= $textes['nav_calendar'] ?? 'Calendrier' ?></a>
        <a href="/public/blog.php"><?= $textes['nav_blog'] ?? 'Blog' ?></a>

        <?php if ($estConnecté): ?>
            <a href="/public/compte/monCompte.php">
                <?= $textes['nav_account'] ?? 'Mon compte' ?> (<?= htmlspecialchars($nom_utilisateur) ?>)
            </a>
            <a href="/public/auth/deconnexion.php" style="color:red;">
                <?= $textes['nav_logout'] ?? 'Déconnexion' ?>
            </a>
        <?php else: ?>
            <a href="/public/auth/connexion.php">
                <?= $textes['nav_login'] ?? 'Connexion' ?>
            </a>
        <?php endif; ?>

        <span style="margin-left:15px; border-left:1px solid #555; padding-left:15px;">
            <a href="?lang=fr" style="margin-left:0; color:<?= $langue === 'fr' ? '#4da6ff' : '#888' ?>; font-weight:bold;">FR</a>
            <span style="color:#555">|</span>
            <a href="?lang=en" style="margin-left:0; color:<?= $langue === 'en' ? '#4da6ff' : '#888' ?>; font-weight:bold;">EN</a>
        </span>
    </div>
</nav>