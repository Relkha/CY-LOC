<?php
session_start();
include 'header.php';

$csvFile = 'data/reservations.csv';
$jsonFile = 'data/donnees.json';
$catalogueFile = 'data/catalogue.csv';

// Assurer la création du fichier CSV s'il n'existe pas
if (!file_exists($csvFile)) {
    $handle = fopen($csvFile, 'w');
    fputcsv($handle, ['Reservation ID', 'Ref', 'Date Depart', 'Date Retour', 'Nom', 'Prenom', 'Email', 'Insurance', 'Extra KM', 'Additional Notes', 'Total Price']);
    fclose($handle);
}

// Fonction pour charger les données de l'utilisateur connecté
function getUserData($file, $pseudo) {
    $json = file_get_contents($file);
    $users = json_decode($json, true);
    foreach ($users as $user) {
        if ($user['Pseudo'] === $pseudo) {
            return $user;
        }
    }
    return null;
}

// Fonction pour vérifier la disponibilité des dates
function checkAvailability($csvFile, $date_depart, $date_retour) {
    $handle = fopen($csvFile, 'r');
    while (($row = fgetcsv($handle)) !== FALSE) {
        if ($date_depart <= $row[3] && $date_retour >= $row[2]) {
            fclose($handle);
            return false; // Il y a un chevauchement
        }
    }
    fclose($handle);
    return true; // Aucun chevauchement trouvé
}

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

$userData = null;
if (isset($_SESSION['statut']) && $_SESSION['statut'] !== 0) {
    $userData = getUserData($jsonFile, $_SESSION['pseudo']);
}

$error = "";

