<?php
session_start();

const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../src/config/database.ini';

// Lire le fichier de config
$config = parse_ini_file(DATABASE_CONFIGURATION_FILE, true);

if (!$config) {
    throw new Exception("Erreur lors de la lecture du fichier de configuration : " . DATABASE_CONFIGURATION_FILE);
}

// Paramètres DB
$db       = $config['database'];
$host     = $db['host'];
$port     = $db['port'];
$dbname   = $db['dbname'];
$username = $db['username'];
$password = $db['password'];

// Connexion à la DB
$pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);


// Requête : musiques + artistes (TOP 10)
$sql = "
    SELECT 
        m.id AS musique_id,
        m.titre,
        m.annee_sortie,
        m.lien_youtube,
        a.nom_artiste,
        a.pays_unicode
    FROM musique_wave AS m
    JOIN artistes_wave AS a ON m.artiste_id = a.id
    ORDER BY m.annee_sortie DESC, m.titre ASC
    LIMIT 10
";

// Prépare + exécute
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Tableau des musiques
$musics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="<?= $langue ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark light">
    <link rel="stylesheet" href="css/lastTop10.css">

    <title><?= $textes['title_top10'] ?> - WAVE</title>

</head>

<?php include 'nav/nav.php'; ?>

<body>
    <main class="container">
       <h1><?= $textes['title_top10'] ?></h1>
        <p><?= $textes['subtitle_top10'] ?></p>>

        <section class="top10-container">
            <?php foreach ($musics as $index => $music): ?>

                <?php
                $rank = $index + 1;

                // Podium
                $extraClass = '';
                if ($rank === 1) $extraClass = 'first';
                elseif ($rank === 2) $extraClass = 'second';
                elseif ($rank === 3) $extraClass = 'third';

                // S'il manque la clé ou que c'est NULL → $url sera null
                $url = $music['lien_youtube'] ?? null;
                ?>

                <?php if (!empty($url)): ?>
                    <a href="<?= htmlspecialchars((string)$url) ?>" target="_blank" class="card-link">
                    <?php endif; ?>

                    <article class="music-card <?= $extraClass ?>">
                        <div class="music-rank">#<?= $rank ?></div>

                        <h2 class="music-title">
                            <?= htmlspecialchars($music['titre']) ?>
                        </h2>

                        <p class="music-artist">
                            <?= htmlspecialchars($music['nom_artiste']) ?>
                            (<?= htmlspecialchars($music['pays_unicode']) ?>)
                        </p>

                        <p class="music-meta">
                            <?= $textes['top10_released_in'] ?> <strong><?= htmlspecialchars($music['annee_sortie']) ?></strong>
                        </p>
                    </article>

                    <?php if (!empty($url)): ?>
                    </a>
                <?php endif; ?>

            <?php endforeach; ?>
        </section>
    </main>

    <footer>
        <?= $textes['footer_copyright'] ?>
    </footer>

</body>

</html>