<!DOCTYPE html>
<html>
<head>
<style>
    table {
      width: 60%;
      border-collapse: collapse;
      margin: auto;
      margin-top: 50px;
    }

    th, td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>
<?php
// Établir la connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = 'password';
$database = 'DBgrp4J';

$connexion = mysqli_connect($host, $user, $password, $database);

// Vérifier la connexion
if (mysqli_connect_errno()) {
    echo "Échec de la connexion à la base de données : " . mysqli_connect_error();
    exit();
}

// Exécuter une requête pour récupérer les données de la base de données
$query = "SELECT * FROM user";
$resultat = mysqli_query($connexion, $query);

// Vérifier si la requête a réussi
if (!$resultat) {
    echo "Erreur lors de l'exécution de la requête : " . mysqli_error($connexion);
    exit();
}

// Afficher les données dans un tableau HTML
echo "<h1>TEST PHP</h1><p>Liste des utilisateurs dans la DB</p>";
echo "<table><tr><th>Nom d'utilisateur</th><th>Nom</th><th>Prénom</th><th>Adresse mail</th></tr>";

while ($row = mysqli_fetch_assoc($resultat)) {
    echo "<tr>";
    echo "<td>" . $row['nom_utilisateur'] . "</td>";
    echo "<td>" . $row['nom'] . "</td>";
    echo "<td>" . $row['prenom'] . "</td>";
    echo "<td>" . $row['adr_mail'] . "</td>";

    echo "</tr>";
} 
echo "</table>";

// Fermer la connexion à la base de données
mysqli_close($connexion);
?>

</body>
</html>
