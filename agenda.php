<?php
session_start();
include("connexion_bdd.php");

if(!isset($_SESSION['user_id'])){
    header("location:index.php");
    exit();
}

$mon_id = $_SESSION['user_id'];
$message_info = "";

// Ajouter une nouvelle date dans l'agenda
if(isset($_POST['btn_ajouter'])){
    $titre = mysqli_real_escape_string($con, $_POST['titre']);
    $date_ev = $_POST['date_ev'];
    $type_ev = mysqli_real_escape_string($con, $_POST['type_ev']);
    
    $req = mysqli_query($con, "INSERT INTO Agenda_Personnel (id_utilisateur, titre, date_evenement, type_evenement) VALUES ('$mon_id', '$titre', '$date_ev', '$type_ev')");
    
    if($req) {
        $message_info = "La date a bien été ajoutée à votre agenda !";
    }
}

// Supprimer une date 
if(isset($_GET['supprimer'])){
    $id_suppr = (int)$_GET['supprimer'];
    mysqli_query($con, "DELETE FROM Agenda_Personnel WHERE id_agenda = $id_suppr AND id_utilisateur = $mon_id");
    header("location:agenda.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Agenda | Old Dating</title>
    <style>
        body { margin: 0; padding: 0; font-family: 'Comic Sans MS', cursive; background-color: #273146; color: white; min-height: 100vh; }
        .top-bar { padding: 20px 40px; }
        .btn-retour { color: #E2BFC9; text-decoration: none; font-size: 1.2em; }
        .btn-retour:hover { text-decoration: underline; }
        
        .agenda-container { display: flex; justify-content: center; gap: 40px; padding: 20px; flex-wrap: wrap; max-width: 1200px; margin: 0 auto; }
        
        /* Colonne Formulaire (Gauche) */
        .form-side { background-color: #62769B; padding: 30px; border-radius: 15px; width: 350px; box-shadow: 0 4px 8px rgba(0,0,0,0.3); height: fit-content; }
        input, select { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: none; box-sizing: border-box; font-family: inherit; }
        button { width: 100%; padding: 10px; background-color: #5E7A53; color: white; border: none; border-radius: 5px; font-size: 1.1em; cursor: pointer; font-family: inherit; font-weight: bold; }
        button:hover { background-color: #769B62; }

        /* Colonne Liste (Droite) */
        .list-side { background-color: #8B9497; padding: 30px; border-radius: 15px; width: 500px; box-shadow: 0 4px 8px rgba(0,0,0,0.3); }
        .agenda-item { background-color: white; color: #111; padding: 15px; border-radius: 10px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; border-left: 8px solid #E2BFC9; }
        .agenda-item.Rendez-vous { border-left-color: #5E7A53; }
        .agenda-item.Anniversaire { border-left-color: #DDA2B5; }
        .btn-supprimer { background-color: #FF6B6B; color: white; text-decoration: none; padding: 5px 10px; border-radius: 5px; font-size: 0.9em; }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="accueil.php" class="btn-retour">Retour à l'accueil</a>
    </div>

    <h1 style="text-align: center; margin-top: 0;">Mon Agenda Personnel</h1>

    <?php if($message_info != "") echo "<p style='color: #A8E6A3; font-weight: bold; text-align:center; font-size: 1.2em;'>$message_info</p>"; ?>

    <div class="agenda-container">
        
        <!-- GAUCHE : Formulaire d'ajout -->
        <div class="form-side">
            <h2 style="margin-top: 0;">Ajouter une date</h2>
            <form action="" method="POST">
                <label>Titre</label>
                <input type="text" name="titre" required>

                <label>Type d'événement</label>
                <select name="type_ev">
                    <option value="Anniversaire">Anniversaire</option>
                    <option value="Rendez-vous">Rendez-vous médical</option>
                    <option value="Autre">Autre</option>
                </select>

                <label>Date</label>
                <input type="date" name="date_ev" required>

                <button type="submit" name="btn_ajouter">Enregistrer dans l'agenda</button>
            </form>
        </div>

        <!-- DROITE : Liste des événements -->
        <div class="list-side">
            <h2 style="margin-top: 0;">Mes prochaines dates</h2>
            
            <?php
            // On récupère les dates classées de la plus proche à la plus lointaine
            $req_agenda = mysqli_query($con, "SELECT * FROM Agenda_Personnel WHERE id_utilisateur = $mon_id ORDER BY date_evenement ASC");
            
            if(mysqli_num_rows($req_agenda) == 0){
                echo "<p style='text-align:center; color: #333; font-style: italic;'>Votre agenda est vide.</p>";
            } else {
                while($item = mysqli_fetch_assoc($req_agenda)) {
                    // Mettre la date au format français 
                    $date_fr = date("d/m/Y", strtotime($item['date_evenement']));
                    
                    echo "
                    <div class='agenda-item ".$item['type_evenement']."'>
                        <div>
                            <strong style='font-size:1.1em;'>".htmlspecialchars($item['titre'])."</strong><br>
                            <span style='color: #555;'> ".$date_fr." - ".$item['type_evenement']."</span>
                        </div>
                        <a href='agenda.php?supprimer=".$item['id_agenda']."' class='btn-supprimer'>X</a>
                    </div>";
                }
            }
            ?>
        </div>

    </div>

</body>
</html>