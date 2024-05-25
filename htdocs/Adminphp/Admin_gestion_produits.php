<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: connexion.php');
    exit;
}

$csvFilePath = "../data/catalogue.csv";

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

$products = loadProducts($csvFilePath);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ref'])) {
    // Gestion du téléchargement de l'image
    $imagePath = 'default.jpg'; // Image par défaut si rien n'est téléchargé
    if (isset($_FILES['image']['error']) && $_FILES['image']['error'] == 0) {
        $uploadDir = '../img/';
        $imagePath = $uploadDir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $imagePath = $_FILES['image']['name']; // Nom de l'image pour le CSV
        }
    }

    $newProduct = [
        $_POST['nom'],
        $_POST['categorie'],
        $_POST['description'],
        $_POST['prix'],
        $_POST['ref'],
        $_POST['stock'],
        $imagePath
    ];

    // Recherche si le produit existe déjà
    $updated = false;
    foreach ($products as $index => $product) {
        if ($product[4] === $_POST['ref']) {
            $products[$index] = $newProduct;
            $updated = true;
            break;
        }
    }

    if (!$updated) {
        $products[] = $newProduct;
    }

    // Sauvegarde des données dans le fichier CSV
    $file = fopen($csvFilePath, 'w');
    foreach ($products as $product) {
        fputcsv($file, $product);
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
    <title>Gestion des Produits</title>
    <link rel="stylesheet" href="../css/admin.css">
    <li><button onclick="history.back()">Retour</button></li>
</head>
<body>
    <header>
        <h1>Gestion des Produits</h1>
    </header>
    <main>
        <section>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Réf</th>
                        <th>Stock</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product[0]); ?></td>
                        <td><?php echo htmlspecialchars($product[1]); ?></td>
                        <td><?php echo htmlspecialchars($product[2]); ?></td>
                        <td><?php echo htmlspecialchars($product[3]); ?></td>
                        <td><?php echo htmlspecialchars($product[4]); ?></td>
                        <td><?php echo htmlspecialchars($product[5]); ?></td>
                        <td><img src="../img/<?php echo htmlspecialchars($product[6]); ?>" alt="Product Image" style="width:100px;"></td>
                        <td>
                            <a href="Admin_modif_produits.php?ref=<?php echo urlencode($product[4]); ?>">Modifier</a> |
                            <a href="Admin_suppr_produits.php?ref=<?php echo urlencode($product[4]); ?>">Supprimer</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
        <section>
            <h2>Ajouter / Modifier un produit</h2>
            <form method="post" enctype="multipart/form-data">
                Nom: <input type="text" name="nom" required><br>
                Catégorie: <input type="text" name="categorie" required><br>
                Description: <input type="text" name="description" required><br>
                Prix: <input type="text" name="prix" required><br>
                Référence: <input type="text" name="ref" required><br>
                Stock: <input type="number" name="stock" required><br>
                Image: <input type="file" name="image"><br>
                <input type="submit" value="Sauvegarder">
            </form>
        </section>
    </main>
    <footer>
        <p>© 2024 Cy loc - Tous droits réservés</p>
    </footer>
</body>
</html>

