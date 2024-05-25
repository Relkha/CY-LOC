<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
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

    $user = loadUserProfile($jsonFilePath, $_SESSION['pseudo']);

    if (password_verify($_POST['old_password'], $user['Mdp'])) {
        $_SESSION['password_verified'] = true;
        header('Location: change_mdp.php');
        exit;
    } else {
        $error = "Ancien mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérifier le mot de passe</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
    <header>
        <h1>Vérifier le mot de passe</h1>
    </header>
    <main>
        <form method="post">
            <label for="old_password">Ancien mot de passe:</label>
            <input type="password" name="old_password" id="old_password" required><br>
            <input type="submit" value="Vérifier">
        </form>
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    </main>
    <footer>
        <p>© 2024 Cy loc - Tous droits réservés</p>
    </footer>
</body>
</html>
