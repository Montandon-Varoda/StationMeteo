<?php
	/********************************************************
	*Titre:			Station Météo							*
	*Auteur:		Maxime Montandon						*
	*Date:			20.01.2021								*
	*Lieux:			Force Aérienne de Payerne 				*
	*Description:	Page d'interaction qui permet d'entrer  * 
	*une période puis ensuite voir le résultat				*						
	*Modification:	Maxime Montandon 20.01.2020 			*
	*				Programme de base						*
	********************************************************/
	// On lance la session
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Station Météo</title>
	</head>
	<body>
		<header>
			<h1>Station Météo</h1>
			<h2>Maxime Montandon</h2>
		</header>
		<section>

			<h1>Sélectionner la période</h1>
			<form action="traitement.php" method="post">

				<label for="dateDebut">Depuis le:</label>
				<input type="date" name="dateDebut"></br>

				<label for="dateFin">Jusqu'au:</label>
				<input type="date" name="dateFin"></br>

				<input type="submit" value="Appliquer">	
			</form>
			<p>
				<?php

					// On récupère le message de la session
					echo $_SESSION['message'];

					// On revide la session
					$_SESSION['message'] = NULL;
				?>
			</p>
		</section>
	</body>
</html>