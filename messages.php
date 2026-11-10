<?php
session_start();
include("connexion_bdd.php");

// Montre les erreur SQL
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if(!isset($_SESSION['user_id']) || !isset($_GET['contact'])) {
    echo "<div style='color:white; text-align:center;'>Veuillez vous connecter.</div>";
    exit(); 
}

$mon_id = (int)$_SESSION['user_id'];
$contact_id = (int)$_GET['contact'];

try {
    $req = mysqli_query($con, "
        SELECT * FROM messages 
        WHERE (id_utilisateur = $mon_id AND id_destinataire = $contact_id) 
           OR (id_utilisateur = $contact_id AND id_destinataire = $mon_id) 
        ORDER BY date_envoi ASC
    ");

    if(mysqli_num_rows($req) == 0){
        echo "<div style='text-align:center; color:white; margin-top:50px; font-size:1.2em;'>Aucun message. Dites bonjour !</div>";
    } else {
        while($row = mysqli_fetch_assoc($req)) {
            if($row['id_utilisateur'] == $mon_id) {
                echo "<div style='display:flex; justify-content:flex-end; margin-bottom:15px;'><div style='background-color:#769B62; color:white; padding:12px 18px; border-radius:20px 20px 0 20px; max-width:70%; font-size:1.1em;'><p style='margin:0;'>".htmlspecialchars($row['contenu_message'])."</p></div></div>";
            } else {
                echo "<div style='display:flex; justify-content:flex-start; margin-bottom:15px;'><div style='background-color:#8B9497; color:white; padding:12px 18px; border-radius:20px 20px 20px 0; max-width:70%; font-size:1.1em;'><p style='margin:0;'>".htmlspecialchars($row['contenu_message'])."</p></div></div>";
            }
        }
    }
} catch (Exception $e) {
    // Si la base de données ne trouve pas les colonnes, l'erreur s'affichera ici en rouge !
    echo "<div style='background-color: white; color: red; padding: 20px; border-radius: 10px; font-weight: bold;'>ERREUR SQL DETECTÉE : " . $e->getMessage() . "</div>";
}
?>