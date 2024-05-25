<?php
session_start();
include 'header.php';
?>

<?php

// un tableaux de produits avec ses champs est mieux que un tableau de chaque champ
if (! isset($_SESSION['PanierRef'])) {
    $_SESSION['PanierRef'] = array();
    $_SESSION['PanierQte'] = array();
    $_SESSION['PanierImg'] = array();
    $_SESSION['PanierNom'] = array();
    $_SESSION['PanierPrix'] = array();
    $_SESSION['PanierCat'] = array();
}

// Verifier chaque POST avant d'affecter et faire l'inclusion directe
$qte = $_POST['qte'];
$ref = $_POST['ref'];
$img = $_POST['img'];
$prix = $_POST['prix'];                                                                                                                          
$nom = $_POST['nom'];
$cat = $_POST['cat'];
@$valider=$_POST["valider"];

 
if(isset($valider)){ 
    if(empty($qte)) {
       $message='<div class="erreur">Le produit n a pas pu etre ajouter au panier </div>';
    }
    if(empty($ref)) {
       $message='<div class="erreur">Le produit n a pas pu etre ajouter au panier</div>';
    }
    if(empty($img)){
       $message='<div class="Le produit n a pas pu etre ajouter au panier</div>';
    }
    if(empty($prix)){
       $message='<div class="erreur">Le produit n a pas pu etre ajouter au panier.</div>';
    }
    if(empty($nom)) {
       $message='<div class="erreur">Le produit n a pas pu etre ajouter au panier</div>';
    }
   if(empty($cat)) {
       $message='<div class="erreur">Le produit n a pas pu etre ajouter au panier</div>';  
   }    
    else{
       
       $message.='</div>';
    }
 }
 

array_push($_SESSION['PanierRef'], $ref);
array_push($_SESSION['PanierQte'], $qte);
array_push($_SESSION['PanierPrix'], $prix);
array_push($_SESSION['PanierImg'], $img);
array_push($_SESSION['PanierNom'], $nom);
array_push($_SESSION['PanierCat'], $cat);

echo $nom . " a bien été ajouté à votre panier !";
echo '<a href="panier.php"> Voir mon panier <a>';
echo '<br> <a href="catalogue.php?cat=0"> Poursuivre mes achats <a>';


include 'footer.php';
?>

