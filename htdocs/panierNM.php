<?php
session_start(); 
include 'header.php'; 

// Vérifiez si l'utilisateur vient de la page panier.php
if (!isset($_SERVER['HTTP_REFERER']) || basename($_SERVER['HTTP_REFERER']) != "panier.php") { 
    header('Location: panier.php');
    exit;
}

//Vérifiez si l'utilisateur est connecté et si le panier n'est pas vide
if ($_SESSION['statut'] == 1 && isset($_SESSION['PanierRef']) && count($_SESSION['PanierRef']) != 0) {  
    echo '<div id="Page">';
    echo '<table border="1">';
    echo '<thead> <tr> <td>Nom</td> <td>Photo</td> <td>Ref</td><td> Réservation </td> <td>Prix</td> <td> Quantité </td> <td>Total Article</td> </tr> </thead>';
    $nom = $_SESSION['nom'] . "_" . $_SESSION['prenom'];
    $fileName = "facture_" . $nom . ".txt";
    $file = fopen($fileName, "w+");
    fwrite($file, "Voici votre facture :\n\n");
    fclose($file);

    $total = 0;
    for ($i = 0; $i < count($_SESSION['PanierNom']); $i++) {
        echo '<tr><td>' . htmlspecialchars($_SESSION['PanierNom'][$i]) . '</td>';
        echo '<td><img class="photoPanier" src="./img/' . htmlspecialchars($_SESSION['PanierImg'][$i]) . '"></td>';
        echo '<td>' . htmlspecialchars($_SESSION['PanierRef'][$i]) . '</td>';
        if ($_SESSION['PanierCat'][$i] == 0) {  
            echo '<td>' . htmlspecialchars($_SESSION['datee']) . '</td>'; // la date choisie 
        } elseif ($_SESSION['PanierCat'][$i] == 1) {
            echo '<td>' . htmlspecialchars($_SESSION['date']) . '</td>'; // la date choisie 
        } else {
            echo '<td>pas de choix de date</td>'; 
        }

        echo '<td>' . htmlspecialchars($_SESSION['PanierPrix'][$i]) . '€</td>';
        echo '<td>' . htmlspecialchars($_SESSION['PanierQte'][$i]) . '</td>';
        echo '<td>' . ($_SESSION['PanierQte'][$i] * $_SESSION['PanierPrix'][$i]) . '€</td></tr>';
        $total += ($_SESSION['PanierQte'][$i] * $_SESSION['PanierPrix'][$i]);

        $file = fopen($fileName, "a");
        $panier = $_SESSION['PanierNom'][$i];
        $prix = $_SESSION['PanierPrix'][$i];
        fwrite($file, $panier . " : " . ($prix * 0.8) . "€ sans TVA soit " . $prix . "€ avec TVA\n");
        fclose($file);
    }
    echo '<tr><td>Total</td><td colspan="6">' . $total . '€</td></tr></table>';
    $_SESSION['total'] = $total;

    echo '<form action="mail.php" method="POST"><input type="submit" name="valider" id="valider" value="Envoyez-moi la facture"></form>';
    echo '</div>';
} else {
    echo '<p>Veuillez vous connecter pour valider votre panier.</p>';
}

include 'footer.php'; 
?>
