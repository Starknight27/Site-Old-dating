<?php
session_start();
include("connexion_bdd.php");

// Redirection si non connecté
if(!isset($_SESSION['user_id'])){
    header("location:index.php");
    exit();
}

if(isset($_POST['btn_ajouter'])){
    extract($_POST);
    
    // On combine la date et l'heure pour correspondre au format DATETIME de MySQL
    $datetime_event = $date_ev . ' ' . $heure_ev . ':00';
    
    // On évite les bugs avec les apostrophes dans le texte
    $titre = mysqli_real_escape_string($con, $titre);
    $lieu = mysqli_real_escape_string($con, $lieu);
    
    $req = mysqli_query($con, "INSERT INTO Evenement (titre, date_evenement, lieu, type_evenement) VALUES ('$titre', '$datetime_event', '$lieu', '$type_ev')");
    
    if($req){
        $message_succes = "L'événement a bien été ajouté au calendrier !";
    } else {
        $message_erreur = "Erreur lors de l'ajout de l'événement.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un événement | Old Dating</title>
    <style>
        body { margin: 0; font-family: 'Comic Sans MS', cursive; background-color: #273146; color: white; display: flex; flex-direction: column; align-items: center; padding-top: 50px; }
        .form-container { background-color: #62769B; padding: 30px; border-radius: 15px; width: 400px; box-shadow: 0 4px 8px rgba(0,0,0,0.3); }
        input, select { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: none; box-sizing: border-box; font-family: inherit; }
        button { width: 100%; padding: 10px; background-color: #5E7A53; color: white; border: none; border-radius: 5px; font-size: 1.2em; cursor: pointer; font-family: inherit; }
        button:hover { background-color: #769B62; }
    </style>
</head>
<body>

    <a href="evenements.php" style="color: #E2BFC9; text-decoration: none; margin-bottom: 20px; font-size: 1.2em;">Retour au calendrier</a>

    <div class="form-container">
        <h2 style="text-align: center; margin-top: 0;">Planifier une sortie</h2>
        
        <?php if(isset($message_succes)) echo "<p style='color: #A8E6A3; font-weight: bold; text-align:center;'>$message_succes</p>"; ?>
        <?php if(isset($message_erreur)) echo "<p style='color: #FFA6A6; font-weight: bold; text-align:center;'>$message_erreur</p>"; ?>

        <form action="" method="POST">
            <label>Titre de l'événement</label>
            <input type="text" name="titre" required placeholder="Ex: Tournoi de Belote">

            <label>Type d'activité</label>
            <select name="type_ev">
                <option value="Jeux">Jeux de cartes / Société</option>
                <option value="Musique">Chants et Musique</option>
                <option value="Danse">Danse</option>
                <option value="Promenade">Promenade / Sortie extérieure</option>
            </select>

            <label>Date</label>
            <input type="date" name="date_ev" required>

            <label>Heure</label>
            <input type="time" name="heure_ev" required>

            <label>Lieu</label>
            <input type="text" name="lieu" placeholder="Ex: Salle des fêtes">

            <button type="submit" name="btn_ajouter">Ajouter au calendrier</button>
        </form>
    </div>

</body>
</html>