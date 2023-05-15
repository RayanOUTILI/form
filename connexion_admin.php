<!DOCTYPE html>
<html>
<head>
    <title>Connexion Admin</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Connexion Admin</h1>
        <?php
        session_start();
        
        // Vérifie si l'administrateur est déjà connecté
        if (isset($_SESSION['admin'])) {
            header("Location: admin_dashboard.php");
            exit();
        }
        
        require_once "config.php";
        
        // Vérifie si le formulaire a été soumis
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom_utilisateur = $_POST['nom_utilisateur'];
            $mdp = $_POST['mdp'];
            
            // Vérifie les informations de connexion admin dans la base de données
            $stmt = $bdd->prepare("SELECT * FROM admin WHERE nom_utilisateur = :nom_utilisateur AND mdp = :mdp");
            $stmt->bindParam(":nom_utilisateur", $nom_utilisateur);
            $stmt->bindParam(":mdp", $mdp);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($admin) {
                // Connexion admin réussie, enregistre la variable de session admin
                $_SESSION['admin'] = true;
                
                // Redirige vers le tableau de bord admin
                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "Nom d'utilisateur ou mot de passe incorrect.";
            }
        }
        ?>
        <!-- Formulaire de connexion admin -->
        <form action="" method="POST">
            <input type="text" name="nom_utilisateur" placeholder="Nom d'utilisateur" required>
            <input type="password" name="mdp" placeholder="Mot de passe" required>
            <input type="submit" value="Se connecter en tant qu'Admin">
        </form>

        <p>Retour à la <a href="index.php">page d'accueil</a></p>
    </div>
</body>
</html>
