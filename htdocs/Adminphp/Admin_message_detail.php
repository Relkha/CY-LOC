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

// Récupérer le numéro de suivi
$tracking_number = $_GET['tracking_number'] ?? null;
$message_details = null;

// Rechercher le message correspondant
if ($tracking_number) {
    foreach ($messages as $message) {
        if (strpos($message[5], $tracking_number) !== false) {
            $message_details = $message;
            break;
        }
    }
}

if (!$message_details) {
    echo "Message non trouvé.";
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Message</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <header>
        <h1>Détails du Message</h1>
        <nav>
            <a href="admin_messages.php">Retour à la gestion des messages</a>
        </nav>
    </header>
    <main>
        <section>
            <h2>Numéro de suivi: <?php echo htmlspecialchars($tracking_number); ?></h2>
            <p><strong>Nom:</strong> <?php echo htmlspecialchars(trim(explode(':', $message_details[0])[1])); ?></p>
            <p><strong>Prénom:</strong> <?php echo htmlspecialchars(trim(explode(':', $message_details[1])[1])); ?></p>
            <p><strong>Téléphone:</strong> <?php echo htmlspecialchars(trim(explode(':', $message_details[2])[1])); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars(trim(explode(':', $message_details[3])[1])); ?></p>
            <p><strong>Objet:</strong> <?php echo htmlspecialchars(trim(explode(':', $message_details[4])[1])); ?></p>
            <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars(trim(explode(':', $message_details[6])[1]))); ?></p>
        </section>
    </main>
    <footer>
        <p>© 2024 Cy loc - Tous droits réservés</p>
    </footer>
</body>
</html>
