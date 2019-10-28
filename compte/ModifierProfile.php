<html lang = "fr">
	<head>
		<meta charset = "UTF-8" />
		<link rel = "stylesheet" href = "../CSS/compte.css" />
		<title>Modifier votre compte</title>
		<?php
			error_reporting(E_ALL ^ E_NOTICE);
			session_start();
			
			function valid_email($str) {
			    return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
			}
			
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
			
			$typu = "password";
			$prenom = true;
			$nom = true;
			$pseudo = true;
			$pseudodispo = true;
			$mail = true;
			$maildispo = true;
			$mdpsaisit = true;
			$mdp = true;
			$correct = true;
			
			$snom = "";
			$sprenom = "";
			$spseudo = "";
			$smail = "";
			$smdp = "";
	
			$sql = "SELECT * FROM REDACTEUR WHERE idredacteur = ?";
			$req = $bdd->prepare($sql);
			$req->execute(array($_GET["ref"]));
			$row = $req->fetch();
			
			$snom = $row["nom"];
			$sprenom = $row["prenom"];
			$spseudo = $row["pseudo"];
			$smail = $row["adressemail"];
			$smdp = $row["motdepasse"];
			
			if (isset($_POST["connecter"]))
				header('location:../index.php');
			else
			{
				
				if (isset($_POST["update"]))
				{
				    $prenom = (trim($_POST["prenom"]) != "");
				    $nom = (trim($_POST["nom"]) != "");
				    
				    $pseudo = (trim($_POST["username"]) != "");
				    if ($pseudo)
				    {
    				    
    				    if ($e == "")
    				    {
    				        $e = "Connexion etablie";
    		                $sql = "SELECT * FROM REDACTEUR WHERE pseudo = :pseudo";
    		                $req = $bdd->prepare($sql);
    		                $req->execute(array('pseudo'=>$_POST["username"]));
    		                $i = 0;
    		                foreach($req as $row)
    		                {
    		                    $i++;
    		                }
    		                $pseudodispo = ($i == 0);
    				    }
			        }
				    
				    $mail = valid_email($_POST["mail"]);
				    if ($mail)
				    {
				        
				        if ($e == "" || $e == "Connexion etablie")
				        {
				            $e = "Connexion etablie";
				            $sql = "SELECT * FROM REDACTEUR WHERE adressemail = :mail";
				            $req = $bdd->prepare($sql);
				            $req->execute(array('mail'=>$_POST["mail"]));
				            $i = 0;
				            foreach($req as $row)
				            {
				                $i++;
				            }
				            $maildispo = ($i == 0);
				        }
				    }
				    
				    $mdp = (trim($_POST["password1"]) != "" && trim($_POST["password2"]) != "" && trim($_POST["password1"]) == trim($_POST["password2"]));
				    if (!$mdp)
				    {
				        $mdpsaisit = (trim($_POST["password1"]) != "" && trim($_POST["password2"]) != "");
				        $mdp = (trim($_POST["password1"]) == trim($_POST["password2"]));
				    }
					
				    $correct = ($prenom && $nom && $pseudo && $mail && $mdp && $mdpsaisit);
				    
					if ($correct)
					{
					    if ($e == "" || $e == "Connexion etablie")
					    {
					        $e = "Connexion etablie";
					        $sql = "UPDATE REDACTEUR SET nom = ?, prenom = ?, adressemail = ?, motdepasse = ?, pseudo = ? WHERE idredacteur = ?";
					        $req = $bdd->prepare($sql);
					        $req->execute(array($_POST["nom"], $_POST["prenom"], $_POST["mail"], $_POST["password1"], $_POST["username"], $_POST["id"]));
					    }
						header('location:../index.php');
					}
				}
				
				if (!isset($_POST["update"]) || !$correct)
				{
		?>
	</head>
	<body>
		<section>
			<fieldset>
        		<h1>Modifier votre compte</h1>
        		<form method = "POST">
        			<table>
        				<tr>
        					<td>Prenom</td>
        					<td><input type = "text" name = "prenom" size = "25" placeholder = "prenom" <?php if (!isset($_POST["update"])) echo 'value = ' . $sprenom; ?> /></td>
        					<td></td>
        					<td><?php if (!$prenom) echo "Le pr&eacute;nom n'est pas saisit"; ?></td>
        				</tr>
        				<tr>
        					<td>Nom</td>
        					<td><input type = "text" name = "nom" size = "25" placeholder = "nom" <?php if (!isset($_POST["update"])) echo 'value = ' . $snom; ?> /></td>
        					<td></td>
        					<td><?php if (!$nom) echo "Le nom n'est pas saisit"; ?></td>
        				</tr>
        				<tr>
        					<td>Pseudo</td>
        					<td><input type = "text" name = "username" size = "25" placeholder = "pseudo" <?php if (!isset($_POST["update"])) echo 'value = ' . $spseudo; ?> /></td>
        					<td></td>
        					<td>
        						<?php 
        						    if (!$pseudodispo)
        						        echo "Le pseudo n'est pas disponible";
        						    else if (!$pseudo)
        						        echo "Le pseudo est invalide"; 
        						?>
        					</td>
        				</tr>
        				<tr>
        					<td>Email</td>
        					<td><input type = "text" name = "mail" size = "25" placeholder = "addresse mail" <?php if (!isset($_POST["update"])) echo 'value = ' . $smail; ?> /></td>
        					<td></td>
        					<td>
        						<?php 
        						    if (!$maildispo)
        						        echo "L'adresse mail n'est pas disponible";
        						    else if (!$mail)
        						        echo "L'adresse mail est invalide";
        						?>
        					</td>
        				</tr>
        				<tr>
        					<td>Mot de passe</td>
        					<td><input type = "password" name = "password1" size = "25" placeholder = "mot de passe" <?php if (!isset($_POST["update"])) echo 'value = ' . $smdp; ?> /></td>;
        					<td></td>
        					<td>
            					<?php
            						if (!$mdpsaisit)
            						    echo "Le mot de passe n'est pas saisit";
            						else if (!$mdp)
            						    echo "Les mots de passes sont diff&eacute;rents";
            					?>
        					</td>
        				</tr>
        				<tr>
        					<td>Verification</td>
        					<td><input type = "password" name = "password2" size = "25" placeholder = "mot de passe" <?php if (!isset($_POST["update"])) echo 'value = ' . $smdp; ?> /></td>;
        					<td></td>
        					<td></td>
        				</tr>
        			</table>
        			<p>
        				<input type = "hidden" <?php if (!isset($_POST["update"])) echo 'value = ' . $_GET["ref"]; else echo 'value = ' . $_POST["id"]; ?> name = "id" />
        				<input type = "submit" value = "Valider les modifications" name = "update" />
        				<input type = "submit" value = "Retour" name = "connecter" />
        			</p>
        		</form>
        		<?php
        				}
        			}
        		?>
        	</fieldset>
        </section>
	</body>
</html>