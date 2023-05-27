<?php
require_once "config.php";


// Initialise la variable d'erreur
$erreur = false;
$messageErreur = "";

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupère les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $age = $_POST['age'];
    $adr_mail = $_POST['adr_mail'];
    $civilite = $_POST['civilite'];
    $mdp = $_POST['mdp'];
    $mdpConfirmation = $_POST['mdpConfirmation'];

    //on met en place un système de vérification (entrer deux fois le bon mot de passe lors de l'inscription)
    if ($mdp === $mdpConfirmation) {
        try {
            // Insère la nouvelle personne dans la base de données
            $stmt = $bdd->prepare("INSERT INTO personne (nom, prenom, age, adr_mail, civilite, mdp) VALUES (:nom, :prenom, :age, :adr_mail, :civilite, :mdp)");
            $stmt->bindParam(":adr_mail", $adr_mail);
            $stmt->bindParam(":nom", $nom);
            $stmt->bindParam(":prenom", $prenom);
            $stmt->bindParam(":age", $age);
            $stmt->bindParam(":civilite", $civilite);
            $stmt->bindParam(":mdp", $mdp);
            $stmt->execute();

            echo "L'utilisateur a été créé avec succès !";


            // Redirige vers la page de connexion
            header("Location: connexion.php");
            exit();
        } catch (PDOException $e) {
            // Erreur lors de l'exécution de la requête
            $erreur = true;
            $messageErreur = "Erreur : " . $e->getMessage();
        }
    }
    else {
        // Les mots de passe ne correspondent pas
        $erreur = true;
        $messageErreur = "Les mots de passe ne correspondent pas.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Inscription</h1>

        <?php
        if ($erreur) {
            echo "<div class='erreur'>$messageErreur</div>";
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" name="nom" placeholder="Nom" required><br>
            <input type="text" name="prenom" placeholder="Prénom" required><br>
            <input type="email" name="adr_mail" placeholder="Adresse e-mail" required><br>
            <select name="civilite">
            <option value="" disabled selected>Sélectionnez une civilité</option>
            <option value="M">M.</option>
            <option value="Mme">Mme</option>
            </select><br>
            <input type="number" name="age" placeholder="Âge" required></br>
            <input type="password" name="mdp" placeholder="Mot de passe" required><br>
            <input type="password" name="mdpConfirmation" placeholder="Confirmez le mot de passe" required><br>
            <input type="submit" value="S'inscrire">
        </form>
        <p><a class="button-link" href="connexion.php">Déjà inscrit ? Connectez-vous</a></p>
    </div>
</body>
</html>
