<?php
session_start();
include 'header.php';

// Récupérer les données du formulaire
$reservation_id = $_POST['reservation_id'];
$insurance = isset($_POST['insurance']) ? $_POST['insurance'] : 'no';
$extra_km = isset($_POST['extra_km']) ? $_POST['extra_km'] : 'no';

// Fichier CSV pour stocker les options
$optionsFile = 'data/options.csv';

// Assurez-vous que le fichier CSV existe avec l'en-tête approprié
if (!file_exists($optionsFile)) {
    $handle = fopen($optionsFile, 'w');
    fputcsv($handle, ['Reservation ID', 'Insurance', 'Extra KM']);
    fclose($handle);
}

// Ajouter les options sélectionnées au fichier CSV
function addOptions($filename, $data) {
    $handle = fopen($filename, 'a');
    fputcsv($handle, $data);
    fclose($handle);
}

// Appeler la fonction pour enregistrer les options
addOptions($optionsFile, [$reservation_id, $insurance, $extra_km]);

echo "<p>Vos options ont été enregistrées avec succès.</p>";
echo '<a href="index.php">Retour à la page d\'accueil</a>';

include 'footer.php';
?>
