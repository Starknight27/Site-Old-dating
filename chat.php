<?php
session_start();
include("connexion_bdd.php");

// Permet d'afficher les erreurs SQL sur la page 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if(!isset($_SESSION['user_id'])){
    header("location:index.php");
    exit();
}

$mon_id = $_SESSION['user_id'];
$contact_id = isset($_GET['contact']) ? (int)$_GET['contact'] : 0;

$erreur_sql = "";

if(isset($_POST['send']) && $contact_id > 0){
    $message = mysqli_real_escape_string($con, $_POST['message']);
    if($message != ""){
        try {
            // Insertion dans la table messages 
            mysqli_query($con, "INSERT INTO messages (id_utilisateur, id_destinataire, contenu_message) VALUES ('$mon_id', '$contact_id', '$message')");
        } catch (Exception $e) {
            $erreur_sql = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Message | Old Dating</title>
    <style>
        body, html { margin: 0; padding: 0; height: 100%; font-family: 'Comic Sans MS', 'Chalkboard SE', sans-serif; overflow: hidden; }
        .container-chat { display: flex; width: 100vw; height: 100vh; }
        
        .chat-sidebar-left { width: 25%; background-color: #8B9497; padding: 30px 20px; box-sizing: border-box; overflow-y: auto; }
        .contact-item { display: flex; align-items: center; margin-bottom: 25px; cursor: pointer; text-decoration: none; color: inherit; padding: 10px; border-radius: 10px; }
        .contact-item:hover, .active { background-color: rgba(0,0,0,0.1); }
        .contact-avatar { width: 55px; height: 55px; border-radius: 50%; background-color: white; margin-right: 15px; display: flex; justify-content: center; align-items: center; overflow: hidden; }
        .contact-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .contact-name { font-size: 1.1em; font-weight: bold; color: #111; margin: 0 0 3px 0; }
        .contact-status { font-size: 0.8em; color: #444; margin: 0; }
        .chat-main { width: 75%; background-color: #6D4C54; display: flex; flex-direction: column; position: relative; }
    </style>
</head>
<body>
    <div class="container-chat">
        
        <div class="chat-sidebar-left">
            <h1 style="margin-top: 0; color: #111; font-size: 2em; margin-bottom: 5px;">Message</h1>
            <hr style="border: 0; border-top: 2px solid #222; margin-bottom: 30px;">
            
            <a href="chat.php?contact=2" class="contact-item <?= ($contact_id == 2) ? 'active' : '' ?>">
                <div class="contact-avatar"><img src="ryan.png" alt="Ryan"></div>
                <div><p class="contact-name">Ryan Gosling</p><p class="contact-status">Hey Barbie !?</p></div>
            </a>
            <a href="chat.php?contact=3" class="contact-item <?= ($contact_id == 3) ? 'active' : '' ?>">
                <div class="contact-avatar"><img src="donald.jpg" alt="Vieu"></div>
                <div><p class="contact-name">Vieu non nommé</p><p class="contact-status">Send Money to Iran</p></div>
            </a>
            <a href="chat.php?contact=4" class="contact-item <?= ($contact_id == 4) ? 'active' : '' ?>">
                <div class="contact-avatar"><img src="dj.jpg" alt="DJ Khaled"></div>
                <div><p class="contact-name">DJ Khaled</p><p class="contact-status">Life is Roblox</p></div>
            </a>
            <a href="chat.php?contact=5" class="contact-item <?= ($contact_id == 5) ? 'active' : '' ?>">
                <div class="contact-avatar"><img src="wd.png" alt="WD Gaster"></div>
                <div><p class="contact-name">WD Gaster</p><p class="contact-status">Come play...</p></div>
            </a>
            <a href="chat.php?contact=6" class="contact-item <?= ($contact_id == 6) ? 'active' : '' ?>">
                <div class="contact-avatar"><img src="thukuna.jpg" alt="Thukuna"></div>
                <div><p class="contact-name">Thukuna</p><p class="contact-status">Hello Gojo, Gojo hello...</p></div>
            </a>
        </div>

        <div class="chat-main">
            <?php if($erreur_sql != "") { ?>
                <div style="background-color: white; color: red; padding: 10px; margin: 10px; border-radius: 5px;">Erreur d'envoi : <?= $erreur_sql ?></div>
            <?php } ?>

            <?php if($contact_id > 0) { ?>
                
                <div id="messages_box" style="flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column;">
                    <!-- Les messages s'afficheront ici -->
                </div>
                
                <div style="padding: 20px; background-color: rgba(0,0,0,0.1);">
                    <form method="POST" style="display: flex; gap: 10px;">
                        <input type="text" name="message" required autocomplete="off" style="flex: 1; padding: 15px; border-radius: 10px; border: none; font-family: inherit; font-size:1.1em; background-color: #EDEEE3;">
                        <button type="submit" name="send" style="padding: 15px 30px; border-radius: 10px; border: none; background-color: #769B62; color: white; cursor: pointer; font-family: inherit; font-weight: bold; font-size:1.1em;">Envoyer</button>
                    </form>
                </div>

                <script>
                    let message_box = document.getElementById('messages_box');
                    
                    setInterval(function(){
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function(){
                            if(this.readyState == 4 && this.status == 200) {
                                // Si le fichier est trouvé, on affiche son contenu
                                if(this.responseText.trim() !== "") {
                                    message_box.innerHTML = this.responseText;
                                }
                            } else if (this.readyState == 4 && this.status == 404) {
                                // Si le fichier n'est pas trouvé, on affiche une erreur
                                message_box.innerHTML = "<div style='color:white; text-align:center;'>Erreur 404 : Le fichier messages.php est introuvable.</div>";
                            }
                        }
                        xhttp.open("GET", "messages.php?contact=<?= $contact_id ?>", true);
                        xhttp.send();
                    }, 500); 
                </script>

            <?php } else { ?>
                <div style="flex:1; display:flex; justify-content:center; align-items:center; color:white; font-size:1.5em; opacity: 0.7;">
                    Sélectionnez une personne à gauche pour discuter
                </div>
            <?php } ?>
        </div>

    </div>
</body>
</html>