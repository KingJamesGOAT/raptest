<?php
// Démarre la session
session_start();

// Vérifie si l'utilisateur est authentifié
$userId = $_SESSION['user_id'] ?? null;

// L'utilisateur n'est pas authentifié
if (!$userId) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
    header('Location: ./auth/connexion.php');
    exit();
}

// Sinon, récupère les autres informations de l'utilisateur
$username = $_SESSION['nom_utilisateur'];
?>

<!DOCTYPE html>
<html lang="<?= $langue ?>">

<head>
    <meta charset="UTF-8">
    <title>WAVE - <?= $textes['title_blog'] ?></title>
    <link rel="stylesheet" href="css/blog.css">
</head>


<body>
    <?php include 'nav/nav.php'; ?>


    <main class="blog-page">

        <h1 class="blog-title"><?= $textes['title_blog'] ?></h1>
<p class="blog-intro">
    <?= $textes['intro_blog'] ?>
</p>

        <section class="blog-posts">

            <!-- POST 1 -->
            <article class="blog-post">
                <h2 class="post-title">🚨 Un rappeur emblématique entendu par la justice</h2>
                <p class="post-meta">Publié récemment • Sources proches du dossier</p>
                <p class="post-content">
                    Booba aurait été entendu au tribunal de Paris cette semaine
                    dans un dossier lié au cyberharcèlement. Selon plusieurs sources, il serait désormais sous contrôle
                    judiciaire et soumis au port d'un bracelet électronique.
                </p>
            </article>

            <!-- POST 2 -->
            <article class="blog-post">
                <h2 class="post-title">😳 Un artiste US aperçu dans une clinique genevoise</h2>
                <p class="post-meta">Publié il y a quelques jours • Genève</p>
                <p class="post-content">
                    Kanye West s'est rendu dans un centre dentaire à Genève vendredi dernier.
                    Il devait être soigné car un dentiste aux États-Unis lui avait mal posé son grillz :
                    dents limées, infection et facturation abusive (800'000 $).
                    Ce dentiste lui aurait aussi livré du gaz hilarant à son domicile, le rendant dépendant.
                    Kanye a décidé cette semaine de le poursuivre en justice !
                    Le déplacement aurait coûté bien moins cher que les soins reçus outre-Atlantique.

                </p>
            </article>

            <!-- POST 3 -->
            <article class="blog-post">
                <h2 class="post-title">👀 Nouveau couple surprise dans le game</h2>
                <p class="post-meta">Publié la semaine dernière • Spotted</p>
                <p class="post-content">
                    Le rappeur PLK serait en couple depuis plusieurs mois avec la chanteuse Eva Queen.
                    Le duo aurait été aperçu ensemble à plusieurs reprises, loin des caméras, en mode discret.
                </p>
            </article>

            <!-- POST 4 -->
            <article class="blog-post">
                <h2 class="post-title">🔥 Romance dans le milieu du rap</h2>
                <p class="post-meta">Publié récemment • Paris</p>
                <p class="post-content">
                    Le rappeur Gazo serait actuellement en couple avec Karine Dolls (IDLT).
                    Ils auraient été vu à plusieurs occasions à Paris.
                    Les deux semblent de plus en plus proches… nos sources parlent même d’un début de relation.
                </p>
            </article>

            <!-- POST 5 -->
            <article class="blog-post">
                <h2 class="post-title">⚡ Showcase mouvementé dans le sud</h2>
                <p class="post-meta">Publié il y a 2 semaines • Sud de la France</p>
                <p class="post-content">
                    Spotted : Dadju giflant un fan hier soir à Fréjus lors de son showcase et continue de chanter comme si de rien n'était
                </p>
            </article>

            <!-- POST 6 -->
            <article class="blog-post">
                <h2 class="post-title">💿 Une star française revient avec un nouvel album + Stade de France</h2>
                <p class="post-meta">Publié il y a quelques jours • Annonce officielle</p>
                <p class="post-content">
                    Aya Nakamura vient d'annoncer un nouvel album prévu pour cet automne,
                    accompagné d'un énorme concert au Stade de France l’année prochaine. Les préventes ouvrent bientôt.
                </p>
            </article>

        </section>

    </main>



    <footer>
        <?= $textes['footer_copyright'] ?>
    </footer>

</body>

</html>