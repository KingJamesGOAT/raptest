<?php
session_start();

require_once __DIR__ . '/../src/outils/autoloader.php';

const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../src/config/database.ini';

$config = parse_ini_file(DATABASE_CONFIGURATION_FILE, true);

if (!$config) {
    die("Erreur : Impossible de lire le fichier de configuration.");
}

$db = $config['database'];
$host = $db['host'];
$port = $db['port'];
$dbname = $db['dbname'];
$username = $db['username'];
$password = $db['password'];

// Connexion PDO
$pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);

// Vérifie si l'utilisateur est authentifié
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Location: ./auth/connexion.php');
    exit();
}

$username = $_SESSION['nom_utilisateur'] ?? null;

// Vérifie si admin
$isAdmin = ($_SESSION['role'] ?? '') === 'admin';
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>WAVE - BLOG</title>
    <link rel="stylesheet" href="css/blog.css">
</head>


<body>
    <?php include 'nav/nav.php'; ?>

    <main class="blog-page">

        <h1 class="blog-title"> 🔥 SCOOPS & EXCLUS RAP</h1>

        <?php if ($isAdmin): ?>
        <section class="admin-panel">
            <h2 class="admin-title">🛠 Publier un article</h2>

            <form action="createPost.php" method="POST" class="admin-form">

                <div class="admin-field">
                    <label for="title">Titre de l’article</label>
                    <input type="text" id="title" name="title" placeholder="Saisis ici ton titre..." required>
                </div>

                <div class="admin-field">
                    <label for="content">Contenu</label>
                    <textarea id="content" name="content" rows="6" placeholder="Écris ton article ici..." required></textarea>
                </div>

                <button class="admin-btn" type="submit">Publier l’article 🚀</button>
            </form>
        </section>
        <?php endif; ?>


        <?php
        // Connexion base (réutilise ton PDO)
        $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
        $posts = $stmt->fetchAll();
        ?>

        <p class="blog-intro">
            Infos exclusives, scoops, spotted, coulisses : voici les actus brûlantes du rap francophone, version WAVE.
        </p>

        <section class="blog-posts">

            <?php foreach ($posts as $post): ?>
                <article class="blog-post">
                    <h2 class="post-title"><?= htmlspecialchars($post['title']) ?></h2>
                    <p class="post-meta">
                        Publié par <?= htmlspecialchars($post['author']) ?> •
                        <?= htmlspecialchars($post['created_at']) ?>
                    </p>
                    <p class="post-content">
                        <!-- nl2br pour les retours à la ligne -->
                        <?= nl2br(htmlspecialchars($post['content'])) ?>
                    </p>
                </article>
            <?php endforeach; ?>

        </section>


    </main>



    <footer>
        &copy; 2025 WAVE - Tous droits réservés
    </footer>

</body>

</html>