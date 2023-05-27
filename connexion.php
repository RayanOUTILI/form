
<?php
require_once "config.php"; // Inclut le fichier de configuration de la base de données

// Initialise les variables d'erreur
$erreur = false;
$messageErreur = "";

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupère les données du formulaire
    $adr_mail = $_POST['adr_mail'];
    $mdp = $_POST['mdp'];

    try {
        // Vérifie les identifiants dans la base de données
        $stmt = $bdd->prepare("SELECT * FROM personne WHERE adr_mail = :adr_mail");
        $stmt->bindParam(":adr_mail", $adr_mail);
        $stmt->execute();
        $utilisateur = $stmt->fetch();

        if ($utilisateur && $mdp === $utilisateur['mdp']) {
            // Les identifiants sont corrects, démarre la session et enregistre l'adresse e-mail
            session_start();
            $_SESSION['adr_mail'] = $utilisateur['adr_mail'];

            // Redirige vers la page d'accueil
            header("Location: connecte.php");
            exit();
        } else {
            // Les identifiants sont incorrects
            $erreur = true;
            $messageErreur = "Adresse e-mail ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        // Erreur lors de l'exécution de la requête
        $erreur = true;
        $messageErreur = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Connexion</h1>

        <?php
        if ($erreur) {
            echo "<div class='erreur'>$messageErreur</div>";
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="email" name="adr_mail" placeholder="Adresse e-mail" required><br>
            <input type="password" name="mdp" placeholder="Mot de passe" required><br>
            <input type="submit" value="Se connecter">
        </form>
        <p><a class="button-link" href="index.php">Pas encore inscrit ? Inscrivez-vous</a></p>
    </div>
</body>
</html>
