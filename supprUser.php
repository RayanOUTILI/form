<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adresse_mail = $_POST['adr_mail'];
    $mot_de_passe = $_POST['mdp'];

    $host = 'localhost';
    $user = 'root';
    $password = 'password';
    $database = 'DBgrp4J';
    $connexion = mysqli_connect($host, $user, $password, $database);

    // Vérification de la connexion
    if (mysqli_connect_errno()) {
        die("La connexion à la base de données a échoué : " . mysqli_connect_error());
    }

    // Vérification de l'adresse e-mail et du mot de passe de l'utilisateur
    $stmt = $connexion->prepare("SELECT id_utilisateur, mdp FROM user WHERE adr_mail = ?");
    $stmt->bind_param("s", $adresse_mail);
    $stmt->execute();
    $stmt->bind_result($id_utilisateur, $mot_de_passe_bd);
    $stmt->fetch();
    $stmt->close();

    if ($mot_de_passe_bd && password_verify($mot_de_passe, $mot_de_passe_bd)) {
        // Mot de passe correct, suppression de l'utilisateur
        $stmt = $connexion->prepare("DELETE FROM user WHERE id_utilisateur = ?");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $stmt->close();

        mysqli_close($connexion);

        header("Location: afficherUser.php");
        exit;
    } else {
        echo "Adresse e-mail ou mot de passe incorrect. L'utilisateur n'a pas été supprimé.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Supprimer un utilisateur</title>
</head>
<body>
    <h1>Supprimer un utilisateur</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <label for="adr_mail">Adresse e-mail:</label><br>
        <input type="email" id="adr_mail" name="adr_mail"><br>
        <label for="mdp">Mot de passe:</label><br>
        <input type="password" id="mdp" name="mdp"><br><br>
        <input type="submit" value="Supprimer l'utilisateur">
    </form>
</body>
</html>
