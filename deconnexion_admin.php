<?php
session_start();

// Détruit la session admin et redirige vers la page de connexion admin
unset($_SESSION['admin']);
session_destroy();

header("Location: connexion_admin.php");
exit();
?>
