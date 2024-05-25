<?php
session_start();
include 'header.php';

$catalogueFile = 'data/catalogue.csv';

// Fonction pour charger les données du catalogue
function loadCatalogue($filePath) {
    $catalogue = [];
    if (!file_exists($filePath)) {
        return $catalogue; // Retourne un tableau vide si le fichier n'existe pas
    }
    $file = fopen($filePath, "r");
    $headers = fgetcsv($file); // Lire la ligne d'en-tête
    while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
        $catalogue[] = array_combine($headers, $data);
    }
    fclose($file);
    return $catalogue;
}

// Charger le catalogue
$catalogue = loadCatalogue($catalogueFile);

// Fonction pour supprimer un article du panier
function supprimerArticle($index) {
    unset($_SESSION['PanierRef'][$index]);
    unset($_SESSION['PanierQte'][$index]);
    unset($_SESSION['PanierImg'][$index]);
    unset($_SESSION['PanierNom'][$index]);
    unset($_SESSION['PanierPrix'][$index]);
    unset($_SESSION['reservations_temp'][$index]);

    // Réindexer les tableaux pour enlever les trous
    $_SESSION['PanierRef'] = array_values($_SESSION['PanierRef']);
    $_SESSION['PanierQte'] = array_values($_SESSION['PanierQte']);
    $_SESSION['PanierImg'] = array_values($_SESSION['PanierImg']);
    $_SESSION['PanierNom'] = array_values($_SESSION['PanierNom']);
    $_SESSION['PanierPrix'] = array_values($_SESSION['PanierPrix']);
    $_SESSION['reservations_temp'] = array_values($_SESSION['reservations_temp']);
}

// Gestion de la suppression d'un article
if (isset($_POST['supprimer'])) {
    supprimerArticle($_POST['index']);
}

$total = 0;
?>

<div id="Page">
    <h3>Votre Panier</h3>
    <?php
    if (!isset($_SESSION['PanierRef']) || count($_SESSION['PanierRef']) == 0) {
        echo "<p>Votre panier est vide</p>";
        echo '<a href="catalogue.php?cat=0">Retour vers le catalogue</a>';
    } else {
        echo '<table border="1">
            <thead>
                <tr>
                    <td>Nom</td>
                    <td>Photo</td>
                    <td>Ref</td>
                    <td>Réservation</td>
                    <td>Prix</td>
                    <td>Quantité</td>
                    <td>Total Article</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>';
        foreach ($_SESSION['PanierNom'] as $i => $nom) {
            $ref = htmlspecialchars($_SESSION['PanierRef'][$i]);
            $prix_par_jour = 0;
            foreach ($catalogue as $item) {
                if ($item['Ref'] === $ref) {
                    $prix_par_jour = $item['Prix'];
                    break;
                }
            }

            $total_price = 0;
            foreach ($_SESSION['reservations_temp'] as $reservation) {
                if ($reservation['Ref'] === $ref && $reservation['Nom'] === $_SESSION['nom']) {
                    $total_price = $reservation['Total Price'];
                    break;
                }
            }

            echo '<tr>
                    <td>' . htmlspecialchars($nom) . '</td>
                    <td><img class="photoPanier" src="./img/' . htmlspecialchars($_SESSION['PanierImg'][$i]) . '"></td>
                    <td>' . $ref . '</td>
                    <td>';
            echo '<form action="verifyAvailability.php" method="post">
                <input type="hidden" name="ref" value="' . $ref . '">
                <input type="hidden" name="index" value="' . $i . '">
                Date de départ: <input type="date" name="date_depart" required><br>
                Date de retour: <input type="date" name="date_retour" required><br>
                <input type="submit" value="Vérifier la disponibilité">
                </form>
                </td>
                <td>' . $prix_par_jour . '€</td>
                <td><form action="" method="post">
                    <input type="number" name="new' . $i . '" min="0" value="' . htmlspecialchars($_SESSION['PanierQte'][$i]) . '">
                    <input type="submit" name="changer' . $i . '" value="Changer la quantité">
                    </form></td>
                <td>' . $total_price . '€</td>
                <td><form action="" method="post">
                    <input type="hidden" name="index" value="' . $i . '">
                    <input type="submit" name="supprimer" value="Supprimer">
                    </form></td>
            </tr>';
            $total += $total_price;
        }
        echo '<tr>
                <td>Total</td>
                <td colspan="7">' . $total . '€</td>
              </tr>
            </tbody>
        </table>
        <form action="finalizeOrder.php" method="POST">
            <input type="submit" name="finalize" value="Finaliser la commande">
        </form>
        <form action="" method="POST">
            <input type="submit" name="reset" value="Vider le panier">
        </form>';

        if (isset($_POST['reset'])) {
            $_SESSION['PanierRef'] = [];
            $_SESSION['PanierQte'] = [];
            $_SESSION['PanierImg'] = [];
            $_SESSION['PanierNom'] = [];
            $_SESSION['PanierPrix'] = [];
            $_SESSION['reservations_temp'] = [];

            echo "<script>window.location.href = 'panier.php';</script>";
        }
    }
    ?>

</div>

<?php include 'footer.php'; ?>
