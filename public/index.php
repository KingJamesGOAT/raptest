<?php require_once __DIR__ . '/../src/outils/gestion_langue.php'; ?>
<!DOCTYPE html>
<html lang="<?= $langue ?>">
<head>
    <meta charset="UTF-8">
    <title>WAVE - <?= $textes['title_spotlight'] ?></title>
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>

<?php include 'nav/nav.php'; ?>

<?php if (isset($_GET['deleted'])): ?>
    <p style="color:green;"><?= $textes['index_deleted_account_message'] ?></p>
<?php endif; ?>

<header class="hero">
    <h1><?= $textes['title_spotlight'] ?></h1>
    <p><?= $textes['desc_spotlight'] ?></p>
</header>

<main>
    <section class="block">
        <h2>GIMS</h2>
        <p><?= $textes['bio_gims'] ?></p>
        <a href="#" class="btn"><?= $textes['listen_btn'] ?></a>
    </section>

    <section class="block">
        <h2><?= $textes['index_favorite_songs_title'] ?></h2>
        <div class="list">
            <ul>
                <li>1. <a href="https://youtu.be/Rfhcng7Ux-A?si=VUjKCiuw5PFStcSL">Où aller</a></li>
                <li>2.<a href="https://youtu.be/-KRe61NpaTA?si=lW-r2Ko5USC1w7yw">Tout donner</a></li>
                <li>3.<a href="https://youtu.be/6yDEYu61piI?si=pjEAXj43YR4RFTyI">Zombie</a></li>
                <li>4. <a href="https://youtu.be/CxDvKp-Hb2c?si=mcccC5ejXXU55P8u">Parisienne</a></li>
                <li>5. <a href="https://youtu.be/s1LA-Kmqr04?si=-8IWX_oqRabsV8P8">DO YOU LOVE ME ?</a></li>
            </ul>
        </div>
    </section>
</main>

<footer>
    <?= $textes['footer_copyright'] ?>
</footer>

</body>
</html>