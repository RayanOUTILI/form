<!DOCTYPE html>
<html>
<head>
    <title>Paramètres</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Paramètres</h1>
        <?php
        session_start();
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['id'])) {
            header("Location: connexion.php");
            exit();
        }
        
        require_once "config.php";

        // Récupère les données de l'utilisateur connecté
        $id_utilisateur = $_SESSION['id'];

        $stmt = $bdd->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->bindParam(":id", $id_utilisateur);
        $stmt->execute();
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifie si le formulaire a été soumis
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Récupère les nouvelles données
            $nouveau_nom = $_POST['nom'];
            $nouveau_prenom = $_POST['prenom'];
            $nouvelle_adr_mail = $_POST['adr_mail'];

            // Met à jour les données de l'utilisateur dans la base de données
            $stmt = $bdd->prepare("UPDATE user SET nom = :nom, prenom = :prenom, adr_mail = :adr_mail WHERE id = :id");
            $stmt->bindParam(":nom", $nouveau_nom);
            $stmt->bindParam(":prenom", $nouveau_prenom);
            $stmt->bindParam(":adr_mail", $nouvelle_adr_mail);
            $stmt->bindParam(":id", $id_utilisateur);
            $stmt->execute();

            echo "Vos données ont été mises à jour avec succès.";
        }
        ?>
        <!-- Formulaire de modification des données -->
        <form action="" method="POST">
            <label>Nom:</label>
            <input type="text" name="nom" value="<?php echo $utilisateur['nom']; ?>" required>
            <label>Prénom:</label>
            <input type="text" name="prenom" value="<?php echo $utilisateur['prenom']; ?>" required>
            <label>Adresse e-mail:</label>
            <input type="email" name="adr_mail" value="<?php echo $utilisateur['adr_mail']; ?>" required>
            <input type="submit" value="Enregistrer">
        </form>

        <p><a href="deconnexion.php">Déconnexion</a></p>
    </div>
</body>
</html>
