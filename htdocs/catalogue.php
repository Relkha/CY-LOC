<?php 
	session_start();
?>

<?php include 'header.php'; ?>
<head>
    <link rel="stylesheet" href="css/index.css">
    <meta charset="utf8">
</head>

<div id="Page">

	<?php // pour automatiser l'affichage des catégories dans le menu
			$C=$_GET['cat']; // recupération de la catégorie pour pouvoir afficher le bon titre
			$cat =(int)$C;
			// affiche le lien vers la page categorie n 
			echo '<h4 id="titre" class="cat">',$categories->categorie[$cat]->nom, "</h4>";
		?>


	<table border="1">
		<thead>
			<tr>
				<td>Nom </td>
				<td>Photo</td>
				<td>Ref</td>
				<td>Descritpion</td>
				<td>Prix</td>
				<td>Achat </td>
			</tr>
		</thead>
		<tbody>
			<?php
					$C=$_GET['cat']; // recupération de la catégorie pour pouvoir afficher les bons articles
					$cat =(int)$C;  
					if (($handle = fopen("./data/catalogue.csv", "r")) !== FALSE):
						$i =0;
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE):
							if($i > 0){
								if($data[1] == $cat){
									if ($data[5] != 0){ // pour ne pas afficher les produits dont le stock est nul 
										?>
			<form method="post" action="recupProduit.php">
				<input type="hidden" name="cat" value="<?= $cat; ?>">
				<tr>
					<td id="produit">
						<?= $data[0]; ?><input type="hidden" name="nom" value="<?= $data[0]; ?>">
					</td>
					<td>
						<div class="zoom">
							<p>
								<img class="catalogue" src="./img/<?= $data[6]; ?>">
								<input type="hidden" name="img" value="<?= $data[6]; ?>">
							</p>
						</div>
					</td>
					<td>
						<?= $data[4]; ?>
						<input type="hidden" name="ref" value="<?= $data[4]; ?>">
					</td>
					<td><?= $data[2]; ?></td>
					<td><?= $data[3]; ?> €
						<input type="hidden" name="prix" value="<?= $data[3]; ?>">
					</td>
					<td>
						<input type="number" pattern="[0-9]" name="qte" id="qte"',' min="0" max="<?= $data[5]; ?>"><br>
						<input type="submit" name="ajouter" id="ajouter" onclick="MAJstock(<?= $data[4]; ?>);" value="Ajouter au panier">  <?php // nous ne sommes parvenus a faire la gestion du stock en ajax ?>
					</td>
				</tr>
			</form>
			<?php
								if ($_SESSION['statut'] == 5){ // pour que seul l'admin voit 
									echo '<button type="button" id="stock',$i,'" onclick="toggle_text(',$i,');">Stock</button></td>';
									// echo $i;
									echo '</td><td><input type="button" style="display:none;" id="st',$i,'"value="',$data[5],'"><br> ';
									echo '<script>
									function toggle_text(i) {
										// alert (i);
										var span = document.getElementById("stock"+i);
										var col = document.getElementById("st"+i);
										if(col.style.display == "none") {
											col.style.display = "inline";
										} else {
											col.style.display = "none";
										}
									}
									</script>';
						
								if (isset($_POST['stock'])){
									// echo "<script> document.getElementById('qte",$i,"').value </script>";
									// echo "ici";
									// echo $data[5];
									// $data[5] = $data[5] - echo "<script> document.getElementById('qte",$i,"').value; </script>";
								}
								echo "</td></tr>"; 
							}
						}
					}
					$i++;
				} else {
					$i++;
				}  
			endwhile;
			fclose($handle);
		endif; 
		?>
		</tbody>
	</table>



	<p> <i> Le choix des dates pour les prestations et locations se fera dans le panier. </i></p>

</div>
<script src="./js/stock.js"></script>

<?php include 'footer.php'; ?>
</body>

</html>