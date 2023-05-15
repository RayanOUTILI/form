<?php
session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté, sinon le redirige vers la page de connexion
if (!isset($_SESSION['nom_utilisateur'])) {
    header("Location: connexion.php");
    exit();
} else {
    // Vérifie le rôle de l'utilisateur pour déterminer la page à afficher
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin.php");
        exit();
    } else {
        header("Location: utilisateur.php");
        exit();
    }
}
?>
