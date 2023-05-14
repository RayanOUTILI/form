<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_utilisateur = htmlspecialchars($_POST['nom_utilisateur']);
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $adresse_mail = htmlspecialchars($_POST['adr_mail']);
    $mot_de_passe = htmlspecialchars($_POST['mdp']);

    $host = 'localhost';
    $user = 'root';
    $password = 'password';
    $database = 'DBgrp4J';
    $connexion = mysqli_connect($host, $user, $password, $database);

    // Vérification de la connexion
    if (mysqli_connect_errno()) {
        die("La connexion à la base de données a échoué : " . mysqli_connect_error());
    }

    // Insertion
    $stmt = $connexion->prepare("INSERT INTO user (nom_utilisateur, nom, prenom, adr_mail, mdp) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nom_utilisateur, $nom, $prenom, $adresse_mail, $mot_de_passe);

    // Exécution de la requête
    if ($stmt->execute() === TRUE) {
        echo "L'utilisateur a été créé avec succès !";
    } else {
        echo "Erreur lors de la création de l'utilisateur : " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Créer un utilisateur</title>
</head>
<body>
    <h1>Créer un utilisateur</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <label for="nom_utilisateur">Nom d'utilisateur:</label><br>
        <input type="text" id="nom_utilisateur" name="nom_utilisateur"><br>
        <label for="nom">Nom:</label><br>
        <input type="text" id="nom" name="nom"><br>
        <label for="prenom">Prénom:</label><br>
        <input type="text" id="prenom" name="prenom"><br>
        <label for="adresse_mail">Adresse e-mail:</label><br>
        <input type="email" id="adresse_mail" name="adresse_mail"><br>
        <label for="mot_de_passe">Mot de passe:</label><br>
        <input type="password" id="mot_de_passe" name="mot_de_passe"><br><br>
        <input type="submit" value="Créer l'utilisateur">
    </form>
</body>
</html>
