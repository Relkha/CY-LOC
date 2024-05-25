<?php
session_start();

$csvFile = 'data/reservations.csv';

// Charger les réservations temporaires de la session
if (isset($_SESSION['reservations_temp']) && !empty($_SESSION['reservations_temp'])) {
    $handle = fopen($csvFile, 'a');
    foreach ($_SESSION['reservations_temp'] as $reservation) {
        fputcsv($handle, $reservation);
    }
    fclose($handle);

    // Générer la facture
    foreach ($_SESSION['reservations_temp'] as $reservation) {
        $reservationId = $reservation['reservation_id'];
        $factureFile = 'factures/facture_' . $reservationId . '.txt';
        
        // Utiliser la fonction fopen avec le mode b pour spécifier l'encodage UTF-8
        $factureHandle = fopen($factureFile, 'wb');
        fwrite($factureHandle, "\xEF\xBB\xBF"); // Ajouter le BOM UTF-8

        fwrite($factureHandle, "Facture\n\n");
        fwrite($factureHandle, "Réservation ID: " . $reservationId . "\n");
        fwrite($factureHandle, "Nom: " . $reservation['nom'] . "\n");
        fwrite($factureHandle, "Prénom: " . $reservation['prenom'] . "\n");
        fwrite($factureHandle, "Email: " . $reservation['email'] . "\n");
        fwrite($factureHandle, "Référence: " . $reservation['ref'] . "\n");
        fwrite($factureHandle, "Date de départ: " . $reservation['date_depart'] . "\n");
        fwrite($factureHandle, "Date de retour: " . $reservation['date_retour'] . "\n");
        fwrite($factureHandle, "Assurance: " . $reservation['insurance'] . "\n");
        fwrite($factureHandle, "KM Supplémentaires: " . $reservation['extra_km'] . "\n");
        fwrite($factureHandle, "Notes Additionnelles: " . $reservation['additional_notes'] . "\n");
        fwrite($factureHandle, "Prix Total: " . $reservation['total_price'] . "€\n");
        fclose($factureHandle);
    }

    // Envoyer la facture par email
    $to = $reservation['email'];
    $subject = "Votre facture de réservation";
    $message = "Merci pour votre réservation. Vous trouverez votre facture en pièce jointe.";
    $headers = "From: no-reply@cyloc.com";

    // Ajouter la pièce jointe
    $file = $factureFile;
    $content = file_get_contents($file);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $filename = basename($file);

    $headers .= "\r\nMIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $headers .= "This is a multi-part message in MIME format.\r\n";
    $headers .= "--".$uid."\r\n";
    $headers .= "Content-type:text/plain; charset=UTF-8\r\n";
    $headers .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $headers .= $message."\r\n\r\n";
    $headers .= "--".$uid."\r\n";
    $headers .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n";
    $headers .= "Content-Transfer-Encoding: base64\r\n";
    $headers .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $headers .= $content."\r\n\r\n";
    $headers .= "--".$uid."--";

    mail($to, $subject, "", $headers);

    // Vider les réservations temporaires
    unset($_SESSION['reservations_temp']);
    echo "Commande finalisée avec succès. Vos réservations ont été enregistrées et la facture a été envoyée par email.";
} else {
    echo "Aucune réservation à finaliser.";
}

echo '<a href="index.php">Retour à l\'accueil</a>';
?>
