<?php
// 1. On charge la gestion des langues (Cookies)
require_once __DIR__ . '/../../src/outils/gestion_langue.php';
// 2. On charge l'autoloader (Pour PHPMailer et Database)
require_once __DIR__ . '/../../src/outils/autoloader.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

const MAIL_CONFIGURATION_FILE = __DIR__ . '/../../src/config/mail.ini';
const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../../src/config/database.ini';

// Lecture du fichier de config DB
$config = parse_ini_file(DATABASE_CONFIGURATION_FILE, true);
if (!$config) {
    die("Erreur : Impossible de lire le fichier de configuration DB.");
}

$db = $config['database'];
$host = $db['host'];
$port = $db['port'];
$dbname = $db['dbname'];
$username = $db['username'];
$password = $db['password'];

// Connexion à la base de données
$pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);

$error = '';
$success = '';

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $nom_utilisateur = $_POST["nom_utilisateur"];
    $age = $_POST["age"];
    $mot_de_passe = $_POST["mot_de_passe"];

    $errors = [];

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Un email valide est requis.";
    }
    if (empty($nom_utilisateur) || strlen($nom_utilisateur) < 2) {
        $errors[] = "Le nom d'utilisateur doit contenir au moins 2 caractères.";
    }
    if ($age < 0) {
        $errors[] = "L'âge doit être un nombre positif.";
    }
    if (strlen($mot_de_passe) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }

    if (empty($errors)) {
        // Vérification si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT email, nom_utilisateur FROM utilisateurs_wave WHERE email = :email OR nom_utilisateur = :nom_utilisateur");
        $stmt->bindValue(':nom_utilisateur', $nom_utilisateur);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            if ($user['nom_utilisateur'] === $nom_utilisateur) {
                $error = "Ce nom d'utilisateur est déjà pris.";
            } elseif ($user['email'] === $email) {
                $error = "Cet email est déjà utilisé.";
            }
        } else {
            // Insertion dans la BDD
            $sql = "INSERT INTO utilisateurs_wave (email, nom_utilisateur, age, mot_de_passe) VALUES (:email, :nom_utilisateur, :age, :mot_de_passe)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':nom_utilisateur', $nom_utilisateur);
            $stmt->bindValue(':age', $age);
            $stmt->bindValue(':mot_de_passe', $mot_de_passe);
            $stmt->execute();

            $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";

            // ---------------------------------------------------------
            // ENVOI DU MAIL (Partie rajoutée)
            // ---------------------------------------------------------
            if (!empty($success)) {
                $configMail = parse_ini_file(MAIL_CONFIGURATION_FILE, true);

                if (!$configMail) {
                    throw new Exception("Erreur configuration mail : " . MAIL_CONFIGURATION_FILE);
                }

                $hostMail = $configMail['host'];
                $portMail = filter_var($configMail['port'], FILTER_VALIDATE_INT);
                $authMail = filter_var($configMail['authentication'], FILTER_VALIDATE_BOOLEAN);
                $userMail = $configMail['username'];
                $passMail = $configMail['password'];
                $fromEmail = $configMail['from_email'];
                $fromName = $configMail['from_name'];

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = $hostMail;
                    $mail->Port = $portMail;
                    $mail->SMTPAuth = $authMail;
                    $mail->Username = $userMail;
                    $mail->Password = $passMail;
                    $mail->CharSet = "UTF-8";
                    $mail->Encoding = "base64";

                    // Expéditeur et destinataire
                    $mail->setFrom($fromEmail, $fromName);
                    $mail->addAddress($email, $nom_utilisateur);

                    // Contenu
                    $mail->isHTML(true);
                    $mail->Subject = 'Inscription à WaveMusic';
                    $mail->Body    = 'Bienvenue chez <b>WaveMusic</b>, ' . htmlspecialchars($nom_utilisateur) . ' on est ravi de te recevoir ';
                    $mail->AltBody = 'Bienvenue chez WaveMusic, ' . htmlspecialchars($nom_utilisateur) . ' on est ravi de te recevoir ';

                    $mail->send();
                    $success = "Un email de bienvenue t'a été envoyé 🎉";
                } catch (Exception $e) {
                    // On affiche l'erreur d'envoi mais on laisse le compte créé
                    $error = "Compte créé, mais erreur d'envoi mail : {$mail->ErrorInfo}";
                }
            }
            // ---------------------------------------------------------
        }
    } else {
        $error = implode('<br>', $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/auth.css">
    <title>Créer un compte</title>
</head>
<body>
    <?php include '../nav/nav.php'; ?>
    <main class="container">
        <h1><?= $textes['register_title'] ?></h1>

        <?php if (!empty($error)): ?>
            <p style="color: red; font-weight: bold; margin-top: 10px;">
                <?= $error ?>
            </p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="email"><?= $textes['label_email'] ?></label>
            <input type="email" id="email" name="email" required>

            <label for="nom_utilisateur"><?= $textes['label_user'] ?></label>
            <input type="text" id="nom_utilisateur" name="nom_utilisateur" required minlength="2">

            <label for="age"><?= $textes['label_age'] ?></label>
            <input type="number" id="age" name="age" required min="0">

            <label for="mot_de_passe"><?= $textes['label_password'] ?></label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required minlength="8">

            <button type="submit"><?= $textes['btn_create'] ?></button>
        </form>

        <?php if (!$success): ?>
            <p>Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p style="color: green;"><strong><?= $success ?></strong></p>
            <p><a href="connexion.php">Se connecter maintenant</a></p>
        <?php endif; ?>
        
        <p><a href="../index.php">Retour à l'accueil</a></p>
    </main>
</body>
</html>