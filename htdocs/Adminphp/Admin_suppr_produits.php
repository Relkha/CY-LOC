<?php
session_start();
// Vérifier si l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: connexion.php');
    exit;
}

$csvFilePath = "../data/catalogue.csv";
$refToDelete = $_GET['ref']; // Récupérer la référence du produit à supprimer

// Lire le fichier CSV et conserver toutes les lignes sauf celle à supprimer
$updatedData = [];
$file = fopen($csvFilePath, 'r');
while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
    if ($data[4] !== $refToDelete) { // Supposer que la référence du produit est à la 5ème position
        $updatedData[] = $data;
    }
}
fclose($file);

// Réécrire le fichier sans la ligne supprimée
$file = fopen($csvFilePath, 'w');
foreach ($updatedData as $line) {
    fputcsv($file, $line);
}
fclose($file);

header('Location: Admin_gestion_produits.php'); // Redirection vers la page de gestion des produits
exit;
?>
