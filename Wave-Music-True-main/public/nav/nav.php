<?php require_once __DIR__ . '/../src/outils/gestion_langue.php'; ?>
<nav>
    <div class="logo">WAVE</div>
    <div class="nav-links">
        <a href="index.php"><?= $textes['nav_home'] ?? 'Accueil' ?></a>
        <a href="blog.php"><?= $textes['nav_blog'] ?? 'Blog' ?></a>
        <a href="calendar.php"><?= $textes['nav_calendar'] ?? 'Calendrier' ?></a>
        <a href="lastTop10.php"><?= $textes['nav_top10'] ?? 'Top 10' ?></a>
        <a href="sondage.php"><?= $textes['nav_vote'] ?? 'Sondage' ?></a>
        <a href="auth/deconnexion.php"><?= $textes['nav_logout'] ?? 'Déconnexion' ?></a>
    </div>
    <div class="language-switcher">
        <form method="POST" action="" class="language-form">
            <select name="language" onchange="this.form.submit()">
                <option value="fr" <?= ($langue === 'fr') ? 'selected' : '' ?>>FR</option>
                <option value="en" <?= ($langue === 'en') ? 'selected' : '' ?>>EN</option>
            </select>
        </form>
    </div>
</nav>

<style>
    .language-switcher select {
        background-color: #2c2c2c;
        color: white;
        border: 1px solid #555;
        padding: 5px;
        border-radius: 5px;
        cursor: pointer;
    }
    .language-form {
        display: flex;
        align-items: center;
    }
</style>