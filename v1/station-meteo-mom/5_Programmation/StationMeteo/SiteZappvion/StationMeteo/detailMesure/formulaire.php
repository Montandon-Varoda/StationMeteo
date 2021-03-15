<?php
	/********************************************************
	*Titre:			Station Météo							*
	*Auteur:		Maxime Montandon						*
	*Date:			20.01.2021								*
	*Lieux:			Force Aérienne de Payerne 				*
	*Description:	Page d'interaction qui permet d'entrer  * 
	*une période puis ensuite voir le résultat				*						
	*Modification:	Maxime Montandon 10.02.2021 			*
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
		<link rel="icon" href="../../icon.png" />
		<title>Détail Mesure</title>
	</head>
	<body>
		<div id="page">
			<header>
				<h1>Station Météo</h1>
				<!--Bouton retour-->
				<a href="../index.php">
				    <button id="back" class="backbutton">
				      <img class="backimg" src="../../Icones/back.png" height="40">
				      <p class="backText">Retour</p>
				    </button>
				</a>	
			</header>	
			<section>
				<h2>Sélectionner</h2>
				<form action="traitement.php" method="post">
					<div id="conditions">
						<h3>Les Conditions:</h3>
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
					</div>
					<div id="periode">
						<h3>La Période:</h3>
						<label  for="dateDebut">Depuis le:</label>
						<input type="datetime-local" name="dateDebut" value="2021-01-07T00:00" required></br>
						<label for="dateFin">Jusqu'au:</label>
						<input type="datetime-local" name="dateFin" value="2021-01-07T23:59" required></br>
						<input type="submit" value="Appliquer">
					</div>		
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
							echo $_SESSION['messageWindDirection'];
							echo $_SESSION['messageWindSpeed'];
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
						$_SESSION['messageWindDirection'] = NULL;
						$_SESSION['messageWindSpeed'] = NULL;
					?>
				</p>
			</section>
		</div>	
	</body>
</html>