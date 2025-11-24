<?php
require_once __DIR__ . '/../../src/outils/gestion_langue.php';

// Constantes
const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../../src/config/database.ini';

// Démarre la session
session_start();

// Si l'utilisateur est déjà connecté, le rediriger vers l'accueil
if (isset($_SESSION['user_id'])) {
    header('Location: ././index.php');
    exit();
}

// Initialise les variables
$error = '';

// Traite le formulaire de connexion
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom_utilisateur = $_POST["nom_utilisateur"] ?? '';
    $mot_de_passe   = $_POST["mot_de_passe"] ?? '';

    if (empty($nom_utilisateur) || empty($mot_de_passe)) {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        try {
            // 1) Lire la config (définit $host, $port, $dbname, $username, $password)
            $config = parse_ini_file(DATABASE_CONFIGURATION_FILE, true);
            if (!$config || !isset($config['database'])) {
                throw new Exception("Impossible de lire la configuration DB.");
            }
            $db = $config['database'];
            $host = $db['host'];
            $port = $db['port'];
            $dbname = $db['dbname'];
            $username = $db['username'];
            $password = $db['password'];

            // 2) Connexion PDO
            $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);

            // 3) Récupérer l'utilisateur par nom_utilisateur
            $stmt = $pdo->prepare('SELECT * FROM utilisateurs_wave WHERE nom_utilisateur = :nom_utilisateur');
            $stmt->execute(['nom_utilisateur' => $nom_utilisateur]);
            $user = $stmt->fetch();

            // 4) Vérifier le mot de passe
            if ($user && $mot_de_passe === $user['mot_de_passe']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nom_utilisateur'] = $user['nom_utilisateur'];
                header('Location: ../index.php');
                exit();
            } else {
                $error = "Nom d'utilisateur ou mot de passe incorrect.";
            }

        } catch (PDOException $e) {
            $error = 'Erreur lors de la connexion : ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $langue ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/auth.css">
    <title>Se connecter | Gestion des sessions</title>
</head>

<body>
    <?php include '../nav/nav.php'; ?>
    <main class="container">
        <h1><?= $textes['login_title'] ?></h1>

        <?php if (!empty($error)): ?>
            <article style="background-color: var(--pico-del-color);">
                <p><strong>Erreur :</strong> <?= htmlspecialchars($error) ?></p>
            </article>
        <?php endif; ?>

        <form method="post" action="">
            <label for="nom_utilisateur">
                <?= $textes['label_user'] ?>
                <input type="text" id="nom_utilisateur" name="nom_utilisateur" required autofocus>
            </label>

            <label for="mot_de_passe">
                <?= $textes['label_password'] ?>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </label>

            <button type="submit"><?= $textes['btn_connect'] ?></button>
        </form>

        <p><a href="inscription.php"><?= $textes['link_no_account'] ?></a></p>
        <p><a href="../index.php">Retour à l'accueil</a></p>
    </main>
</body>

</html>