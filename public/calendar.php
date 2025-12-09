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

// Paramètres DB
$db       = $config['database'];
$host     = $db['host'];
$port     = $db['port'];
$dbname   = $db['dbname'];
$username = $db['username'];
$password = $db['password'];

// Connexion à la DB
$pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);


// Requête : concerts + artistes
$sql = "
    SELECT 
        c.date,
        c.nom_salle,
        c.ville,
        c.canton_unicode,
        c.heure_debut,
        a.nom_artiste
    FROM concerts_wave AS c
    JOIN artistes_wave AS a ON c.artiste_id = a.id
    ORDER BY c.date ASC, c.heure_debut ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$concerts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>WAVE - Calendrier des prochains concerts</title>
<link rel="stylesheet" href="css/style.css">

<style>
    body {
        background: #050505;
        color: #fff;
    }

    .page-calendrier {
        max-width: 900px;
        margin: 2rem auto 3rem auto;
        padding: 0 1rem;
    }

    .page-calendrier h1 {
        font-size: 2.2rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 0.3rem;
    }

    .page-calendrier h1::before {
        content: "📅";
        font-size: 2.1rem;
    }

    .calendrier-intro {
        opacity: 0.85;
        margin-bottom: 1.5rem;
    }

    /* Container des cartes */
    .calendrier-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .concert-card {
        display: grid;
        grid-template-columns: 110px 1fr;
        gap: 1rem;
        padding: 1rem 1.3rem;
        border-radius: 1rem;
        border: 1px solid #333;
        background: radial-gradient(circle at top left, #26226255, #101010 60%);
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }

    .concert-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(0,0,0,0.6);
        border-color: #6c63ff;
    }

    /* Bloc date */
    .concert-date {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.6rem 0.4rem;
        border-radius: 0.8rem;
        background: #111;
        border: 1px solid #333;
        min-height: 80px;
    }

    .concert-date-day {
        font-size: 1.6rem;
        font-weight: 700;
        line-height: 1;
    }

    .concert-date-month {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        opacity: 0.85;
    }

    .concert-date-year {
        font-size: 0.8rem;
        opacity: 0.6;
        margin-top: 0.2rem;
    }

    /* Bloc contenu */
    .concert-content {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .concert-artist {
        font-size: 1.1rem;
        font-weight: 700;
    }

    .concert-salle {
        font-size: 0.95rem;
        opacity: 0.9;
    }

    .concert-location {
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        opacity: 0.85;
    }

    .badge-canton {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.1rem 0.55rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        border: 1px solid #444;
        background: #181818;
    }

    /* Couleurs par canton (tu peux adapter) */
    .badge-GE { border-color: #7c4dff; color: #d3c1ff; }
    .badge-VD { border-color: #00c853; color: #b9ffd2; }
    .badge-FR { border-color: #ff9800; color: #ffe0b2; }
    .badge-ZH { border-color: #29b6f6; color: #b3e5fc; }

    .concert-meta {
        font-size: 0.85rem;
        opacity: 0.8;
        margin-top: 0.25rem;
    }

    .concert-time {
        font-weight: 600;
    }

    @media (max-width: 640px) {
        .concert-card {
            grid-template-columns: 1fr;
        }

        .concert-date {
            flex-direction: row;
            justify-content: flex-start;
            gap: 0.6rem;
            padding: 0.4rem 0.7rem;
        }

        .concert-date-day {
            font-size: 1.4rem;
        }
    }

    nav {
     display: flex;
     /* Utilise Flexbox pour aligner les éléments */
     justify-content: space-between;
     /* Espace entre logo et liens */
     align-items: center;
     /* Centre verticalement les éléments */
     padding: 15px 40px;
     /* Espacement interne du nav */
     background: #1a1a1a;
     /* Fond sombre */
     position: sticky;
     /* Reste visible en scrollant */
     top: 0;
     /* Position en haut de la page */
     z-index: 1000;
     /* Priorité au-dessus des autres éléments */
 }

 /* Logo dans la navigation */
 nav .logo {
     font-size: 1.5em;
     /* Taille du texte du logo */
     font-weight: bold;
     /* Texte en gras */
     color: #4da6ff;
     /* Couleur bleue */
 }

 /* Liens de navigation */
 nav .nav-links a {
     margin-left: 20px;
     /* Espace entre les liens */
     text-decoration: none;
     /* Supprime le soulignement */
     color: #eee;
     /* Couleur texte blanche */
     font-weight: 500;
     /* Poids moyen */
     transition: color 0.3s;
     /* Animation lors du survol */
 }

 nav .nav-links a:hover {
     color: #4da6ff;
     /* Change la couleur au survol */
 }
</style>
</head>
<body>

<?php include 'nav/nav.php'; ?>

<main class="page-calendrier">

    <h1>Calendrier des concerts</h1>
    <p class="calendrier-intro">
        Tous les prochains concerts rap WAVE en Suisse, triés par date.
    </p>

    <?php if (empty($concerts)): ?>
        <p>Pas encore de dates enregistrées.</p>
    <?php else: ?>
        <section class="calendrier-list">
            <?php foreach ($concerts as $concert): ?>
                <?php
                    // Format date
                    $timestamp = strtotime($concert['date']);
                    $day   = date('d', $timestamp);
                    $month = date('M', $timestamp);
                    $year  = date('Y', $timestamp);

                    // Canton badge class
                    $canton = $concert['canton_unicode'];
                    $badgeClass = 'badge-canton';
                    if ($canton === 'GE') $badgeClass .= ' badge-GE';
                    elseif ($canton === 'VD') $badgeClass .= ' badge-VD';
                    elseif ($canton === 'FR') $badgeClass .= ' badge-FR';
                    elseif ($canton === 'ZH') $badgeClass .= ' badge-ZH';

                    // Heure
                    $heure = $concert['heure_debut']
                        ? substr($concert['heure_debut'], 0, 5)
                        : 'À confirmer';
                ?>

                <article class="concert-card">
                    <div class="concert-date">
                        <div class="concert-date-day"><?= $day ?></div>
                        <div>
                            <div class="concert-date-month"><?= strtoupper($month) ?></div>
                            <div class="concert-date-year"><?= $year ?></div>
                        </div>
                    </div>

                    <div class="concert-content">
                        <div class="concert-artist">
                            <?= htmlspecialchars($concert['nom_artiste']) ?>
                        </div>

                        <div class="concert-salle">
                            <?= htmlspecialchars($concert['nom_salle']) ?>
                        </div>

                        <div class="concert-location">
                            <span><?= htmlspecialchars($concert['ville']) ?></span>
                            <span class="<?= $badgeClass ?>">
                                <?= htmlspecialchars($canton) ?>
                            </span>
                        </div>

                        <div class="concert-meta">
                            Heure : <span class="concert-time"><?= htmlspecialchars($heure) ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

</main>

<footer>
    &copy; 2025 WAVE - Tous droits réservés
</footer>

</body>
</html>

 