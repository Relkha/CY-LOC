<?php

// Fonction pour charger les données de l'utilisateur connecté
function loadUserData($file, $pseudo) {
    $json = file_get_contents($file);
    $users = json_decode($json, true);
    foreach ($users as $user) {
        if ($user['Pseudo'] === $pseudo) {
            return $user;
        }
    }
    return null;
}

// Fonction pour vérifier la disponibilité des dates pour une référence donnée
function checkAvailability($filename, $date_depart, $date_retour, $ref) {
    $handle = fopen($filename, "r");
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if ($data[0] == $ref && (
            ($date_depart <= $data[2] && $date_depart >= $data[1]) ||
            ($date_retour <= $data[2] && $date_retour >= $data[1])
        )) {
            fclose($handle);
            return false; // Indique que la période n'est pas disponible
        }
    }
    fclose($handle);
    return true;
}

// Fonction pour ajouter une nouvelle réservation au fichier CSV
function addReservation($filename, $data) {
    $handle = fopen($filename, 'a');
    fputcsv($handle, $data);
    fclose($handle);
}
