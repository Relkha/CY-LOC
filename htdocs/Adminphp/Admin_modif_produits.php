<?php
session_start();
// Vérifier si l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: connexion.php');
    exit;
}

$csvFilePath = "../data/catalogue.csv";

// Chargement des données produit
function loadProducts($filePath) {
    $products = [];
    if (file_exists($filePath)) {
        $file = fopen($filePath, "r");
        while (($data = fgetcsv($file, 1000, ",")) !== false) {
            $products[] = $data;
        }
        fclose($file);
    }
    return $products;
}

function getProductIndex($products, $ref) {
    foreach ($products as $index => $product) {
        if ($product[4] == $ref) { // La référence est à l'index 4
            return $index;
        }
    }
    return -1;
}

$products = loadProducts($csvFilePath);
$productIndex = getProductIndex($products, $_GET['ref']);
$product = $products[$productIndex] ?? null;

if (!$product) {
    echo "Produit non trouvé.";
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ref'])) {
    if (isset($_FILES['newImage']['name']) && $_FILES['newImage']['error'] == 0) {
        $uploadPath = "../img/";
        $newImageName = basename($_FILES['newImage']['name']);
        $newImagePath = $uploadPath . $newImageName;
        if (move_uploaded_file($_FILES['newImage']['tmp_name'], $newImagePath)) {
            $product[6] = $newImageName; // Mettre à jour avec le nouveau nom de l'image
        }
    } else {
        $product[6] = $_POST['currentImage']; // Conserver l'image actuelle
    }

    // Mise à jour des informations du produit
    $products[$productIndex] = [
        $_POST['nom'],
        $_POST['categorie'],
        $_POST['description'],
        $_POST['prix'],
        $_POST['ref'],
        $_POST['stock'],
        $product[6]
    ];

    $file = fopen($csvFilePath, 'w');
    foreach ($products as $prod) {
        fputcsv($file, $prod);
    }
    fclose($file);

    header('Location: Admin_gestion_produits.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Produit</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>Modifier Produit</h1>
    <button onclick="history.back()">Retour</button>
    <form method="post" enctype="multipart/form-data">
        Nom: <input type="text" name="nom" value="<?php echo htmlspecialchars($product[0]); ?>" required><br>
        Catégorie: <input type="text" name="categorie" value="<?php echo htmlspecialchars($product[1]); ?>" required><br>
        Description: <input type="text" name="description" value="<?php echo htmlspecialchars($product[2]); ?>" required><br>
        Prix: <input type="text" name="prix" value="<?php echo htmlspecialchars($product[3]); ?>" required><br>
        Référence: <input type="text" name="ref" value="<?php echo htmlspecialchars($product[4]); ?>" required><br>
        Stock: <input type="number" name="stock" value="<?php echo htmlspecialchars($product[5]); ?>" required><br>
        Image Actuelle: <img src="../img/<?php echo htmlspecialchars($product[6]); ?>" alt="Product Image" style="width:100px;"><br>
        Remplacer l'image: <input type="file" name="newImage"><br>
        <input type="hidden" name="currentImage" value="<?php echo htmlspecialchars($product[6]); ?>">
        <input type="submit" value="Enregistrer les modifications">
    </form>
</body>
</html>
