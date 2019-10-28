<html lang = "fr"> 
	<head>
		<meta charset = "UTF-8" />
		<link rel = "stylesheet" href = "../CSS/index.css" />
		<?php
			error_reporting(E_ALL ^ E_NOTICE);
			session_start();
			
			$correct = false;
			
			if (isset($_POST["create"]))
			    $correct = (trim($_POST["contenu"]) != "");
			
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
			    
			if ($e == "" && $correct)
			{
			    $sql = "INSERT INTO REPONSE(idsujet, idredacteur, daterep, textereponse, heurerep) VALUES(?, ?, DATE(now()), ?, time(now()))";
			    $req = $bdd->prepare($sql);
			    $req->execute(array($_GET["ref"], $_SESSION["user"], $_POST["contenu"]));
			}
		?>
	</head>
	<body>
		<div id = "Titelu">
		</div>
		<aside>
			<fieldset>
	    		<a href = "../index.php">Retour &agrave; la page d'accueil</a>
    		</fieldset>
    	</aside>
    	<header>
    		<?php
    			if (!isset($_SESSION["user"]))
    			{
    		?>
    			<section>
        			<a href = "../compte/Connexion.php">Connexion</a> 
        			<a href = "../compte/NouvelUser.php">Creer un compte</a>
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
            		    echo '<thead>Liste de vos sujets</thead>';
            		    
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
            		            echo '<td><img src = "../Image/vert.png" alt = "Ouvert" title = "Sujet ouvert"/></td>';
            		            echo '<td><a href = "fermer.php?ref=' . $row["idsujet"] . '&page=affi&sujet=' . $_GET["ref"] . '"><img src = "../Image/fermer.png" alt = "Fermer" /></a></td>';
            		        }
            		        else
            		        {
            		            echo '<td><img src = "../Image/rouge.png" alt = "Fermer" /></td>';
            		            echo '<td><a href = "ouvrir.php?ref=' . $row["idsujet"] . '&page=affi&sujet=' . $_GET["ref"] . '"><img src = "../Image/ouvrir.jpg" alt = "Ouvrir" /></a></td>';
            		        }
            		        if ($_SESSION["user"] == -1)
            		            echo '<td><a href = "supprimer.php?ref=' . $row["idsujet"] . '" title = "Supprimer le sujet"><img src = "../Image/croix.gif" alt = "Supprimer" /></a></td>';
            		        echo '</tr>';
            		    }
            		    
            		    echo '</table>';
            		    echo '</section>';
            		}
            ?>
            	<section>
    				<a href = "../compte/Deconnexion.php" id = "retour">D&eacute;connexion</a>
    				<a href = "../compte/ModifierProfile.php?ref=<?php echo $_SESSION["user"]; ?>">Modifier le profil</a> 
    			</section>
			<?php
    			}
    		?>
    	</header>
    	<section>
    		<?php
    		
    			if ($e == "")
    			{
        			$sql = "SELECT DISTINCT(idsujet), titresujet, DATE_FORMAT(datesujet, '%d/%m/%Y') AS datesujet, textsujet, pseudo, ouvert, heuresujet FROM SUJET, REDACTEUR WHERE SUJET.idredacteur = REDACTEUR.idredacteur AND idsujet = " . $_GET["ref"];
        			$req = $bdd->query($sql);
        			$row = $req->fetch();
    
        			echo '<article>';
        			echo '<fieldset>';
        		    echo '<h1>' . $row["titresujet"] . '</h1>';
        		    echo '<label>Par ' . $row["pseudo"] . ' le ' . $row["datesujet"] . ' &agrave; ' . $row["heuresujet"] . '</label>';
        		    echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $row["textsujet"] . '</p>';
        	        echo '</fieldset>';
        			echo '</article>';
        	        
        	        if (isset($_SESSION["user"]) && $row["ouvert"])
        	        {
            			echo '<article>';
        	            echo '<fieldset>';
        	            echo '<form method = "POST">';
        	            echo '<label>R&eacute;ponse</label><br />';
        	            echo '<textarea name = "contenu"></textarea>';
        	            echo '<br />';
        	            if (!$correct && isset($_POST["create"]))
        	                echo "Le contenu n'est pas saisi.<br />";
        	            echo '<input type = "submit" name = "create" value = "R&eacute;pondre">';
        	            echo '</form>';
        	            echo '</fieldset>';
            			echo '</article>';
        	        }
        	        
        	        $sql = "SELECT DISTINCT(idreponse), pseudo, DATE_FORMAT(daterep, '%d/%m/%Y') AS daterep, textereponse, heurerep FROM REPONSE, REDACTEUR WHERE idsujet = " . $_GET["ref"] . " AND REPONSE.idredacteur = REDACTEUR.idredacteur ORDER BY idreponse DESC";
        	        $req = $bdd->query($sql);
        	        foreach ($req as $row)
        	        {
            			echo '<article>';
        	            echo '<fieldset>';
        	            echo '<label>Par ' . $row["pseudo"] . ' le ' . $row["daterep"] . ' &agrave; ' . $row["heurerep"];
         	            if ($_SESSION["user"] == -1)
         	                echo ' <a href = "supprimerCom.php?ref='. $row["idreponse"] . '&sujet=' . $_GET["ref"] . '"><img src = "../Image/croix.gif" alt = "Supprimer" title = "Supprimer le commentaire" /></a>';
                        echo '</label>';
        	            echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $row["textereponse"] . '</p>';
       	                echo '</fieldset>';
            			echo '</article>';
        	        }
    			}
    		?>
		</section>
	</body>
</html>