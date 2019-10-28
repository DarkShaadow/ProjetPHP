<?php
$e = "";

try
{
    $bdd = new PDO('mysql:host=devbdd.iutmetz.univ-lorraine.fr;dbname=schlimme1u_php;charset=utf8', 'schlimme1u_appli', '31821437');
}
catch (Exception $e)
{
    die('Erreur : ' . $e->getMessage());
    $e = "Erreur";
}

$sql = "UPDATE SUJET SET ouvert = false WHERE idsujet = ?";
$req = $bdd->prepare($sql);
$req->execute(array($_GET["ref"]));

if ($_GET["page"] == "index")
    header('location:../index.php');
else
    header('location:AfficherSujet.php?ref=' . $_GET["sujet"]);
?>