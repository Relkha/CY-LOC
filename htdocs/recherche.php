<?php
session_start();

// Vérifiez si une requête de recherche a été soumise
if (!isset($_GET['query']) || empty($_GET['query'])) {
    header('Location: index.php');
    exit;
}

$query = htmlspecialchars($_GET['query']);
$csvFilePath = "data/catalogue.csv"; // Chemin vers le fichier CSV contenant les données de recherche

// Fonction pour charger les produits depuis le fichier CSV
function loadProducts($filePath) {
    $products = [];
    if (!file_exists($filePath)) {
        return $products; // Retourne un tableau vide si le fichier n'existe pas
    }
    $file = fopen($filePath, "r");
    $headers = fgetcsv($file); // Lire la ligne d'en-tête
    while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
        $products[] = array_combine($headers, $data);
    }
    fclose($file);
    return $products;
}

// Chargement des produits
$products = loadProducts($csvFilePath);

// Fonction pour filtrer les produits en fonction de la requête de recherche
function searchProducts($products, $query) {
    $results = [];
    foreach ($products as $product) {
        if (stripos($product['Nom'], $query) !== false || stripos($product['Description'], $query) !== false) {
            $results[] = $product;
        }
    }
    return $results;
}

// Filtrer les produits en fonction de la requête de recherche
$results = searchProducts($products, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats de recherche</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <header>
        <h1>Résultats de recherche pour "<?php echo $query; ?>"</h1>
    </header>
    <main>
        <?php if (count($results) > 0) { ?>
            <ul>
                <?php foreach ($results as $product) { ?>
                    <li>
                        <h3><?php echo htmlspecialchars($product['Nom']); ?></h3>
                        <p><?php echo htmlspecialchars($product['Description']); ?></p>
                        <p>Prix: <?php echo htmlspecialchars($product['Prix']); ?>€</p>
                        <p>Stock: <?php echo htmlspecialchars($product['Stock']); ?></p>
                        <img src="img/<?php echo htmlspecialchars($product['Image']); ?>" alt="<?php echo htmlspecialchars($product['Nom']); ?>" style="width:100px;">
                        <form method="post" action="recupProduit.php">
                            <input type="hidden" name="nom" value="<?php echo htmlspecialchars($product['Nom']); ?>">
                            <input type="hidden" name="ref" value="<?php echo htmlspecialchars($product['Ref']); ?>">
                            <input type="hidden" name="prix" value="<?php echo htmlspecialchars($product['Prix']); ?>">
                            <input type="hidden" name="img" value="<?php echo htmlspecialchars($product['Image']); ?>">
                            Quantité: <input type="number" name="qte" min="1" value="1">
                            <input type="submit" value="Ajouter au panier">
                        </form>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>Aucun résultat trouvé pour "<?php echo $query; ?>".</p>
        <?php } ?>
    </main>
    <footer>
        <p>© 2024 Cy loc - Tous droits réservés</p>
    </footer>
</body>
</html>
