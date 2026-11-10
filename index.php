<?php
// On démarre la session pour mémoriser l'utilisateur connecté
session_start();
include("connexion_bdd.php");

// Si le bouton "Se connecter" est cliqué
if(isset($_POST['btn_connexion'])){
    extract($_POST);
    
    // On vérifie que les champs ne sont pas vides
    if(isset($email) && isset($mdp) && $email != "" && $mdp != ""){
        
        // 1. On cherche si l'email existe dans la base de données
        $req = mysqli_query($con, "SELECT * FROM Utilisateur WHERE email = '$email'");
        
        if(mysqli_num_rows($req) > 0){
            // 2. L'email existe, on récupère les informations de l'utilisateur
            $user = mysqli_fetch_assoc($req);
            
            // 3. On vérifie si le mot de passe tapé correspond au mot de passe crypté en base
            if(password_verify($mdp, $user['mot_de_passe'])){
                
                // C'est un succès ! On stocke ses infos en mémoire (Session)
                $_SESSION['user_id'] = $user['id_utilisateur'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_pseudo'] = $user['pseudo'];
                
                // On redirige vers la page de l'acceuil
                header("location:accueil.php");
                exit();
                
            } else {
                $error = "Mot de passe incorrect !";
            }
        } else {
            $error = "Aucun compte trouvé avec cet email !";
        }
    } else {
        $error = "Veuillez remplir tous les champs !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion | Old Dating</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2 style="color: white; margin-left: 10%; padding-top: 20px;">Connexion</h2>
    
    <div class="container-inscription">
        <div class="form-side">
            <div style="text-align:center; margin-bottom: 20px;">
                <h1 style="color: white; font-family: 'Comic Sans MS', cursive;">Old Dating</h1>
                <p style="color: #D3D3D3;">Bienvenue !</p>
            </div>
            
            <?php if(isset($error)) { echo "<p style='color:red; font-weight:bold; background-color:white; padding:5px; border-radius:5px;'>$error</p>"; } ?>
            
            <form action="" method="POST">
                <label style="color: white;">Email</label>
                <input type="email" name="email" required>
                
                <label style="color: white;">Mot de Passe</label>
                <input type="password" name="mdp" required>
                
                <input type="submit" name="btn_connexion" value="Se connecter" style="background-color: var(--muted-green); color: white; cursor: pointer; margin-top: 15px; font-weight: bold;">
                
                <p style="color: white; text-align: center; margin-top: 15px;">
                    Vous n'avez pas de compte ? <a href="inscription.php" style="color: #E2BFC9;">S'inscrire</a>
                </p>
            </form>
        </div>
        
        <div class="illustration-side">
            <img src="oldpeople.png" alt="Image Seniors" style="max-height: 80%; max-width: 80%;">
        </div>
    </div>
</body>
</html>