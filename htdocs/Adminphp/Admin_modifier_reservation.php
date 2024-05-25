<?php
session_start();

// Vérifiez si l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: connexion.php');
    exit;
}

$csvFilePath = "../data/reservations.csv";

// Chargement des données de réservations
function loadReservations($filePath) {
    $reservations = [];
    if (!file_exists($filePath)) {
        return $reservations; // Retourne un tableau vide si le fichier n'existe pas
    }
    $file = fopen($filePath, "r");
    while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
        $reservations[] = $data;
    }
    fclose($file);
    return $reservations;
}

// Sauvegarde des données de réservations
function saveReservations($filePath, $reservations) {
    $file = fopen($filePath, 'w');
    foreach ($reservations as $reservation) {
        fputcsv($file, $reservation);
    }
    fclose($file);
}

// Récupérer l'index de la réservation à modifier
function getReservationIndex($reservations, $reservationId) {
    foreach ($reservations as $index => $reservation) {
        if ($reservation[0] == $reservationId) {
            return $index;
        }
    }
    return -1;
}

$reservations = loadReservations($csvFilePath);
$reservationId = $_GET['id'];
$reservationIndex = getReservationIndex($reservations, $reservationId);
$reservation = $reservations[$reservationIndex] ?? null;

if (!$reservation) {
    echo "Réservation non trouvée.";
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservations[$reservationIndex] = [
        $_POST['reservation_id'],
        $_POST['ref'],
        $_POST['date_depart'],
        $_POST['date_retour'],
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['email'],
        $_POST['insurance'],
        $_POST['extra_km'],
        $_POST['additional_notes'],
        $_POST['total_price']
    ];

    saveReservations($csvFilePath, $reservations);

    header('Location: Admin_reservations.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Réservation</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header>
        <h1>Modifier Réservation</h1>
        <nav>
            <a href="Admin_reservations.php">Retour à la gestion des réservations</a>
        </nav>
    </header>
    <main>
        <form method="post">
            <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($reservation[0]); ?>">
            Référence: <input type="text" name="ref" value="<?php echo htmlspecialchars($reservation[1]); ?>" required><br>
            Date de départ: <input type="date" name="date_depart" value="<?php echo htmlspecialchars($reservation[2]); ?>" required><br>
            Date de retour: <input type="date" name="date_retour" value="<?php echo htmlspecialchars($reservation[3]); ?>" required><br>
            Nom: <input type="text" name="nom" value="<?php echo htmlspecialchars($reservation[4]); ?>" required><br>
            Prénom: <input type="text" name="prenom" value="<?php echo htmlspecialchars($reservation[5]); ?>" required><br>
            Email: <input type="email" name="email" value="<?php echo htmlspecialchars($reservation[6]); ?>" required><br>
            Assurance: <input type="text" name="insurance" value="<?php echo htmlspecialchars($reservation[7]); ?>" required><br>
            KM Supplémentaires: <input type="text" name="extra_km" value="<?php echo htmlspecialchars($reservation[8]); ?>" required><br>
            Notes Additionnelles: <input type="text" name="additional_notes" value="<?php echo htmlspecialchars($reservation[9]); ?>"><br>
            Prix Total: <input type="text" name="total_price" value="<?php echo htmlspecialchars($reservation[10]); ?>" required><br>
            <input type="submit" value="Enregistrer les modifications">
        </form>
    </main>
    <footer>
        <p>© 2024 Cy loc - Tous droits réservés</p>
    </footer>
</body>
</html>
