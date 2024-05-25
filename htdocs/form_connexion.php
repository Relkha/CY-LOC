<?php
include 'header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
</head>
<body>
    <div id="Page">
        <!-- Le formulaire de connexion poste les données à 'action.php', où le traitement de la connexion est réalisé -->
        <form method="post" action="connexion.php">
            Nom d'utilisateur : <input type="text" id="pseudo" name="pseudo" placeholder="Nom d'utilisateur" maxlength="20" required><br>
            Mot de passe : <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" maxlength="20" required><br>
            <input type="submit" id="submit" name="submit" value="Connexion"><br><br>
        </form>
        <!-- Lien pour s'inscrire si l'utilisateur n'a pas de compte -->
        <form id="inscription" action="inscription.php" method="get">
            <input type="submit" value="Inscription">
        </form>
    </div>
    <?php
    include 'footer.php';
    ?>
</body>
</html>

