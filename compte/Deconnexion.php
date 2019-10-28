<html lang = "fr"> 
	<head>
		<meta charset = "UTF-8" />
		<link rel = "stylesheet" href = "../CSS/compte.css" />
		<title>Accueil</title>
		<?php
			error_reporting(E_ALL ^ E_NOTICE);
			session_start();
			
			if ($_POST["deconnexion"] == "Oui")
			{
				$_SESSION = [];
				header('location:../index.php');
			}
			else if ($_POST["deconnexion"] == "Non")
				header('location:../index.php');
			else
			{
		?>
	</head>
	<body>
		<section>
    		<fieldset>
        		<form method = "POST">
        			<p>&Ecirc;tes vous s&ucirc;r de vouloir vous d&eacute;connecter ?</p>
        			<input type = "submit" name = "deconnexion" value = "Oui" />
        			<input type = "submit" name = "deconnexion" value = "Non" />
        		</form>
        	</fieldset>
        </section>
	</body>
	<?php
		}
	?>
</html>