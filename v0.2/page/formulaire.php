<?php
	/********************************************************
	*Titre:			Station Météo							*
	*Auteur:		Maxime Montandon						*
	*Date:			20.01.2021								*
	*Lieux:			Force Aérienne de Payerne 				*
	*Description:	Page d'interaction qui permet d'entrer  * 
	*une période puis ensuite voir le résultat				*						
	*Modification:	Maxime Montandon 25.01.2021 			*
	*				Programme de base						*
	********************************************************/
	// On lance la session
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="formulaire.css">
		<title>Station Météo</title>
	</head>
	<body>
		<div id="page">
			<header>
				<div id="titre">
					<img src="image/iconMeteo.png">
					<h1>Station Météo</h1>
				</div>
				<h2>Maxime Montandon</h2>
			</header>
			<section>

				<h1>Sélectionner</h1>
				<form action="traitement.php" method="post">

					<h2>Les Conditions:</h2>
					<input type="checkbox" name="Temperature" id="Temperature">
					<label for="Temperature">Temperature</label></br>
					<input type="checkbox" name="Humidity" id="Humidity">
					<label for="Humidity">Humidité</label></br>
					<input type="checkbox" name="Rain" id="Rain">
					<label for="Rain">Précipitacion</label></br>
					<input type="checkbox" name="windDirection" id="windDirection">
					<label for="windDirection">Direction du vent</label></br>
					<input type="checkbox" name="windSpeed" id="windSpeed">
					<label for="windSpeed">Vitesse du vent</label></br>

					<h2>La Période:</h2>
					<label for="dateDebut">Depuis le:</label>
					<input type="datetime" name="dateDebut" value="2021-01-01 00:00:00" required></br>
					<label for="dateFin">Jusqu'au:</label>
					<input type="datetime" name="dateFin" value="2021-01-01 23:59:59" required></br>

					<input type="submit" value="Appliquer">	
				</form>
				<p>
					<?php

						if ($_SESSION['messageDate']) 
						{
							// On récupère le message de la session
							echo $_SESSION['messageDate'];
							echo $_SESSION['messageRain'];
							echo $_SESSION['messageHumidity'];
							echo $_SESSION['messageTemperature'];
					?>
							<form action="detail.php" method="post">
								<input type="submit" value="Détail">	
							</form>
					<?php		
						}

						// On récupère le message de la session
						echo $_SESSION['messageError'];

						// On revide la session
						$_SESSION['messageError'] = NULL;
						$_SESSION['messageDate'] = NULL;
						$_SESSION['messageRain'] = NULL;
						$_SESSION['messageHumidity'] = NULL;
						$_SESSION['messageTemperature'] = NULL;
					?>
				</p>
			</section>
		</div>
		
	</body>
</html>