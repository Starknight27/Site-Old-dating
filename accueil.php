<?php
session_start();
// Si on n'est pas connecté, on retourne à l'accueil
if(!isset($_SESSION['user_id'])){
    header("location:index.php");
    exit();
}
$mon_pseudo = $_SESSION['user_pseudo'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil | Old Dating</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            gap: 50px;
        }
        .card {
            background-color: var(--slate-blue);
            width: 300px;
            height: 300px;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: white;
            font-size: 1.5em;
            transition: transform 0.3s;
            border: 5px solid var(--bg-light);
            text-align: center;
            padding: 20px;
        }
        .card:hover {
            transform: scale(1.05);
            background-color: var(--muted-green);
        }
        .card-icon {
            font-size: 3em;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div style="padding: 20px 50px; display: flex; justify-content: space-between; align-items: center;">
        <h1 style="color: white; margin: 0;">Old Dating</h1>
        <div style="color: white; font-size: 1.2em;">
            Bonjour, <strong><?= htmlspecialchars($mon_pseudo) ?></strong> ! | <a href="index.php" style="color: #E2BFC9;">Déconnexion</a>
        </div>
    </div>

    <div class="dashboard-container">
        <a href="chat.php" class="card">
            <div class="card-icon"></div>
            Mes Messages
        </a>

        <a href="evenements.php" class="card">
            <div class="card-icon"></div>
            Calendrier et<br>Événements
        </a>


        <a href="agenda.php" class="card">
            <div class="card-icon"></div>
            Agenda<br>Personnel
        </a>
    </div>
</body>
</html>