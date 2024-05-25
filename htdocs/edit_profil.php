<?php
session_start();

// Vérifiez si l'utilisateur est connecté, sinon redirigez vers la page de connexion
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: form_connexion.php');
    exit;
}

// Chemin du fichier JSON contenant les données des utilisateurs
$jsonFilePath = "data/donnees.json";

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

function saveUserProfile($filePath, $updatedUser) {
    $json = file_get_contents($filePath);
    $users = json_decode($json, true);

    foreach ($users as &$user) {
        if ($user['Pseudo'] === $updatedUser['Pseudo']) {
            $user = $updatedUser;
            break;
        }
    }

    file_put_contents($filePath, json_encode($users, JSON_PRETTY_PRINT));
}

// Chargez les données de profil de l'utilisateur connecté
$user = loadUserProfile($jsonFilePath, $_SESSION['pseudo']);

if (!$user) {
    echo "Profil utilisateur non trouvé.";
    exit;
}

// Mise à jour des informations de l'utilisateur si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user['Nom'] = $_POST['nom'];
    $user['Prénom'] = $_POST['prenom'];
    $user['Genre'] = $_POST['genre'];
    $user['Email'] = $_POST['email'];
    $user['Téléphone'] = $_POST['phone'];
    $user['Date de naissance'] = $_POST['date_naissance'];
    $user['Adresse'] = $_POST['adresse'];
    $user['Métier'] = $_POST['metier'];

    saveUserProfile($jsonFilePath, $user);

    header('Location: profil.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Profil</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <header>
        <h1>Modifier Profil</h1>
    </header>
    <main>
        <h2>Modifier vos informations personnelles</h2>
        <form method="post">
            Nom: <input type="text" name="nom" value="<?php echo htmlspecialchars($user['Nom']); ?>" required><br>
            Prénom: <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['Prénom']); ?>" required><br>
            Genre: <input type="text" name="genre" value="<?php echo htmlspecialchars($user['Genre']); ?>" required><br>
            Email: <input type="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required><br>
            Téléphone: <input type="text" name="phone" value="<?php echo htmlspecialchars($user['Téléphone']); ?>" required><br>
            Date de naissance: <input type="date" name="date_naissance" value="<?php echo htmlspecialchars($user['Date de naissance']); ?>" required><br>
            Adresse: <input type="text" name="adresse" value="<?php echo htmlspecialchars($user['Adresse']); ?>" required><br>
            Métier: <input type="text" name="metier" value="<?php echo htmlspecialchars($user['Métier']); ?>" required><br>
            <input type="submit" value="Mettre à jour">
        </form>
        <a href="check_mdp.php" class="btn">Modifier le mot de passe</a>
    </main>
    <footer>
        <p>© 2024 Cy loc - Tous droits réservés</p>
    </footer>
</body>
</html>
