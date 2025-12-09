<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$estConnecté = isset($_SESSION['user_id']);
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? '';
?>

<nav>
    <div class="logo">WAVE</div>
    <div class="nav-links">
<a href="/public/index.php">Spotlight</a>
<a href="/public/lastTop10.php">Top 10</a>
<a href="/public/sondage.php">Vote musique</a>
<a href="/public/calendar.php">Calendrier Concerts</a>
<a href="/public/blog.php">Blog</a>


        <?php if ($estConnecté): ?>
            <a href="/public/compte/monCompte.php">
                Mon compte (<?= htmlspecialchars($nom_utilisateur) ?>)
            </a>
            <a href="/public/auth/deconnexion.php" style="color:red;">Déconnexion</a>
        <?php else: ?>
            <a href="/public/auth/connexion.php">Connexion</a>
        <?php endif; ?>
    </div>
</nav>