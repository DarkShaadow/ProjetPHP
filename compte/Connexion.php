<html lang = "fr">
	<head>
		<meta charset = "UTF-8" />
		<link rel = "stylesheet" href = "../CSS/compte.css" />
		<title>Connexion</title>
		<?php
			error_reporting(E_ALL ^ E_NOTICE);
			session_start();
			
			function isEmail($str)
			{
				return (strpos($str, '@'));
			}
			
			$username = true;
			$password = true;
			
			$valid = true;
			
			if (isset($_POST["newuser"]))
				header('location:NouvelUser.php');
			else if (isset($_POST["connecter"]) && trim($_POST["username"]) != "" && trim($_POST["password"]) != "")
			{
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
   				
   				if ($e == "")
   				{
   					$e = "Connexion &eacute;tablie";
   					if (isEmail(trim($_POST["username"])))
   						$champ = "adressemail";
   					else
   						$champ = "pseudo";
   					echo $champ . "<br />";
   					$sql = "SELECT * FROM REDACTEUR WHERE " . $champ . " = :valeur AND motdepasse = :mdp";
   					$req = $bdd->prepare($sql);
   					$req->execute(array('valeur'=>$_POST["username"], 'mdp'=>$_POST["password"]));
   					if ($row = $req->fetch())
   					{
   						$_SESSION["user"] = $row["idredacteur"];
   						$_SESSION["nom"] = strtoupper($row["nom"]);
   						$_SESSION["prenom"] = ucwords($row["prenom"]);
   						$_SESSION["pseudo"] = $row["pseudo"];
   						$_SESSION["mail"] = $row["adressemail"];
   						header('location:../index.php');
   					}
   					else
   					    $valide = false;
   				}
			}
			
			if (!isset($_POST["connecter"]) || !$valide)
			{
			    if (isset($_POST["connecter"]))
			    {
    				$username = trim($_POST["username"]) != "";
    				$password = trim($_POST["password"]) != "";
			    }
		?>
	</head>
	<body>
		<section>
			<fieldset>
        		<h1>Connexion</h1>
        		<?php 
            		if (!$valide && $username && $password && isset($_POST["connecter"]))
            		    echo "<p>Identifiants invalides</p>";
        		?>
        		<form method = "POST">
        			<table>
        				<tr>
        					<td>Identifiant</td>
        					<td><input type = "text" name = "username" size = "25" placeholder = "pseudo ou email" <?php if ($username && isset($_POST["username"])) echo 'value = ' . $_POST["username"];?>/><?php if (!$username) echo "Pseudo à saisir"; ?></td>
        				</tr>
        				<tr>
        					<td>Mot de passe</td>
        					<td><input type = "password" name = "password" size = "25" placeholder = "mot de passe" <?php if ($password && isset($_POST["password"])) echo 'value = ' . $_POST["password"];?>/><?php if (!$password) echo "Mot de passe à saisir"; ?></td>
        				</tr>
        			</table>
        			<p>
        				<input type = "submit" value = "Connexion" name = "connecter" />
        				<input type = "submit" value = "Nouveau compte" name = "newuser" />
        			</p>
        		</form>
        		<?php
        			}
        		?>
        	</fieldset>
    	</section>
	</body>
</html>