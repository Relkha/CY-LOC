<?php
session_start();
// Vérifiez si l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: connexion.php');
    exit;
}

$jsonFilePath = "../data/donnees.json";

// Chargement des données utilisateur
function loadUsers($filePath) {
    if (!file_exists($filePath)) {
        return []; // Retourne un tableau vide si le fichier n'existe pas
    }
    $json = file_get_contents($filePath);
    return json_decode($json, true);
}

function getUserIndex($users, $pseudo) {
    foreach ($users as $index => $user) {
        if ($user['Pseudo'] == $pseudo) {
            return $index;
        }
    }
    return -1;
}

$users = loadUsers($jsonFilePath);
$userIndex = getUserIndex($users, $_GET['pseudo']);
$user = $users[$userIndex] ?? null;

if (!$user) {
    echo "Utilisateur non trouvé.";
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pseudo'])) {
    // Mise à jour des informations
    $users[$userIndex] = [
        "Nom" => $_POST['nom'],
        "Prénom" => $_POST['prénom'],
        "Genre" => $_POST['genre'],
        "Email" => $_POST['email'],
        "Téléphone" => $_POST['phone'],
        "Date de naissance" => $_POST['date'],
        "Adresse" => $_POST['adresse'],
        "Métier" => $_POST['metier'],
        "Pseudo" => $_POST['pseudo'],
        "Mdp" => $user['Mdp'], // On ne change pas le mot de passe ici
        "Role" => $_POST['role']
    ];

    file_put_contents($jsonFilePath, json_encode($users, JSON_PRETTY_PRINT));
    header('Location: Admin_gestion_user.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>Modifier Utilisateur</h1>
    <li><button onclick="history.back()">Retour</button></li>
    <form method="post">
        Nom: <input type="text" name="nom" value="<?php echo htmlspecialchars($user['Nom']); ?>" required><br>
        Prénom: <input type="text" name="prénom" value="<?php echo htmlspecialchars($user['Prénom']); ?>" required><br>
        Genre: <input type="text" name="genre" value="<?php echo htmlspecialchars($user['Genre']); ?>" required><br>
        Email: <input type="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required><br>
        Téléphone: <input type="text" name="phone" value="<?php echo htmlspecialchars($user['Téléphone']); ?>" required><br>
        Date de naissance: <input type="date" name="date" value="<?php echo htmlspecialchars($user['Date de naissance']); ?>" required><br>
        Adresse: <input type="text" name="adresse" value="<?php echo htmlspecialchars($user['Adresse']); ?>" required><br>
        Métier: <input type="text" name="metier" value="<?php echo htmlspecialchars($user['Métier']); ?>" required><br>
        Pseudo: <input type="text" name="pseudo" value="<?php echo htmlspecialchars($user['Pseudo']); ?>" required><br>
        Role: <select name="role">
            <option value="user" <?php echo $user['Role'] == 'user' ? 'selected' : ''; ?>>Utilisateur</option>
            <option value="admin" <?php echo $user['Role'] == 'admin' ? 'selected' : ''; ?>>Administrateur</option>
        </select><br>
        <input type="submit" value="Enregistrer les modifications">
    </form>
</body>
</html>
