<?php
session_start();

// Chemin du fichier JSON
$jsonFilePath = "data/donnees.json";

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier si les données de formulaire sont envoyées
    if (isset($_POST['nom'], $_POST['prénom'], $_POST['genre'], $_POST['email'], $_POST['phone'], $_POST['date'], $_POST['adresse'], $_POST['metier'], $_POST['pseudo'], $_POST['mdp'])) {
        // Vérifier si les champs ne sont pas vides
        if (!empty($_POST['nom']) && !empty($_POST['prénom']) && !empty($_POST['genre']) && !empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['date']) && !empty($_POST['adresse']) && !empty($_POST['metier']) && !empty($_POST['pseudo']) && !empty($_POST['mdp'])) {
            
            // Vérifier si le fichier JSON existe, sinon le créer
            if (!file_exists($jsonFilePath)) {
                file_put_contents($jsonFilePath, json_encode([]));  // Créer un fichier vide avec un tableau vide
            }

            // Lire le fichier JSON
            $json = file_get_contents($jsonFilePath);
            $data = json_decode($json, true);

            // Vérifier si le pseudo existe déjà
            $exists = array_reduce($data, function ($carry, $user) {
                return $carry || ($user['Pseudo'] === $_POST['pseudo']);
            }, false);

            if ($exists) {
                echo "<script>setTimeout(\"location.href = 'inscription.php';\", 3000);</script>";
                echo "Le nom d'utilisateur existe déjà. Veuillez en choisir un autre.<br>";
                echo "Redirection en cours...<br>";
                exit();
            }

            // Hachage du mot de passe pour la sécurité
            $hashed_password = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

            // Ajouter le nouvel utilisateur avec le rôle 'user'
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
                "Mdp" => $hashed_password,
                "Role" => "user"  // Définir le rôle comme 'user'
            ];
            $data[] = $newUser;

            // Sauvegarder le nouveau tableau en JSON
            if (file_put_contents($jsonFilePath, json_encode($data, JSON_PRETTY_PRINT)) === false) {
                echo "Erreur lors de l'enregistrement des données. Veuillez réessayer plus tard.<br>";
            } else {
                echo "<script>setTimeout(\"location.href = 'form_connexion.php';\", 3000);</script>";
                echo "Inscription réussie !<br>";
                echo "Redirection vers la page de connexion en cours...<br>";
            }
        } else {
            echo "Des valeurs sont vides<br>";
            echo "Redirection en cours...<br>";
        }
    } else {
        echo "Des valeurs ne sont pas envoyées<br>";
        echo "Redirection en cours...<br>";
    }
} else {
    echo "Requête invalide<br>";
}
?>
