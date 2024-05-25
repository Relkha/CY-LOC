<?php
session_start();

// Vérifiez si l'utilisateur est connecté, sinon redirigez vers la page de connexion
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: form_connexion.php');
    exit;
}

// Chemin du fichier JSON contenant les données des utilisateurs et des réservations
$jsonFilePath = "data/donnees.json";
$reservationsFilePath = "data/reservations.csv";

// Chargement des données utilisateurs
function loadUserProfile($filePath, $pseudo) {
    $json = file_get_contents($filePath);
    $users = json_decode($json, true);

    foreach ($users as $user) {
        if ($user['Pseudo'] === $pseudo) {
            return $user;
        }
    }
    return null;
}

// Chargement des données de réservation de l'utilisateur
function loadUserReservations($filePath, $email) {
    $reservations = [];
    if (file_exists($filePath)) {
        $file = fopen($filePath, "r");
        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
            if ($data[6] === $email) {
                $reservations[] = $data;
            }
        }
        fclose($file);
    }
    return $reservations;
}

// Charger les données de profil de l'utilisateur connecté
$user = loadUserProfile($jsonFilePath, $_SESSION['pseudo']);

if (!$user) {
    echo "Profil utilisateur non trouvé.";
    exit;
}

// Charger les réservations de l'utilisateur connecté
$reservations = loadUserReservations($reservationsFilePath, $user['Email']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil de <?php echo htmlspecialchars($user['Prénom']); ?></title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <header>
        <h1>Profil de <?php echo htmlspecialchars($user['Prénom']); ?></h1>
        <li><a href="index.php">Retour à l'Accueil</a></li>
    </header>
    <main>
        <h2>Vos informations personnelles</h2>
        <p><strong>Nom:</strong> <?php echo htmlspecialchars($user['Nom']); ?></p>
        <p><strong>Prénom:</strong> <?php echo htmlspecialchars($user['Prénom']); ?></p>
        <p><strong>Genre:</strong> <?php echo htmlspecialchars($user['Genre']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
        <p><strong>Téléphone:</strong> <?php echo htmlspecialchars($user['Téléphone']); ?></p>
        <p><strong>Date de naissance:</strong> <?php echo htmlspecialchars($user['Date de naissance']); ?></p>
        <p><strong>Adresse:</strong> <?php echo htmlspecialchars($user['Adresse']); ?></p>
        <p><strong>Métier:</strong> <?php echo htmlspecialchars($user['Métier']); ?></p>
        <p><strong>Rôle:</strong> <?php echo htmlspecialchars($user['Role']); ?></p>
        <a href="edit_profil.php" class="edit-btn">Modifier mon profil</a>

        <h2>Votre réservation actuelle</h2>
        <?php if (count($reservations) > 0) { ?>
            <?php foreach ($reservations as $reservation) { ?>
                <p><strong>Véhicule Réf:</strong> <?php echo htmlspecialchars($reservation[1]); ?></p>
                <p><strong>Date de départ:</strong> <?php echo htmlspecialchars($reservation[2]); ?></p>
                <p><strong>Date de retour:</strong> <?php echo htmlspecialchars($reservation[3]); ?></p>
                <p><strong>Assurance:</strong> <?php echo htmlspecialchars($reservation[7]); ?></p>
                <p><strong>KM Supplémentaires:</strong> <?php echo htmlspecialchars($reservation[8]); ?></p>
                <p><strong>Notes Additionnelles:</strong> <?php echo htmlspecialchars($reservation[9]); ?></p>
                <p><strong>Prix Total:</strong> <?php echo htmlspecialchars($reservation[10]); ?>€</p>
                <p><strong>Facture:</strong> <a href="factures/facture_<?php echo urlencode($reservation[0]); ?>.txt" target="_blank">Voir la facture</a></p>
            <?php } ?>
        <?php } else { ?>
            <p>Vous n'avez aucune réservation en cours.</p>
        <?php } ?>
    </main>
    <footer>
        <p>© 2024 Cy loc - Tous droits réservés</p>
    </footer>
</body>
</html>
