<?php
session_start();
include("connexion_bdd.php");

$message_action = "";
$erreur_login = "";

// 1. Connexion de l'Administrateur
if(isset($_POST['btn_login_admin'])){
    if($_POST['password_admin'] === "bogoss"){
        $_SESSION['admin_ok'] = true;
    } else {
        $erreur_login = "Mot de passe incorrect";
    }
}

// Déconnexion Admin
if(isset($_GET['logout'])){
    unset($_SESSION['admin_ok']);
    header("location:admin.php");
    exit();
}

// Vérification si l'admin est connecté pour afficher la suite
if(isset($_SESSION['admin_ok']) && $_SESSION['admin_ok'] === true) {
    
    // 2. Création d'un compte senior sur demande 
    if(isset($_POST['btn_creer_compte'])){
        extract($_POST);
        // Hachage du mot de passe pour la sécurité
        $mdp_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        
        $req_creation = mysqli_query($con, "INSERT INTO Utilisateur (nom, prenom, pseudo, email, mot_de_passe) VALUES ('$nom', '$prenom', '$pseudo', '$email', '$mdp_hache')");
        if($req_creation) {
            $message_action = "Le compte de $pseudo a été créé avec succès !";
        } else {
            $message_action = "Erreur lors de la création du compte.";
        }
    }

    // 3. Suppression d'un compte 
    if(isset($_GET['supprimer'])){
        $id_a_supprimer = (int)$_GET['supprimer'];
        mysqli_query($con, "DELETE FROM Utilisateur WHERE id_utilisateur = $id_a_supprimer");
        $message_action = "Le compte a été supprimé définitivement.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration | Old Dating</title>
    <style>
        body { margin: 0; padding: 0; font-family: 'Comic Sans MS', cursive; background-color: #273146; color: white; min-height: 100vh; }
        .header-admin { background-color: #FF4C4C; padding: 20px; text-align: center; font-size: 1.5em; font-weight: bold; border-bottom: 5px solid #8B0000; }
        
        /* Style du Login Admin */
        .login-box { background-color: #62769B; width: 350px; margin: 100px auto; padding: 30px; border-radius: 15px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.3); }
        .login-box input { width: 100%; padding: 10px; margin: 15px 0; border-radius: 5px; border: none; box-sizing: border-box; }
        .login-box button { width: 100%; padding: 10px; background-color: #FF4C4C; color: white; border: none; border-radius: 5px; font-size: 1.1em; cursor: pointer; font-weight: bold; }
        
        /* Style du Dashboard Admin */
        .dashboard { display: flex; justify-content: center; gap: 40px; padding: 40px; flex-wrap: wrap; }
        .form-side { background-color: #62769B; padding: 30px; border-radius: 15px; width: 350px; height: fit-content; }
        .form-side input { width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; border: none; box-sizing: border-box; }
        .form-side button { width: 100%; padding: 10px; background-color: #5E7A53; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
        
        .list-side { background-color: #8B9497; padding: 30px; border-radius: 15px; width: 600px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background-color: white; color: black; border-radius: 10px; overflow: hidden; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #FF4C4C; color: white; }
        .btn-supprimer { background-color: #FF4C4C; color: white; text-decoration: none; padding: 5px 10px; border-radius: 5px; font-size: 0.9em; font-weight: bold; }
        .btn-supprimer:hover { background-color: #8B0000; }
    </style>
</head>
<body>

    <div class="header-admin">
        Panneau d'Administration Old Dating
        <?php if(isset($_SESSION['admin_ok'])) { echo "<br><a href='admin.php?logout=1' style='color:white; font-size:0.6em; text-decoration:none;'>Se déconnecter</a>"; } ?>
    </div>

    <?php if(!isset($_SESSION['admin_ok'])): ?>
        <!-- ECRAN DE CONNEXION ADMIN -->
        <div class="login-box">
            <h2>Accès Restreint</h2>
            <?php if($erreur_login != "") echo "<p style='color: #FFC0C0;'>$erreur_login</p>"; ?>
            <form action="" method="POST">
                <input type="password" name="password_admin" placeholder="Mot de passe Administrateur" required>
                <button type="submit" name="btn_login_admin">Entrer</button>
            </form>
            <p style="margin-top:20px;"><a href="index.php" style="color: #E2BFC9;">Retour au site public</a></p>
        </div>

    <?php else: ?>
        <!-- ECRAN DU DASHBOARD ADMIN -->
        <?php if($message_action != "") echo "<p style='text-align:center; font-size:1.2em; color:#A8E6A3; font-weight:bold; margin-top:20px;'>$message_action</p>"; ?>
        
        <div class="dashboard">
            
            <!-- Créer un compte -->
            <div class="form-side">
                <h3 style="margin-top:0;">Inscrire un Senior</h3>
                <form action="" method="POST">
                    <input type="text" name="nom" placeholder="Nom" required>
                    <input type="text" name="prenom" placeholder="Prénom" required>
                    <input type="text" name="pseudo" placeholder="Pseudo" required>
                    <input type="email" name="email" placeholder="Adresse Email" required>
                    <input type="text" name="mot_de_passe" placeholder="Mot de passe provisoire" required>
                    <button type="submit" name="btn_creer_compte">Créer le compte</button>
                </form>
            </div>

            <!-- Gérer et Supprimer les comptes -->
            <div class="list-side">
                <h3 style="margin-top:0;">Liste des Adhérents</h3>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Pseudo</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $req_users = mysqli_query($con, "SELECT * FROM Utilisateur ORDER BY id_utilisateur DESC");
                    while($user = mysqli_fetch_assoc($req_users)){
                        echo "<tr>
                                <td>".$user['id_utilisateur']."</td>
                                <td>".htmlspecialchars($user['pseudo'])."</td>
                                <td>".htmlspecialchars($user['email'])."</td>
                                <td><a href='admin.php?supprimer=".$user['id_utilisateur']."' class='btn-supprimer' onclick='return confirm(\"Supprimer définitivement ce compte ?\");'>Supprimer</a></td>
                              </tr>";
                    }
                    ?>
                </table>
            </div>
            
        </div>
    <?php endif; ?>

</body>
</html>