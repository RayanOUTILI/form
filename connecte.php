<?php
session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['adr_mail'])) {
    // Redirige vers la page de connexion s'il n'est pas connecté
    header("Location: index.php");
    exit();
}

require_once "config.php"; // Inclut le fichier de configuration de la base de données

// Initialise les variables d'erreur
$erreur = false;
$messageErreur = "";

// Vérifie si le formulaire de recherche précise a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['recherche_precise'])) {
    // Récupère les données du formulaire de recherche précise
    $recherche = $_POST['recherche_precise'];

    try {
        // Requête de recherche précise des utilisateurs
        //on utilise LIKE au lieu de = pour que la recherche soit meilleure (marge erreur)
        $stmt = $bdd->prepare("SELECT * FROM personne WHERE nom LIKE :recherche OR prenom LIKE :recherche OR adr_mail LIKE :recherche");
        $stmt->bindValue(":recherche", $recherche);
        $stmt->execute();
        $utilisateurs = $stmt->fetchAll();
    } catch (PDOException $e) {
        // Erreur lors de l'exécution de la requête
        $erreur = true;
        $messageErreur = "Erreur : " . $e->getMessage();
    }
}

// Vérifie si le formulaire d'affichage de toutes les personnes a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['afficher_tous'])) {
    try {
        // Requête pour récupérer tous les utilisateurs
        $stmt = $bdd->query("SELECT * FROM personne");
        $utilisateurs = $stmt->fetchAll();
    } catch (PDOException $e) {
        // Erreur lors de l'exécution de la requête
        $erreur = true;
        $messageErreur = "Erreur : " . $e->getMessage();
    }
}

// Vérifie si le formulaire de modification a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['modifier'])) {
    // Récupère les données du formulaire de modification
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $nouvelAdrMail = $_POST['nouvel_adr_mail'];

    try {
        // Met à jour les informations de l'utilisateur connecté
        $stmt = $bdd->prepare("UPDATE personne SET nom = :nom, prenom = :prenom, adr_mail = :nouvel_adr_mail WHERE adr_mail = :adr_mail");
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":prenom", $prenom);
        $stmt->bindParam(":nouvel_adr_mail", $nouvelAdrMail);
        $stmt->bindParam(":adr_mail", $_SESSION['adr_mail']);
        $stmt->execute();
        $messageErreur = "Informations mises à jour avec succès.";
    } catch (PDOException $e) {
        // Erreur lors de l'exécution de la requête
        $erreur = true;
        $messageErreur = "Erreur : " . $e->getMessage();
    }
}

// Vérifie si le formulaire de suppression a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['supprimer'])) {
    try {
        // Supprime l'utilisateur connecté de la base de données
        $stmt = $bdd->prepare("DELETE FROM personne WHERE adr_mail = :adr_mail");
        $stmt->bindParam(":adr_mail", $_SESSION['adr_mail']);
        $stmt->execute();

        // Détruit la session et redirige vers la page de connexion
        session_destroy();
        header("Location: index.php");
        exit();
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
    <title>Espace connecté</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Espace connecté</h1>

        <?php
        if ($erreur) {
            echo "<div class='erreur'>$messageErreur</div>";
        }
        ?>

        <h2>Bienvenue <?php echo $_SESSION['adr_mail']; ?></h2>

        <h3>Rechercher d'autres utilisateurs</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" name="recherche_precise" placeholder="Recherche précise (nom, prénom ou adresse e-mail)" required>
            <input type="submit" value="Rechercher">
        </form>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="submit" name="afficher_tous" value="Afficher toutes les personnes">
        </form>

        <?php if (isset($utilisateurs)): ?>
            <h3>Résultats de la recherche</h3>
            <?php if (count($utilisateurs) > 0): ?>
                <ul>
                    <?php foreach ($utilisateurs as $utilisateur): ?>
                        <li><?php echo $utilisateur['nom'] . " " . $utilisateur['prenom'] . " (" . $utilisateur['adr_mail'] . ")"; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucun utilisateur trouvé.</p>
            <?php endif; ?>
        <?php endif; ?>

        <h3>Modifier vos informations</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" name="nom" placeholder="Nouveau nom" required><br>
            <input type="text" name="prenom" placeholder="Nouveau prénom" required><br>
            <input type="email" name="nouvel_adr_mail" placeholder="Nouvelle adresse e-mail" required><br>
            <input type="submit" name="modifier" value="Modifier">
        </form>

        <h3>Supprimer votre compte</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">
            <input type="submit" name="supprimer" value="Supprimer">
        </form>

        <p><a class="button-link" href="deconnexion.php">Déconnexion</a></p>
    </div>
</body>
</html>
