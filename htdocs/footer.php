<footer>
    <br> <br>
   
        <p>
            © Cy loc - SIRET : 98738498293 Adresse : 2 Avenue du Parc 95000 Cergy . Téléphone : 06.14.57.06.77  <br> Hébergeur : CY Tech - 2 avenue du Parc 95000 Cergy. Téléphone : 07.67.83.06.11 
        </p>

        <nav>
			<ul >

                <a  href="#top"> Retourner en haut</a> 
				<li ><a href="index.php"> Accueil </a> </li>
				<?php // pour automatiser l'affichage des catégories dans le menu
                      
					$categories=simplexml_load_file("./data/categories.xml"); 
					for($n=0; $n<count($categories); $n++){
						// affiche le lien vers la page categorie n 
						echo '<a href="catalogue.php?cat=',$n,'"> ',$categories->categorie[$n]->nom,'</a></li> <br>';
					}
                ?>
				<li ><a href="./contact.php"> Contact </a> </li>

			</ul>
		</nav>
</footer>