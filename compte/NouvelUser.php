<html lang = "fr">
	<head>
		<meta charset = "UTF-8" />
		<link rel = "stylesheet" href = "../CSS/compte.css" />
		<title>Nouvel utilisateur</title>
		<?php
			error_reporting(E_ALL ^ E_NOTICE);
			session_start();
			
			function valid_email($str) {
			    return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
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
	
			if (isset($_POST["connecter"]))
				header('location:Connexion.php');
			else
			{
				
				if (isset($_POST["create"]))
				{
				    $prenom = (trim($_POST["prenom"]) != "");
				    $nom = (trim($_POST["nom"]) != "");
				    
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
					        $sql = "INSERT INTO REDACTEUR(nom, prenom, adressemail, motdepasse, pseudo) VALUES(?, ?, ?, ?, ?)";
					        $req = $bdd->prepare($sql);
					        $req->execute(array($_POST["nom"], $_POST["prenom"], $_POST["mail"], $_POST["password1"], $_POST["username"]));
					    }
						header('location:../index.php');
					}
				}
				
				if (!isset($_POST["create"]) || !$correct)
				{
		?>
	</head>
	<body>
		<section>
			<fieldset>
        		<h1>Creer un compte</h1>
        		<form method = "POST">
        			<table>
        				<tr>
        					<td>Prenom</td>
        					<td><input type = "text" name = "prenom" size = "25" placeholder = "prenom" <?php if ($prenom && isset($_POST["prenom"])) echo 'value = '. $_POST["prenom"];?> /></td>
        					<td></td>
        					<td><?php if (!$prenom) echo "Le pr&eacute;nom n'est pas saisit"; ?></td>
        				</tr>
        				<tr>
        					<td>Nom</td>
        					<td><input type = "text" name = "nom" size = "25" placeholder = "nom" <?php if ($nom && isset($_POST["nom"])) echo 'value = '. $_POST["nom"];?> /></td>
        					<td></td>
        					<td><?php if (!$nom) echo "Le nom n'est pas saisit"; ?></td>
        				</tr>
        				<tr>
        					<td>Pseudo</td>
        					<td><input type = "text" name = "username" size = "25" placeholder = "pseudo" <?php if ($pseudo && $pseudodispo && isset($_POST["username"])) echo 'value = '. $_POST["username"];?> /></td>
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
        					<td><input type = "text" name = "mail" size = "25" placeholder = "addresse mail" <?php if ($mail && $maildispo && isset($_POST["mail"])) echo 'value = '. $_POST["mail"];?> /></td>
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
        					<td><input type = "password" name = "password1" size = "25" placeholder = "mot de passe" <?php if ($mdp && $mdpsaisit && isset($_POST["password1"])) echo 'value = '. $_POST["password1"];?> /></td>;
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
        					<td><input type = "password" name = "password2" size = "25" placeholder = "mot de passe" <?php if ($mdp && $mdpsaisit && isset($_POST["password1"])) echo 'value = '. $_POST["password1"];?> /></td>;
        					<td></td>
        					<td></td>
        				</tr>
        			</table>
        			<p>
        				<input type = "submit" value = "Creer un compte" name = "create" />
        				<input type = "submit" value = "Vous avez d&eacute;j&agrave; un compte ?" name = "connecter" />
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