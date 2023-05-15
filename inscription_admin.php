<!DOCTYPE html>
<html>
<head>
    <title>Inscription Admin</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Inscription Admin</h1>
        <?php
        session_start();
        
        // Vérifie si l'utilisateur est déjà connecté en tant qu'admin
        if (isset($_SESSION['admin'])) {
            header("Location: admin_dashboard.php");
            exit();
        }
        
        require_once "config.php";
        
        // Vérifie si le formulaire a été soumis
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom_utilisateur = $_POST['nom_utilisateur'];
            $mdp = $_POST['mdp'];
            
            // Vérifie si l'utilisateur existe déjà en tant qu'admin
            $stmt = $bdd->prepare("SELECT * FROM admin WHERE nom_utilisateur = :nom_utilisateur");
            $stmt->bindParam(":nom_utilisateur", $nom_utilisateur);
            $stmt->execute();
            $admin_existe = ($stmt->rowCount() > 0);
            
            if ($admin_existe) {
                echo "Ce nom d'utilisateur est déjà utilisé. Veuillez en choisir un autre.";
            } else {
                // Insère le nouvel admin dans la base de données
                $stmt = $bdd->prepare("INSERT INTO admin (nom_utilisateur, mdp) VALUES (:nom_utilisateur, :mdp)");
                $stmt->bindParam(":nom_utilisateur", $nom_utilisateur);
                $stmt->bindParam(":mdp", $mdp);
                $stmt->execute();
                
                echo "Inscription réussie. Vous pouvez maintenant vous connecter en tant qu'admin.";
            }
        }
        ?>
        <!-- Formulaire d'inscription en tant qu'admin -->
        <form action="" method="POST">
            <input type="text" name="nom_utilisateur" placeholder="Nom d'utilisateur" required>
            <input type="password" name="mdp" placeholder="Mot de passe" required>
            <input type="submit" value="S'inscrire en tant qu'Admin">
        </form>

        <p>Retour à la <a href="index.php">page d'accueil</a></p>
    </div>
</body>
</html>
