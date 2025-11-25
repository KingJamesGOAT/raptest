<?php
// Include language management first (handles cookies)
require_once __DIR__ . '/../src/outils/gestion_langue.php';

session_start();

const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../src/config/database.ini';
$config = parse_ini_file(DATABASE_CONFIGURATION_FILE, true);
if (!$config) { throw new Exception("Erreur config"); }

$db = $config['database'];
$pdo = new PDO("mysql:host={$db['host']};port={$db['port']};dbname={$db['dbname']};charset=utf8mb4", $db['username'], $db['password']);

$sql = "SELECT m.id, m.titre, m.annee_sortie, m.lien_youtube, a.nom_artiste, a.pays_unicode 
        FROM musique_wave AS m JOIN artistes_wave AS a ON m.artiste_id = a.id 
        ORDER BY m.annee_sortie DESC, m.titre ASC LIMIT 10";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$musics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="<?= $langue ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/lastTop10.css">
    <title>WAVE - <?= $textes['nav_top10'] ?></title>
</head>

<?php include 'nav/nav.php'; ?>

<body>
    <main class="container">
        <h1><?= $textes['title_top10'] ?></h1>
        <p><?= $textes['subtitle_top10'] ?></p>

        <section class="top10-container">
            <?php foreach ($musics as $index => $music): ?>
                <?php
                $rank = $index + 1;
                $extraClass = ($rank === 1) ? 'first' : (($rank === 2) ? 'second' : (($rank === 3) ? 'third' : ''));
                $url = $music['lien_youtube'] ?? null;
                ?>
                <?php if (!empty($url)): ?><a href="<?= htmlspecialchars((string)$url) ?>" target="_blank" class="card-link"><?php endif; ?>
                    <article class="music-card <?= $extraClass ?>">
                        <div class="music-rank">#<?= $rank ?></div>
                        <h2 class="music-title"><?= htmlspecialchars($music['titre']) ?></h2>
                        <p class="music-artist"><?= htmlspecialchars($music['nom_artiste']) ?> (<?= htmlspecialchars($music['pays_unicode']) ?>)</p>
                        <p class="music-meta">Sortie en <strong><?= htmlspecialchars($music['annee_sortie']) ?></strong></p>
                    </article>
                <?php if (!empty($url)): ?></a><?php endif; ?>
            <?php endforeach; ?>
        </section>
    </main>
    <footer><?= $textes['footer_copyright'] ?></footer>
</body>
</html>