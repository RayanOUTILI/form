<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <?php
        session_start();
        
        // Vérifie si l'administrateur est connecté
        if (!isset($_SESSION['admin'])) {
            header("Location: connexion_admin.php");
            exit();
        }
        
        require_once "config.php";
        
        // Récupère la liste des utilisateurs
        $stmt = $bdd->prepare("SELECT * FROM user");
        $stmt->execute();
        $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Récupère la liste des admins
        $stmt = $bdd->prepare("SELECT * FROM admin");
        $stmt->execute();
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <h2>Liste des Utilisateurs</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Adresse Mail</th>
            </tr>
            <?php foreach ($utilisateurs as $utilisateur): ?>
            <tr>
                <td><?php echo $utilisateur['id']; ?></td>
                <td><?php echo $utilisateur['nom_utilisateur']; ?></td>
                <td><?php echo $utilisateur['nom']; ?></td>
                <td><?php echo $utilisateur['prenom']; ?></td>
                <td><?php echo $utilisateur['adr_mail']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <h2>Liste des Admins</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Adresse Mail</th>
            </tr>
            <?php foreach ($admins as $admin): ?>
            <tr>
                <td><?php echo $admin['id']; ?></td>
                <td><?php echo $admin['nom_utilisateur']; ?></td>
                <td><?php echo $admin['nom']; ?></td>
                <td><?php echo $admin['prenom']; ?></td>
                <td><?php echo $admin['adr_mail']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <form action="deconnexion_admin.php" method="POST">
            <input type="submit" value="Se déconnecter">
        </form>
    </div>
</body>
</html>
