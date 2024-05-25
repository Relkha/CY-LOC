<?php
session_start();
if ($_SESSION['statut'] == 1){ // statut connecté
    $_SESSION['statut'] = 0; // statut non connecté
    echo "<script>setTimeout(\"location.href = 'index.php';\",3000);</script>";
    echo "Vous avez bien été déconnecté"."<br>";
    echo 'Redirection en cours...';
    exit();
}
else{
    echo "<script>setTimeout(\"location.href = 'index.php';\",3000);</script>";
    echo "Une erreur est survenue"."<br>";
    echo 'Redirection en cours...';
    exit();
}
?>