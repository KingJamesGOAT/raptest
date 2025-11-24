<?php
session_start();

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Location: connexion.php');
    exit();
}

session_destroy();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/auth.css">
    <title>Déconnexion | Gestion des sessions</title>
</head>

<body>

    <?php include '../nav/nav.php'; ?>
    <main class="container">
        <h1>Déconnexion réussie</h1>

        <p>Vous avez été déconnecté.e avec succès.</p>

        <p><a href="../index.php">Retour à l'accueil</a> | <a href="connexion.php">Se connecter à nouveau</a></p>
    </main>
</body>

</html>