<?php

require_once __DIR__ . '/../../src/outils/autoloader.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

const MAIL_CONFIGURATION_FILE = __DIR__ . '/../../src/config/mail.ini';

// === Connexion à la base ===
const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../../src/config/database.ini';

// Lecture du fichier INI
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

// Connexion simple à la base (comme vu en cours)
$pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);

// --- Variables pour messages ---
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
        // Vérifie si le nom existe déjà
        $stmt = $pdo->prepare("
    SELECT email, nom_utilisateur 
    FROM utilisateurs_wave
    WHERE email = :email OR nom_utilisateur = :nom_utilisateur
");

        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':nom_utilisateur', $nom_utilisateur);
        $stmt->execute();

        $existing = $stmt->fetch();

        if ($existing) {
            if ($existing['email'] === $email) {
                $error = "Cet email est déjà utilisé.";
            } elseif ($existing['nom_utilisateur'] === $nom_utilisateur) {
                $error = "Ce nom d'utilisateur est déjà pris.";
            }
        }


        if (empty($error)) {

            // Insertion dans la base 
            $sql = "INSERT INTO utilisateurs_wave (email, nom_utilisateur, age, mot_de_passe)
            VALUES (:email, :nom_utilisateur, :age, :mot_de_passe)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':nom_utilisateur', $nom_utilisateur);
            $stmt->bindValue(':age', $age);
            $stmt->bindValue(':mot_de_passe', $mot_de_passe);
            $stmt->execute();

            $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";


            if ($success) {

                //ENVOIE DU MAIL

                $config = parse_ini_file(MAIL_CONFIGURATION_FILE, true);

                if (!$config) {
                    throw new Exception("Erreur lors de la lecture du fichier de configuration : " .
                        MAIL_CONFIGURATION_FILE);
                }

                $host = $config['host'];
                $port = filter_var($config['port'], FILTER_VALIDATE_INT);
                $authentication = filter_var($config['authentication'], FILTER_VALIDATE_BOOLEAN);
                $username = $config['username'];
                $password = $config['password'];
                $from_email = $config['from_email'];
                $from_name = $config['from_name'];

                $mail = new PHPMailer(true);


                try {
                    $mail->isSMTP();
                    $mail->Host = $host;
                    $mail->Port = $port;
                    //On a demandé à chat, et sa fonctionne pas sans.
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->SMTPAuth = $authentication;
                    $mail->Username = $username;
                    $mail->Password = $password;

                    // // ⭐ POUR VOIR SI UNE ERREUR APPARAÎT (à enlever ensuite)
                    // $mail->SMTPDebug = 2;
                    // $mail->Debugoutput = 'html';

                    $mail->CharSet = "UTF-8";
                    $mail->Encoding = "base64";

                    // Expéditeur et destinataire
                    $mail->setFrom($from_email, $from_name);
                    $mail->addAddress($email, $nom_utilisateur); // On envoie au nouvel utilisateur

                    // Contenu du mail
                    $mail->isHTML(true);
                    $mail->Subject = 'Inscrption à WaveMusic';
                    $mail->Body    = 'Bienvenue chez <b>WaveMusic</b>, ' . htmlspecialchars($nom_utilisateur) . ' on est ravi de te recevoir ';
                    $mail->AltBody = 'Bienvenue chez <b>WaveMusic</b>, ' . htmlspecialchars($nom_utilisateur) . ' on est ravi de te recevoir ';

                    // Envoi
                    $mail->send();

                    $success = "Un email de bienvenue t'a été envoyé 🎉";
                } catch (Exception $e) {
                    $error = "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
                }
            }
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
        <h1>Créer un compte</h1>

        <?php if (!empty($error)): ?>
            <p style="color: red; font-weight: bold; margin-top: 10px;">
                <?= $error ?>
            </p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>

            <label for="nom_utilisateur">Nom d'utilisateur</label>
            <input type="text" id="nom_utilisateur" name="nom_utilisateur" required minlength="2">

            <label for="age">Âge</label>
            <input type="number" id="age" name="age" required min="1">

            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required minlength="8">

            <button type="submit">Créer mon compte</button>
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