<?php
session_start();
include("connexion_bdd.php");

if(!isset($_SESSION['user_id'])){
    header("location:index.php");
    exit();
}

$mon_id = $_SESSION['user_id'];
$sms_alerte = ""; // Variable les faux SMS

// --- NOUVEAU : Traitement de l'inscription à un événement ---
if(isset($_GET['participer'])) {
    $id_ev = (int)$_GET['participer'];
    
    // On vérifie si l'utilisateur n'est pas déjà inscrit
    $check = mysqli_query($con, "SELECT * FROM inscription_evenement WHERE id_utilisateur = $mon_id AND id_evenement = $id_ev");
    if(mysqli_num_rows($check) == 0) {
        mysqli_query($con, "INSERT INTO inscription_evenement (id_utilisateur, id_evenement) VALUES ($mon_id, $id_ev)");
        // Le texte du SMS de confirmation !
        $sms_alerte = "Inscription confirmée !";
    } else {
        $sms_alerte = "Vous participez déjà à cet événement !";
    }
}

// Gestion des dates du calendrier
$m = isset($_GET['m']) ? (int)$_GET['m'] : (int)date('n');
$y = isset($_GET['y']) ? (int)$_GET['y'] : (int)date('Y');
$prev_m = $m - 1; $prev_y = $y; if($prev_m < 1) { $prev_m = 12; $prev_y--; }
$next_m = $m + 1; $next_y = $y; if($next_m > 12) { $next_m = 1; $next_y++; }
$mois_fr = ["", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
$nom_mois = $mois_fr[$m];
$premier_jour_timestamp = mktime(0, 0, 0, $m, 1, $y);
$jours_dans_mois = date('t', $premier_jour_timestamp);
$jour_semaine = date('N', $premier_jour_timestamp); 

// Récupération des événements
$mois_format = sprintf("%02d", $m);
$req_events = mysqli_query($con, "SELECT * FROM Evenement WHERE MONTH(date_evenement) = '$mois_format' AND YEAR(date_evenement) = '$y' ORDER BY date_evenement ASC");
$evenements_du_mois = [];
while($ev = mysqli_fetch_assoc($req_events)){
    $jour_ev = (int)date('d', strtotime($ev['date_evenement']));
    $evenements_du_mois[$jour_ev][] = $ev;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calendrier | Old Dating</title>
    <style>
        body, html { margin: 0; padding: 0; font-family: 'Comic Sans MS', cursive; background-color: #273146; color: white; min-height: 100vh; }
        .top-bar { padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; }
        .btn-retour { color: #E2BFC9; text-decoration: none; font-size: 1.2em; }
        .btn-ajouter { background-color: #5E7A53; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        
        .calendar-container { width: 90%; max-width: 1000px; margin: 0 auto 20px auto; background-color: white; padding: 10px; }
        .calendar-header { display: flex; justify-content: space-between; align-items: center; background-color: #62769B; color: black; font-size: 1.8em; padding: 10px 20px; }
        .calendar-arrow { color: #DDA2B5; font-size: 1.2em; font-weight: bold; text-decoration: none; }
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; margin-top: 5px; }
        .day-header { background-color: #5E7A53; color: white; text-align: center; padding: 10px 0; font-size: 1.1em; }
        .day-box { background-color: #D9D9D9; min-height: 100px; padding: 5px; display: flex; flex-direction: column; gap: 5px; }
        .day-box.empty { background-color: transparent; }
        .date-number { font-weight: bold; color: #333; text-align: right; margin-bottom: 5px; }
        
        .event-card { background-color: #62769B; color: white; padding: 5px; border-radius: 5px; font-size: 0.85em; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        .event-heure { color: #DDA2B5; font-weight: bold; }
        .btn-participer { display: inline-block; margin-top: 5px; background-color: #5E7A53; color: white; text-decoration: none; padding: 3px 8px; border-radius: 10px; font-size: 0.9em; }
        
        /* Style du SMS */
        .sms-popup { background-color: #A8E6A3; color: #111; padding: 15px; text-align: center; font-weight: bold; font-size: 1.2em; border-bottom: 3px solid #5E7A53; }
    </style>
</head>
<body>

    <?php if($sms_alerte != "") { echo "<div class='sms-popup'>$sms_alerte</div>"; } ?>

    <div class="top-bar">
        <a href="accueil.php" class="btn-retour">Retour à l'accueil</a>
        <a href="ajouter_evenement.php" class="btn-ajouter">Ajouter un événement</a>
    </div>

    <div class="calendar-container">
        <div class="calendar-header">
            <a href="evenements.php?m=<?= $prev_m ?>&y=<?= $prev_y ?>" class="calendar-arrow">◁</a>
            <span><?= $nom_mois . " " . $y ?></span>
            <a href="evenements.php?m=<?= $next_m ?>&y=<?= $next_y ?>" class="calendar-arrow">▷</a>
        </div>

        <div class="calendar-grid">
            <div class="day-header">Lundi</div><div class="day-header">Mardi</div><div class="day-header">Mercredi</div><div class="day-header">Jeudi</div><div class="day-header">Vendredi</div><div class="day-header">Samedi</div><div class="day-header">Dimanche</div>
            <?php for($i = 1; $i < $jour_semaine; $i++): ?><div class="day-box empty"></div><?php endfor; ?>
            <?php for($jour = 1; $jour <= $jours_dans_mois; $jour++): ?>
                <div class="day-box">
                    <div class="date-number"><?= $jour ?></div>
                    <?php
                    if(isset($evenements_du_mois[$jour])){
                        foreach($evenements_du_mois[$jour] as $evt){
                            $heure = date('H:i', strtotime($evt['date_evenement']));
                            echo "
                            <div class='event-card'>
                                <strong>".htmlspecialchars($evt['titre'])."</strong><br>
                                <span class='event-heure'>".$heure."</span><br>
                                <a href='evenements.php?m=$m&y=$y&participer=".$evt['id_evenement']."' class='btn-participer'>Je participe</a>
                            </div>";
                        }
                    }
                    ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>