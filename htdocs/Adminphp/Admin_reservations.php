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

// Supprimer une réservation
if (isset($_GET['delete'])) {
    $reservationId = $_GET['delete'];
    $reservations = loadReservations($csvFilePath);
    $updatedReservations = array_filter($reservations, function($reservation) use ($reservationId) {
        return $reservation[0] != $reservationId;
    });
    saveReservations($csvFilePath, $updatedReservations);
    header('Location: Admin_reservations.php');
    exit;
}

$reservations = loadReservations($csvFilePath);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Réservations</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header>
        <h1>Gestion des Réservations</h1>
        <nav>
            <a href="Page_admin.php">Retour au tableau de bord</a>
        </nav>
    </header>
    <main>
        <section>
            <table>
                <thead>
                    <tr>
                        <th>Réservation ID</th>
                        <th>Référence</th>
                        <th>Date de Départ</th>
                        <th>Date de Retour</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Assurance</th>
                        <th>KM Supplémentaires</th>
                        <th>Notes Additionnelles</th>
                        <th>Prix Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation[0]); ?></td>
                        <td><?php echo htmlspecialchars($reservation[1]); ?></td>
                        <td><?php echo htmlspecialchars($reservation[2]); ?></td>
                        <td><?php echo htmlspecialchars($reservation[3]); ?></td>
                        <td><?php echo htmlspecialchars($reservation[4]); ?></td>
                        <td><?php echo htmlspecialchars($reservation[5]); ?></td>
                        <td><?php echo htmlspecialchars($reservation[6]); ?></td>
                        <td><?php echo htmlspecialchars($reservation[7]); ?></td>
                        <td><?php echo htmlspecialchars($reservation[8]); ?></td>
                        <td><?php echo htmlspecialchars($reservation[9]); ?></td>
                        <td><?php echo htmlspecialchars($reservation[10]); ?>€</td>
                        <td>
                            <a href="Admin_modifier_reservation.php?id=<?php echo urlencode($reservation[0]); ?>">Modifier</a> |
                            <a href="Admin_reservations.php?delete=<?php echo urlencode($reservation[0]); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">Supprimer</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <p>© 2024 Cy loc - Tous droits réservés</p>
    </footer>
</body>
</html>
