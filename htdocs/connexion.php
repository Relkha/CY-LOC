<?php
session_start();

// Chemin du fichier JSON pour les données des utilisateurs
$jsonFilePath = "data/donnees.json";

// Fonction pour charger les utilisateurs depuis le fichier JSON
function loadUsers($filePath) {
    if (!file_exists($filePath)) {
        return null;  // Retourne null si le fichier n'existe pas
    }
    $json = file_get_contents($filePath);
    return json_decode($json, true);
}

// Vérification des données de formulaire envoyées
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pseudo'], $_POST['mdp'])) {
    $users = loadUsers($jsonFilePath);
    if ($users === null) {
        echo "Le fichier de données n'existe pas. Contactez l'administrateur.";
        header('Location: form_connexion.php');
        exit();
    }

    $found = false;
    foreach ($users as $user) {
        // Vérifie si le pseudo et le mot de passe correspondent
        if ($_POST['pseudo'] == $user['Pseudo'] && password_verify($_POST['mdp'], $user['Mdp'])) {
            $_SESSION['pseudo'] = $user['Pseudo']; // Stockage du pseudo dans la session
            $_SESSION['role'] = $user['Role'];     // Stockage du rôle dans la session
            $_SESSION['loggedin'] = true;          // Marqueur indiquant que l'utilisateur est connecté
            $_SESSION['statut'] = 1;               // Mise à jour du statut pour indiquer une connexion réussie

            $found = true;
            // Redirection en fonction du rôle de l'utilisateur
            if ($user['Role'] == 'admin') {
                header("Location: Adminphp/Page_admin.php");
                exit();
            } else {
                header("Location: index.php");
                exit();
            }
        }
    }

    if (!$found) {
        echo "Nom d'utilisateur ou mot de passe incorrect. Veuillez réessayer.";
        $_SESSION['statut'] = 0;  // Réinitialiser le statut en cas d'échec de connexion
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'form_connexion.php'; 
                }, 2000);  // Délai en millisecondes
              </script>";
    }
    
    
} else {
    echo "Veuillez remplir tous les champs pour vous connecter.";
}
?>