// Gestion de la soumission du formulaire
if (isset($_POST['submit'])) {
    $nom = $userData ? $userData['Nom'] : $_POST['nom'];
    $prenom = $userData ? $userData['Prénom'] : $_POST['prenom'];
    $email = $userData ? $userData['Email'] : $_POST['email'];
    $date_depart = $_POST['date_depart'];
    $date_retour = $_POST['date_retour'];
    $ref = $_POST['ref'];
    $today = date('Y-m-d');

    // Calculer le nombre de jours
    $datetime1 = new DateTime($date_depart);
    $datetime2 = new DateTime($date_retour);
    $interval = $datetime1->diff($datetime2);
    $days = $interval->days;

    // Trouver le prix par jour dans le catalogue
    $prix_par_jour = 0;
    foreach ($catalogue as $item) {
        if ($item['Ref'] === $ref) {
            $prix_par_jour = $item['Prix'];
            break;
        }
    }

    // Calculer le prix total sans assurance
    $total_price = $prix_par_jour * $days;

    // Ajouter le prix de l'assurance
    $assurance_price = 0;
    if (isset($_POST['insurance'])) {
        switch ($_POST['insurance']) {
            case 'entry':
                $assurance_price = 5 * $days; // Exemple de prix pour l'assurance entrée de gamme
                break;
            case 'middle':
                $assurance_price = 10 * $days; // Exemple de prix pour l'assurance milieu de gamme
                break;
            case 'premium':
                $assurance_price = 20 * $days; // Exemple de prix pour l'assurance premium
                break;
        }
        $total_price += $assurance_price;
    }

    // Ajouter le prix des kilomètres supplémentaires
    $km_price = 0;
    if (isset($_POST['extra_km'])) {
        switch ($_POST['extra_km']) {
            case '100km':
                $km_price = 20; // Exemple de prix pour 100 km supplémentaires
                break;
            case '200km':
                $km_price = 35; // Exemple de prix pour 200 km supplémentaires
                break;
            case '300km':
                $km_price = 50; // Exemple de prix pour 300 km supplémentaires
                break;
        }
        $total_price += $km_price;
    }

    // Vérifier que la date de départ n'est pas antérieure à aujourd'hui
    if ($date_depart < $today) {
        $error = "La date de départ ne peut pas être antérieure à aujourd'hui.";
    }
    // Vérifier que la date de départ est avant la date de retour
    elseif ($date_depart >= $date_retour) {
        $error = "La date de départ doit être avant la date de retour.";
    }
    // Vérifier la disponibilité des dates
    elseif (!checkAvailability($csvFile, $date_depart, $date_retour)) {
        $error = "Désolé, les dates sélectionnées ne sont pas disponibles.";
    }
    else {
        $reservationData = [
            'reservation_id' => time(),
            'ref' => $ref,
            'date_depart' => $date_depart,
            'date_retour' => $date_retour,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'insurance' => $_POST['insurance'] ?? 'no',
            'extra_km' => $_POST['extra_km'] ?? 'no',
            'additional_notes' => $_POST['additional_notes'] ?? '',
            'total_price' => $total_price
        ];

        // Stocker les informations de réservation temporairement dans la session
        $_SESSION['reservations_temp'][] = $reservationData;

        echo "Réservation ajoutée avec succès. <a href='panier.php'>Payer maintenant</a>";
        exit;
    }
}
?>
<form method="post">
    <input type="hidden" name="ref" value="<?php echo htmlspecialchars($_POST['ref']); ?>">
    Date de départ: <input type="date" name="date_depart" required value="<?php echo $_POST['date_depart'] ?? ''; ?>"><br>
    Date de retour: <input type="date" name="date_retour" required value="<?php echo $_POST['date_retour'] ?? ''; ?>"><br>
    <?php if (!$userData): ?>
        Nom: <input type="text" name="nom" required value="<?php echo $_POST['nom'] ?? ''; ?>"><br>
        Prénom: <input type="text" name="prenom" required value="<?php echo $_POST['prenom'] ?? ''; ?>"><br>
        Email: <input type="email" name="email" required value="<?php echo $_POST['email'] ?? ''; ?>"><br>
    <?php else: ?>
        <p><strong>Nom:</strong> <?php echo htmlspecialchars($userData['Nom']); ?></p>
        <p><strong>Prénom:</strong> <?php echo htmlspecialchars($userData['Prénom']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['Email']); ?></p>
    <?php endif; ?>
    <h3>Choisissez une assurance :</h3>
    <label>
        <input type="radio" name="insurance" value="entry" <?php if (isset($_POST['insurance']) && $_POST['insurance'] == 'entry') echo 'checked'; ?>>
        Entrée de gamme (5€/jour) - Couverture de base
    </label><br>
    <label>
        <input type="radio" name="insurance" value="middle" <?php if (isset($_POST['insurance']) && $_POST['insurance'] == 'middle') echo 'checked'; ?>>
        Milieu de gamme (10€/jour) - Couverture étendue
    </label><br>
    <label>
        <input type="radio" name="insurance" value="premium" <?php if (isset($_POST['insurance']) && $_POST['insurance'] == 'premium') echo 'checked'; ?>>
        Premium (20€/jour) - Couverture complète
    </label><br>
    <h3>Choisissez des kilomètres supplémentaires (600 km inclus de base) :</h3>
    <label>
        <input type="radio" name="extra_km" value="100km" <?php if (isset($_POST['extra_km']) && $_POST['extra_km'] == '100km') echo 'checked'; ?>>
        100 km supplémentaires (20€)
    </label><br>
    <label>
        <input type="radio" name="extra_km" value="200km" <?php if (isset($_POST['extra_km']) && $_POST['extra_km'] == '200km') echo 'checked'; ?>>
         200 km supplémentaires (35€)
    </label><br>
    <label>
        <input type="radio" name="extra_km" value="300km" <?php if (isset($_POST['extra_km']) && $_POST['extra_km'] == '300km') echo 'checked'; ?>>
        300 km supplémentaires (50€)
    </label><br>
    <input type="submit" name="submit" value="Vérifier la disponibilité et réserver">
    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
</form>

<?php
include 'footer.php';
?>
