<?php
session_start();

@$nom = $_POST["nom"];
@$prenom = $_POST["prenom"];
@$phone = $_POST["phone"];
@$email = $_POST["email"];
@$object = $_POST["object"];
@$contenu = $_POST["contenu"];
@$valider = $_POST["valider"];
$message = '';

if (isset($valider)) {
    if (empty($nom)) {
        $message = '<div class="erreur">Nom laissé vide.</div>';
    } elseif (empty($prenom)) {
        $message = '<div class="erreur">Prénom laissé vide.</div>';
    } elseif (empty($phone)) {
        $message = '<div class="erreur">Téléphone laissé vide.</div>';
    } elseif (empty($email)) {
        $message = '<div class="erreur">Email laissé vide.</div>';
    } elseif (empty($object)) {
        $message = '<div class="erreur">Objet laissé vide.</div>';
    } elseif (empty($contenu)) {
        $message = '<div class="erreur">Contenu laissé vide.</div>';
    } else {
        // Générer un numéro de suivi aléatoire
        $tracking_number = uniqid('msg_');

        // Créer le message de l'email
        $email_message = "Nom: $nom\n";
        $email_message .= "Prénom: $prenom\n";
        $email_message .= "Téléphone: $phone\n";
        $email_message .= "Email: $email\n";
        $email_message .= "Objet: $object\n";
        $email_message .= "Message: $contenu\n";
        $email_message .= "Numéro de suivi: $tracking_number\n";

        // Envoyer l'email
        $to = "Superbadr@outlook.com"; // Remplacez par votre adresse email
        $subject = "Contact depuis le site";
        $headers = "From: $email";

        if (mail($to, $subject, $email_message, $headers)) {
            $message = '<div class="success">Votre message a été envoyé avec succès.</div>';

            // Stocker les informations dans un fichier texte
            $file = fopen('messages.txt', 'a');
            fwrite($file, $email_message . "\n-----------------------------\n");
            fclose($file);
        } else {
            $message = '<div class="erreur">Une erreur est survenue lors de l\'envoi de votre message.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="ISO-8859-1" />
    <title> Cy'LOC Contact </title>
    <link rel="stylesheet" href="./css/index.css">
    <script>
        function validateForm() {
            var name = document.forms["myForm"]["name"];
            var prenom = document.forms["myForm"]["prenom"];
            var phone = document.forms["myForm"]["phone"];
            var email = document.forms["myForm"]["email"];
            var object = document.forms["myForm"]["object"];
            var contenu = document.forms["myForm"]["contenu"];

            if (name.value == "") {
                document.getElementById('errorname').innerHTML = "Veuillez entrez un nom valide";
                name.focus();
                return false;
            }
            if (prenom.value == "") {
                document.getElementById('errorprenom').innerHTML = "Veuillez entrez un prenom valide";
                prenom.focus();
                return false;
            }
            if (phone.value == "") {
                document.getElementById('errorphone').innerHTML = "Veuillez entrez un numero de telephone valide";
                phone.focus();
                return false;
            }
            if (email.value == "") {
                document.getElementById('erroremail').innerHTML = "Veuillez entrez un email valide";
                email.focus();
                return false;
            }
            if (object.value == "") {
                document.getElementById('errorobject').innerHTML = "Veuillez entrez Un intitulé a votre message";
                object.focus();
                return false;
            }
            if (contenu.value == "") {
                document.getElementById('errorcontenu').innerHTML = "Veuillez entrez un Message";
                contenu.focus();
                return false;
            } else {
                document.getElementById('errorname').innerHTML = "";
                document.getElementById('errorprenom').innerHTML = "";
                document.getElementById('errorphone').innerHTML = "";
                document.getElementById('erroremail').innerHTML = "";
                document.getElementById('errorobject').innerHTML = "";
                document.getElementById('errorcontenu').innerHTML = "";
            }
        }
    </script>
</head>

<body bgcolor="fae9e3">
    <?php include 'header.php'; ?>

    <div id="Page">
        <h1>Contact</h1>
        <?php echo $message ?>
        <form name="myForm" method="post" action="" onsubmit="return validateForm()">
            <div class="champ">Date du jour</div>
            <script>
                date = new Date().toLocaleDateString();
                document.write(date);
            </script>

            <div class="label">Nom</div>
            <div class="champ">
                <input type="text" name="nom" pattern="^[A-Za-z '-]+$" maxlength="30" value="<?php echo $nom ?>" required />
                <span class="error" id="errorname"></span>
            </div>

            <div class="label">Prénom</div>
            <div class="champ">
                <input type="text" name="prenom" pattern="^[A-Za-z '-]+$" maxlength="30" value="<?php echo $prenom ?>" required />
                <span class="error" id="errorprenom"></span>
            </div>

            <div class="label">Numéro de téléphone</div>
            <div class="champ">
                <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" placeholder="0x-xx-xx-xx-xx" size="20" minlength="10" maxlength="10" required>
                <span class="error" id="errorphone"></span>
            </div>

            <div class="label">Email</div>
            <div class="champ">
                <input type="email" name="email" value="<?php echo $email ?>" required />
                <span class="error" id="erroremail"></span>
            </div>

            <div class="label">Objet</div>
            <div class="champ">
                <input type="text" id="object" name="object" placeholder="Demande information" maxlength="20" required>
                <span class="error" id="errorobject"></span>
            </div>

            <div class="label">Votre Message</div>
            <div class="champ">
                <input type="text" id="contenu" name="contenu" placeholder="Votre message" required>
                <span class="error" id="errorcontenu"></span>
            </div>

            <br><br>
            <div class="champ">
                <input type="submit" name="valider" value="Envoyer" />
            </div>
        </form>
        <br><br>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
