<?php
// Connexion à la base de données oldating_db
$con = mysqli_connect("localhost", "root", "", "oldating_db");

mysqli_query($con, "SET NAMES UTF8");

// Si la connexion échoue, on affiche un message d'erreur
if(!$con){
    die("Connexion échouée : " . mysqli_connect_error());
}
?>