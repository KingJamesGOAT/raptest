<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>WAVE - Spotlight</title>
    <link rel="stylesheet" href="css/style.css"> </head>
<body>

<?php include 'nav/nav.php'; ?>

<?php if (isset($_GET['deleted'])): ?>
    <p style="color:green;">✅ Ton compte a bien été supprimé.</p>
<?php endif; ?>


<header class="hero">
    <h1>🎤 Spotlight</h1>
    <p>Découvrez l'artiste du mois de Janvier! ❄️</p>
</header>

<main>
    <section class="block">
        <h2>GIMS</h2>
        <!-- <div class="card-grid">
            <div class="card"> -->
                <!-- <div class="card-img"><img src="./img/gims.jpeg" alt="Photo de GIMS"></div> -->
                <p>Gims, stylisé GIMS, anciennement Maître Gims, né Gandhi Djuna le 6 mai 1986 à Kinshasa au Zaïre, est un chanteur et rappeur congolais. Il grandit en France et vit principalement entre la France et le Maroc. Il est membre du groupe de hip-hop Sexion d'assaut.</p>
                <a href="#" class="btn">Écouter maintenant</a>
            <!-- </div>
        </div> -->
    </section>

    <section class="block">
        <h2>Nos 5 musiques préférés de Gims</h2>
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
    &copy; 2025 WAVE - Tous droits réservés
</footer>

</body>
</html>