<html lang = "fr">
	<head>
		<meta charset = "UTF-8" />
		<link rel = "stylesheet" href = "CSS/index.css" />
		<title>Accueil</title>
		<?php
			error_reporting(E_ALL ^ E_NOTICE);
			session_start();

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
			
			$correct = false;
			$titre = true;
			$contenu = true;
			
			if (isset($_POST["create"]))
			{
			    $titre = (trim($_POST["titre"]) != "");
			    $contenu = (trim($_POST["contenu"]) != "");
			    $correct = ($titre && $contenu);
			}

			if ($correct)
			{    			
    			if (isset($_POST["create"]))
    			{
    			    if ($e == "")
    			    {
    			        $sql = "INSERT INTO SUJET(idredacteur, titresujet, textsujet, datesujet, ouvert, heuresujet) VALUES(?, ?, ?, date(now()), true, time(now()))";
    			        $req = $bdd->prepare($sql);
    			        $req->execute(array($_SESSION["user"], $_POST["titre"], $_POST["contenu"]));
    			    }
    			}
            }
		?>
	</head>
	<body>
		<div id = "Titelu">
		</div>
		<aside>
		
		</aside>
		<header>
			<?php
				if (!isset($_SESSION["user"]))
				{
			?>
				<section>
    				<a href = "./compte/Connexion.php">Connexion</a> 
    				<a href = "./compte/NouvelUser.php">Creer un compte</a>
    			</section>
			<?php
				}
				else
				{
				    echo "<section>";
				    echo "Utilisateur : " . $_SESSION["pseudo"];
				    echo "</section>";

			        if ($e == "")
			        {
			            echo '<section id = "gestionSujet">';
			            echo '<table>';
			            
			            if ($_SESSION["user"] == -1)
			            {
			                $sql = "SELECT DISTINCT(idsujet), titresujet, ouvert FROM SUJET ORDER BY idsujet DESC";
                			$req = $bdd->prepare($sql);
                			$req->execute();
			            }
			            else
			            {
                			$sql = "SELECT DISTINCT(idsujet), titresujet, ouvert FROM SUJET WHERE idredacteur = ? ORDER BY idsujet DESC";
                			$req = $bdd->prepare($sql);
                			$req->execute(array($_SESSION["user"]));
			            }
            			foreach ($req as $row)
            			{
            			     echo '<tr>';
            			     echo '<td><a href = "sujet/AfficherSujet.php?ref=' . $row["idsujet"] . '">' . $row["titresujet"] . '</a></td>';
            				 if ($row["ouvert"])
            				 {
            				     echo '<td><img src = "Image/vert.png" alt = "Ouvert" title = "Sujet ouvert"/></td>';
            				     echo '<td><a href = "sujet/fermer.php?ref=' . $row["idsujet"] . '&page=index" title = "Fermer le sujet"><img src = "Image/fermer.png" alt = "Fermer" /></a></td>';
            				 }
            				 else
            				 {
            				     echo '<td><img src = "Image/rouge.png" alt = "Fermer" title = "Sujet ferm&eacute;"/></td>';
            				     echo '<td><a href = "sujet/ouvrir.php?ref=' . $row["idsujet"] . '&page=index" title = "Ouvrir le sujet"><img src = "Image/ouvrir.jpg" alt = "Ouvrir" /></a></td>';
            				 }
            				 if ($_SESSION["user"] == -1)
            				     echo '<td><a href = "sujet/supprimer.php?ref=' . $row["idsujet"] . '" title = "Supprimer le sujet"><img src = "Image/croix.gif" alt = "Supprimer" /></a></td>';
            				 echo '</tr>';
            			}    
			            
            			echo '</table>';
			            echo '</section>';
			        }
			?>
				<section>
    				<a href = "compte/Deconnexion.php" id = "retour">D&eacute;connexion</a>
    				<a href = "compte/ModifierProfile.php?ref=<?php echo $_SESSION["user"]; ?>">Modifier le profil</a> 
    			</section>
			<?php
				}
			?>
		</header>
		<section>
			<article id = "saisieSujet">
    			<?php 
    			    if (isset($_SESSION["user"]))
    			    {
    					echo '<fieldset>';
    			        echo '<form method = "POST">';
    			        echo '<label>Titre du sujet : </label><input type = "text" name = "titre" placeholder = "titre de l\'article"';
                        if (!$correct && $titre && isset($_POST["titre"]))
                            echo 'value = ' . $_POST["titre"];
                        echo '>';
    			        if (!$titre)
    			            echo " Le titre n'est pas saisi.";
    			        echo '<br />';
    			        echo '<textarea name = "contenu" placeholder = "contenu du sujet">';
    			        if (!$correct && $contenu && isset($_POST["contenu"]))
                            echo $_POST["contenu"];
                        echo '</textarea>';
    			        echo '<br />';
    			        if (!$contenu)
    			            echo "Le contenu n'est pas saisi.<br />";
    			        echo '<input type = "submit" name = "create" value = "Creer un sujet">';
    			        echo '</form>';
    			        echo '</fieldset>';
    			    }
    			?>
    		</article>
    		<article id = "sujet">
    			<?php				
    				if ($e == "")
    				{
        				$sql = "SELECT DISTINCT(idsujet), titresujet, DATE_FORMAT(datesujet, '%d/%m/%Y') AS datesujet, textsujet, ouvert, heuresujet, pseudo FROM SUJET, REDACTEUR WHERE SUJET.idredacteur = REDACTEUR.idredacteur ORDER BY idsujet DESC";
        				$req = $bdd->query($sql);
        				foreach ($req as $row)
        				{
        					echo '<fieldset>';
        					echo '<h1>' . $row["titresujet"] . '</h1>';
        					echo '<label>Par ' . $row["pseudo"] . ' le ' . $row["datesujet"] . ' &agrave; ' . $row["heuresujet"] . '</label>';
        					echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $row["textsujet"] . '</p>';
           					echo '<a href = "sujet/AfficherSujet.php?ref=' . $row["idsujet"] . '">Lire le sujet</a>';
        					echo '</fieldset>';
        				}
    				}
    			?>
			</article>
		</section>
	</body>
</html>