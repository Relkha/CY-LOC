<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['password_verified']) || $_SESSION['password_verified'] !== true) {
    header('Location: form_connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    $user = loadUserProfile($jsonFilePath, $_SESSION['pseudo']);

    if ($_POST['new_password'] === $_POST['confirm_password']) {
        $user['Mdp'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        saveUserProfile($jsonFilePath, $user);
        unset($_SESSION['password_verified']);
        header('Location: profil.php');
        exit;
    } else {
        $error = "Les mots de passe ne correspondent pas.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Changer le mot de passe</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
    <header>
        <h1>Changer le mot de passe</h1>
    </header>
    <main>
        <form method="post">
            <label for="new_password">Nouveau mot de passe:</label>
            <input type="password" name="new_password" id="new_password" required><br>
            <label for="confirm_password">Confirmer le mot de passe:</label>
            <input type="password" name="confirm_password" id="confirm_password" required><br>
            <input type="submit" value="Changer le mot de passe">
        </form>
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    </main>
    <footer>
        <p>© 2024 Cy loc - Tous droits réservés</p>
    </footer>
</body>
</html>
