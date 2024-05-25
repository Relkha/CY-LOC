<!DOCTYPE html>
<html>
    <head>
        <title>Page de traitement</title>
        <link rel="stylesheet" href="css/index.css">
        <meta http-equiv="refresh" content="15; URL=index.php">
    </head>


    <body bgcolor="fae9e3">
        <div id="Page">
            <h3 id="titre"> Votre message a bien été envoyé. </h3>
            <p>L'équipe Cy Loc reviendra vers vous dans un délais maximum d'une semaine. </p> 
            <p>Recapitulatif du mail envoyé</p>
    
            <?php
                echo 'Nom : '.$_POST["nom"].'<br>';
                echo 'Prenom : '.$_POST["prenom"].'<br>';
                echo 'Email : ' .$_POST["email"].'<br>';
                echo 'Numero de telephone : ' .$_POST["phone"].'<br>';
                echo 'Object : ' .$_POST["object"].'<br>';
                echo 'Contenu : ' .$_POST["contenu"].'<br>';
            ?>

        <a href="index.php">Retour vers la page d'accueil</a>
        </div>
       

    </body>
</html>