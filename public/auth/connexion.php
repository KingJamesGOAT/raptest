    <?php
    const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../../src/config/database.ini';

    session_start();

    if (isset($_SESSION['user_id'])) {
        header('Location: ././index.php');
        exit();
    }

    $error = '';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nom_utilisateur = $_POST["nom_utilisateur"] ?? '';
        $mot_de_passe   = $_POST["mot_de_passe"] ?? '';

        if (empty($nom_utilisateur) || empty($mot_de_passe)) {
            $error = 'Tous les champs sont obligatoires.';
        } else {
            try {
                // Lire la config ($host, $port, $dbname, $username, $password)
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

                // Connexion PDO
                $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);

                //Récupérer l'utilisateur par nom_utilisateur
                $stmt = $pdo->prepare('SELECT * FROM utilisateurs_wave WHERE nom_utilisateur = :nom_utilisateur');
                $stmt->execute(['nom_utilisateur' => $nom_utilisateur]);
                $user = $stmt->fetch();

                // Vérifier le mot de passe
                if ($user && $mot_de_passe === $user['mot_de_passe']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nom_utilisateur'] = $user['nom_utilisateur'];
                        $_SESSION['role'] = $user['role']; 
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
    <html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/auth.css">
        <title>Se connecter | Gestion des sessions</title>
    </head>

    <body>
        <?php include '../nav/nav.php'; ?>
        <main class="container">
            <h1>Se connecter</h1>

            <?php if (!empty($error)): ?>
                <article style="background-color: var(--pico-del-color);">
                    <p><strong>Erreur :</strong> <?= htmlspecialchars($error) ?></p>
                </article>
            <?php endif; ?>

            <form method="post" action="">
                <label for="nom_utilisateur">
                    Nom d'utilisateur
                    <input type="text" id="nom_utilisateur" name="nom_utilisateur" required autofocus>
                </label>

                <label for="mot_de_passe">
                    Mot de passe
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </label>

                <button type="submit">Se connecter</button>
            </form>

            <p>Pas encore de compte ? <a href="inscription.php">Créer un compte</a></p>
            <p><a href="../index.php">Retour à l'accueil</a></p>
        </main>
    </body>

    </html>