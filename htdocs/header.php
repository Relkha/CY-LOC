<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CY Loc : Location de voitures</title>
    <link rel="icon" href="./img/logo_size.jpg" type="image/x-icon">
    
    <link rel="stylesheet" href="css/index.css?v=1">
    <link rel="stylesheet" href="css/catalogue.css?v=1">
   
</head>

<body>
    <header>
        <img src="./img/logo.png" alt="logo" id="logo">
        <h1 id="titre">CY Loc</h1>
        <div id="boutonsD">
            <?php 
            session_start();
            if (!isset($_SESSION['statut'])) {
                $_SESSION['statut'] = 0; 
            }
            ?>
            <div class="dropdown">
                <button class="dropbtn"></button>
                <div class="dropdown-content">
                    <?php
                    if ($_SESSION['statut'] == 0) {
                        echo '<a href="form_connexion.php">Connexion</a>';
                    } else {
                        echo '<a href="deconnexion.php">Déconnexion</a>';
                        echo '<a href="profil.php">Mon profil</a>';
                        if ($_SESSION['role'] === 'admin') {
                            echo '<a href="Adminphp/Page_admin.php">Administration</a>';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="boutonRose">
                <a href="panier.php">Panier</a>
                <a href="panier.php">
                    <img src="./img/panier.png" alt="panier" id="panier">
                </a>
            </div>
            <form action="recherche.php" method="GET">
                <input type="text" name="query" placeholder="Recherche">
                <input type="submit" value="Rechercher">
            </form>
        </div>
    </header>

    <nav>
        <ul id="MenuHaut">
            <li><a href="index.php">Accueil</a></li>
            <?php
            $categories = simplexml_load_file("data/categories.xml");
            for ($n = 0; $n < count($categories); $n++) {
                if (strtolower($categories->categorie[$n]->nom) !== "spray") {
                    echo '<li><a href="catalogue.php?cat=', $n, '">', $categories->categorie[$n]->nom, '</a></li>';
                }
            }
            ?>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>


    
</body>
</html>
