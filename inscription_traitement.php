<?php
require_once "config.php"; 

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupère les données du formulaire
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adr_mail = $_POST['adr_mail'];
    $mdp = $_POST['mdp'];

    try {
        // Vérifie si le nom d'utilisateur est déjà utilisé
        $stmt = $bdd->prepare("SELECT * FROM user WHERE nom_utilisateur = :nom_utilisateur");
        $stmt->bindParam(":nom_utilisateur", $nom_utilisateur);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Le nom d'utilisateur est déjà utilisé
            echo "Le nom d'utilisateur est déjà utilisé. Veuillez en choisir un autre.";
        } else {
            // Le nom d'utilisateur est disponible, insère le nouvel utilisateur dans la base de données
            $hash_mdp = password_hash($mdp, PASSWORD_DEFAULT);

            $stmt = $bdd->prepare("INSERT INTO user (nom_utilisateur, nom, prenom, adr_mail, mdp) VALUES (:nom_utilisateur, :nom, :prenom, :adr_mail, :mdp)");
            $stmt->bindParam(":nom_utilisateur", $nom_utilisateur);
            $stmt->bindParam(":nom", $nom);
            $stmt->bindParam(":prenom", $prenom);
            $stmt->bindParam(":adr_mail", $adr_mail);
            $stmt->bindParam(":mdp", $hash_mdp);
            $stmt->execute();

            echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        }
    } catch (PDOException $e) {
        // Erreur lors de l'exécution de la requête
        echo "Erreur : " . $e->getMessage();
    }
}
?>
