<?php
session_start();
// On inclut le fichier pour se connecter à la BDD
include("connexion_bdd.php");

// Si l'utilisateur a cliqué sur le bouton "S'inscrire"
if(isset($_POST['btn_inscription'])){
    // On extrait les variables du formulaire
    extract($_POST);

    // On vérifie que tous les champs obligatoires sont remplis
    if(isset($nom) && isset($prenom) && isset($pseudo) && isset($email) && isset($mdp1) && isset($mdp2)){
        
        // On vérifie que les deux mots de passe correspondent
        if($mdp1 === $mdp2){
            
            // Sécurité : On hache le mot de passe avant de le stocker
            $mdp_hash = password_hash($mdp1, PASSWORD_DEFAULT);
            
            // On prépare la requête d'insertion
            $req = mysqli_query($con, "INSERT INTO Utilisateur (nom, prenom, pseudo, email, mot_de_passe) VALUES ('$nom', '$prenom', '$pseudo', '$email', '$mdp_hash')");
            
            if($req){
                // Si ça a marché, on redirige vers la page de chat (qu'on créera ensuite)
                header("location:accueil.php"); 
                exit();
            } else {
                $error = "Erreur lors de l'inscription (cet email est peut-être déjà utilisé).";
            }
        } else {
            $error = "Les mots de passe ne correspondent pas.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription | OLDating</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2 style="color: white; margin-left: 10%; padding-top: 20px;">Inscription</h2>
    
    <div class="container-inscription">
        <div class="form-side">
            <div style="text-align:center; margin-bottom: 20px;">
                <div style="width: 150px; height: 150px; background-color: rgba(255,255,255,0.5); border-radius: 50%; margin: 0 auto; display:flex; align-items:center; text-align:center; justify-content:center;">
                    Insérer une<br>photo de profil
                </div>
            </div>
            
            <?php if(isset($error)) { echo "<p style='color:red; font-weight:bold;'>$error</p>"; } ?>
            
            <form action="" method="POST">
                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label>Nom</label>
                        <input type="text" name="nom" required style="width: 100%; box-sizing: border-box;">
                    </div>
                    <div style="flex: 1;">
                        <label>Prénom</label>
                        <input type="text" name="prenom" required style="width: 100%; box-sizing: border-box;">
                    </div>
                </div>
                
                <label>Nom d'utilisateur</label>
                <input type="text" name="pseudo" required>
                
                <label>Email</label>
                <input type="email" name="email" required>
                
                <label>Mot de Passe</label>
                <input type="password" name="mdp1" required>

                <label>Confirmation Mot de Passe</label>
                <input type="password" name="mdp2" required>
                
                <input type="submit" name="btn_inscription" value="S'inscrire" style="background-color: var(--muted-green); color: white; cursor: pointer; margin-top: 15px;">
            </form>
        </div>
        
        <div class="illustration-side">
            <img src="oldpeople.png" alt="Image Seniors" style="max-height: 80%; max-width: 80%;">
        </div>
    </div>
</body>
</html>