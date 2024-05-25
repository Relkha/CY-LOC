<?php
session_start();

// Vérifiez si l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: connexion.php');
    exit;
}

// Chemin du fichier contenant les messages
$messageFilePath = 'messages.txt';

// Fonction pour charger les messages
function loadMessages($filePath) {
    $messages = [];
    if (!file_exists($filePath)) {
        return $messages; // Retourne un tableau vide si le fichier n'existe pas
    }
    $file = fopen($filePath, "r");
    $message = [];
    while (($line = fgets($file)) !== false) {
        if (trim($line) == '-----------------------------') {
            $messages[] = $message;
            $message = [];
        } else {
            $message[] = $line;
        }
    }
    fclose($file);
    return $messages;
}

// Charger les messages
$messages = loadMessages($messageFilePath);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Messages</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <header>
        <h1>Gestion des Messages</h1>
        <nav>
            <a href="Page_admin.php">Retour au tableau de bord</a>
        </nav>
    </header>
    <main>
        <section>
            <table>
                <thead>
                    <tr>
                        <th>Numéro de Suivi</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Objet</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message) { 
                        // Extraire les informations du message
                        $tracking_number = trim(explode(':', $message[5])[1]);
                        $nom = trim(explode(':', $message[0])[1]);
                        $prenom = trim(explode(':', $message[1])[1]);
                        $email = trim(explode(':', $message[3])[1]);
                        $object = trim(explode(':', $message[4])[1]);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tracking_number); ?></td>
                        <td><?php echo htmlspecialchars($nom); ?></td>
                        <td><?php echo htmlspecialchars($prenom); ?></td>
                        <td><?php echo htmlspecialchars($email); ?></td>
                        <td><?php echo htmlspecialchars($object); ?></td>
                        <td>
                            <a href="admin_message_detail.php?tracking_number=<?php echo urlencode($tracking_number); ?>">Voir Détails</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <p>© 2024 Cy loc - Tous droits réservés</p>
    </footer>
</body>
</html>
