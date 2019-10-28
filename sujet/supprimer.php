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

$sql = "DELETE FROM SUJET WHERE idsujet = ?";
$req = $bdd->prepare($sql);
$req->execute(array($_GET["ref"]));

$sql = "DELETE FROM REPONSE WHERE idsujet = ?";
$req = $bdd->prepare($sql);
$req->execute(array($_GET["ref"]));

header('location:../index.php');
?>