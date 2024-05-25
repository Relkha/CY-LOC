<?php
session_start();
// Vérifiez si l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: connexion.php');
    exit;
}

$jsonFilePath = "../data/donnees.json";

function loadUsers($filePath) {
    if (!file_exists($filePath)) {
        return [];
    }
    $json = file_get_contents($filePath);
    return json_decode($json, true);
}

function deleteUser($users, $pseudo) {
    foreach ($users as $index => $user) {
        if ($user['Pseudo'] == $pseudo) {
            array_splice($users, $index, 1);
            return $users;
        }
    }
    return $users;
}

if (isset($_GET['pseudo'])) {
    $users = loadUsers($jsonFilePath);
    $users = deleteUser($users, $_GET['pseudo']);
    file_put_contents($jsonFilePath, json_encode($users, JSON_PRETTY_PRINT));
    header('Location: Admin_gestion_user.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer Utilisateur</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>Supprimer Utilisateur</h1>
    <p>L'utilisateur a été supprimé.</p>
</body>
</html>
