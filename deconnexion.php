<?php
session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté
if (isset($_SESSION['adr_mail'])) {
    // Détruit toutes les variables de session
    $_SESSION = array();

    // Détruit la session
    session_destroy();
}

// Redirige vers la page de connexion
header("Location: connexion.php");
exit();
?>
