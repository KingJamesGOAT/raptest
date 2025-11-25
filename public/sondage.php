<?php
session_start();

const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../src/config/database.ini';

// Vérifie si l'utilisateur est authentifié
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Location: ./auth/connexion.php');
    exit();
}

// Lecture du fichier de config
$config = parse_ini_file(DATABASE_CONFIGURATION_FILE, true);

if (!$config) {
    throw new Exception("Erreur lors de la lecture du fichier de configuration : " . DATABASE_CONFIGURATION_FILE);
}

$db = $config['database'];
$pdo = new PDO(
    "mysql:host={$db['host']};port={$db['port']};dbname={$db['dbname']};charset=utf8mb4",
    $db['username'],
    $db['password']
);

// Vérifie si l’utilisateur a déjà voté
$stmt = $pdo->prepare("SELECT COUNT(*) FROM classement_utilisateur WHERE user_id = :uid");
$stmt->execute(['uid' => $userId]);
$aDejaVote = $stmt->fetchColumn() > 0;

// IDs des musiques sélectionnées
$ids = [4, 9, 10, 11, 12, 15, 16, 17, 18, 20];

$sql = "
    SELECT m.id, m.titre, m.lien_youtube, m.annee_sortie, a.nom_artiste
    FROM musique_wave AS m
    JOIN artistes_wave AS a ON m.artiste_id = a.id
    WHERE m.id IN (" . implode(',', $ids) . ")
";
$stmt = $pdo->query($sql);
$musics = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement du vote
if ($_SERVER["REQUEST_METHOD"] === "POST" && !$aDejaVote) {
    
    $classement = $_POST["classement"]; // tableau : musique_id => position

    // Vérifier s'il y a bien 10 positions
    if (count($classement) === 10) {

        // Enregistre chaque musique & position
        $insert = $pdo->prepare("
            INSERT INTO classement_utilisateur (user_id, musique_id, position)
            VALUES (:user_id, :musique_id, :position)
        ");

        foreach ($classement as $musiqueId => $position) {
            $insert->execute([
                'user_id' => $userId,
                'musique_id' => $musiqueId,
                'position' => $position
            ]);
        }

        // Mise à jour du flag pour ne plus réafficher le formulaire
        $aDejaVote = true;
        $message = "<p style='color:lightgreen; text-align:center;'>Ton classement a été enregistré !</p>";
    }
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>WAVE - Vote Musical</title>
    <link rel="stylesheet" href="css/sondage.css">
</head>

<body>

<?php include 'nav/nav.php'; ?>

<div class="container">
    <h1>🎵 Classe ton Top 10</h1>

    <?php if (isset($message)) echo $message; ?>

    <?php if (!$aDejaVote): ?>
        <form method="post">

            <table class="table-classement">
                <tr>
                    <th>Ordre</th>
                    <th>Titre</th>
                    <th>Artiste</th>
                    <th>Clip</th>
                </tr>

                <?php foreach ($musics as $music): ?>
                <tr>
                    <td>
                        <select name="classement[<?= $music['id'] ?>]" required>
                            <option value="">--</option>
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </td>
                    <td><?= htmlspecialchars($music['titre']) ?></td>
                    <td><?= htmlspecialchars($music['nom_artiste']) ?></td>
                    <td>
                        <a href="<?= htmlspecialchars($music['lien_youtube']) ?>" target="_blank">▶️ Voir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

            <button type="submit" class="btn">💾 Enregistrer</button>
        </form>

    <?php else: ?>
        <p style="text-align:center; color:#bbb; margin-top:20px;">
            ⭐ Tu as déjà voté ! Merci pour ta participation ⭐
        </p>
    <?php endif; ?>

</div>

<footer>
    &copy; 2025 WAVE - Tous droits réservés
</footer>

</body>
</html>