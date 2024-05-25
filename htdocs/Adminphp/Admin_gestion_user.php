<?php
session_start();
// Vérifiez si l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: connexion.php');
    exit;
}

// Chemin du fichier JSON pour les données des utilisateurs
$jsonFilePath = "../data/donnees.json";

// Fonction pour charger et retourner les utilisateurs
function loadUsers($filePath) {
    if (!file_exists($filePath)) {
        return []; // Retourne un tableau vide si le fichier n'existe pas
    }
    $json = file_get_contents($filePath);
    return json_decode($json, true);
}

$users = loadUsers($jsonFilePath);

// Ajouter ou mettre à jour un utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pseudo'])) {
    // Récupérez les données du formulaire
    $newUser = [
        "Nom" => $_POST['nom'],
        "Prénom" => $_POST['prénom'],
        "Genre" => $_POST['genre'],
        "Email" => $_POST['email'],
        "Téléphone" => $_POST['phone'],
        "Date de naissance" => $_POST['date'],
        "Adresse" => $_POST['adresse'],
        "Métier" => $_POST['metier'],
        "Pseudo" => $_POST['pseudo'],
        "Mdp" => password_hash($_POST['mdp'], PASSWORD_DEFAULT), // Toujours hacher le mot de passe
        "Role" => $_POST['role']
    ];

    // Recherche si l'utilisateur existe déjà
    $index = array_search($_POST['pseudo'], array_column($users, 'Pseudo'));
    if ($index !== false) {
        $users[$index] = $newUser;
    } else {
        $users[] = $newUser; // Ajoute un nouvel utilisateur si non trouvé
    }

    // Sauvegardez les données dans le fichier
    file_put_contents($jsonFilePath, json_encode($users, JSON_PRETTY_PRINT));
    header('Location: Admin_gestion_user.php'); // Redirection pour éviter le re-post des données
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header>
        <h1>Gestion des Utilisateurs</h1>
        <li><button onclick="history.back()">Retour</button></li>
    </header>
    <main>
        <section>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Pseudo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['Nom']); ?></td>
                        <td><?php echo htmlspecialchars($user['Prénom']); ?></td>
                        <td><?php echo htmlspecialchars($user['Email']); ?></td>
                        <td><?php echo htmlspecialchars($user['Pseudo']); ?></td>
                        <td>
                            <a href="Admin_modif_user.php?pseudo=<?php echo urlencode($user['Pseudo']); ?>">Modifier</a> |
                            <a href="Admin_suppr_user.php?pseudo=<?php echo urlencode($user['Pseudo']); ?>">Supprimer</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
        <section>
            <h2>Ajouter / Modifier un utilisateur</h2>
            <form method="post">
                Nom: <input type="text" name="nom" required><br>
                Prénom: <input type="text" name="prénom" required><br>
                Genre: <input type="text" name="genre" required><br>
                Email: <input type="email" name="email" required><br>
                Téléphone: <input type="text" name="phone" required><br>
                Date de naissance: <input type="date" name="date" required><br>
                Adresse: <input type="text" name="adresse" required><br>
                Métier: <input type="text" name="metier" required><br>
                Pseudo: <input type="text" name="pseudo" required><br>
                Mot de passe: <input type="password" name="mdp" required><br>
                Rôle: <select name="role">
                    <option value="user">Utilisateur</option>
                    <option value="admin">Administrateur</option>
                </select><br>
                <input type="submit" value="Sauvegarder">
            </form>
        </section>
    </main>
    <footer>
        <p>© 2024 Cy loc - Tous droits réservés</p>
    </footer>
</body>
</html>
