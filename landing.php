<?php
session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté, sinon le redirige vers la page de connexion
if (!isset($_SESSION['nom_utilisateur'])) {
    header("Location: connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Page d'accueil</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenue, <?php echo $_SESSION['nom_utilisateur']; ?> !</h1>
        <p>Vous êtes connecté en tant que <?php echo $_SESSION['nom_utilisateur']; ?>.</p>
        <p>Vous pouvez maintenant accéder aux fonctionnalités réservées aux utilisateurs connectés.</p>
        <a href="deconnexion.php">Déconnexion</a>
    </div>
</body>
</html>
