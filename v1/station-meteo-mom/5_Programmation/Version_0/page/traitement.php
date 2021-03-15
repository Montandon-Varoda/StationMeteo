<?php
	/********************************************************
	*Titre:			Traitement Météo						*
	*Auteur:		Maxime Montandon						*
	*Date:			20.01.2021								*
	*Lieux:			Force Aérienne de Payerne 				*
	*Description:	Il va chercher tout les valeurs mesurés * 
	*dans la base de données selon la période entrée et il 	*
	*renvoie la moyenne de tout les mesures	à la page du 	*
	*formulaire 											*					
	*Modification:	Maxime Montandon 03.01.2021 			*
	*				Programme de base						*
	********************************************************/
	// On lance la session
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Formulaire_Meteo</title>
	</head>
	<body>
		<?php 
			try 
			{
				// On se connecte à SQL
	 			$bdd = new PDO('mysql:host=localhost;dbname=exercice;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			} 
			catch (Exception $e) 
			{
				// En cas d'erreur, on affiche un message et on arrête tout
				die('Erreur: '.$e->getMessage());	
			}

			//Si il n'y a pas d'erreur on peut continuer

			// Initialisation des variable
			$control = 0;
			$moyenne = 0;
			$moyennePluie = 0;
			$moyenneHumidité = 0;
			$moyenneTemperature = 0;
			$windDirection = 0;
			$moyenneWindSpeed = 0;
			$moyenneSpeed = 0;

			//On initialise les sessions
			$_SESSION['dateDebut'] = date('Y-m-d H:i:s', strtotime($_POST['dateDebut']));
			$_SESSION['dateFin'] = date('Y-m-d H:i:s', strtotime($_POST['dateFin']));
			$_SESSION['Rain'] = NULL;
			$_SESSION['Humidity'] = NULL;
			$_SESSION['Temperature'] = NULL;
			$_SESSION['WindSpeed'] = NULL;
			$_SESSION['WindDirection'] = NULL;

			// On control si la periode de temps est correcte 
			if ($_SESSION['dateDebut'] < $_SESSION['dateFin']) 
			{

				/***********************************
				*BDD Pluie, Température et Humidité*
				***********************************/

				// On va sélectionner le tableau et on va prendre toute les mesures qui se trouve dans la période de temps
				$req = $bdd->prepare('SELECT * FROM temphumrain WHERE TimeStamp >= :debut AND TimeStamp <= :fin');
				$req->execute(array('debut' => $_SESSION['dateDebut'], 'fin' => $_SESSION['dateFin']));

				// On va chercher touts ce qui se trouve dans notre tableau
				while ($donnees = $req->fetch()) 
				{

					// On récupère tous les données pour en faire une moyenne plus tard
					$moyenne ++;
					$moyennePluie = $moyennePluie + $donnees['Rain'];
					$moyenneHumidité = $moyenneHumidité + $donnees['Humidity'];
					$moyenneTemperature = $moyenneTemperature + $donnees['Temperature'];

					// On indique que le tableau contient au moins une mesure
					$control = 1;
				}

				// Termine le traitement de la requête
				$req->closeCursor(); 

				/**********************
				*BDD Direction du Vent*
				**********************/

				// On va sélectionner le tableau et on va prendre toute les mesures qui se trouve dans la période de temps et on va regrouper toute les mesures semblable entre elle et on récupère le plus grand groupe 
				$req = $bdd->prepare('SELECT * FROM winddirection WHERE TimeStamp >= :debut AND TimeStamp <= :fin GROUP BY WindDirection ORDER BY COUNT(WindDirection) DESC LIMIT 1');
				$req->execute(array('debut' => $_SESSION['dateDebut'], 'fin' => $_SESSION['dateFin']));

				// On va chercher touts ce qui se trouve dans notre tableau
				while ($donnees = $req->fetch()) 
				{
					// On regarede si c'est un vent d'est ou d'ouest
					if ($donnees['WindDirection'] == 'E' OR $donnees['WindDirection'] == 'W') 
					{
						// on affiche "d'" avant la direction
						$windDirection = "d'".$donnees['WindDirection'];
					}
					
					else
					{
						//on affiche "du" avant la direction
						$windDirection = "du ".$donnees['WindDirection'];
					}

					// On indique que le tableau contient au moins une mesure
					$control = 1;
				}

				// Termine le traitement de la requête
				$req->closeCursor(); 

				/********************
				*BDD Vitesse du Vent*
				********************/

				// On va sélectionner le tableau et on va prendre toute les mesures qui se trouve dans la période de temps
				$req = $bdd->prepare('SELECT * FROM windspeed WHERE TimeStamp >= :debut AND TimeStamp <= :fin');
				$req->execute(array('debut' => $_SESSION['dateDebut'], 'fin' => $_SESSION['dateFin']));

				// On va chercher touts ce qui se trouve dans notre tableau
				while ($donnees = $req->fetch()) 
				{

					// On récupère tous les données pour en faire une moyenne plus tard
					$moyenneSpeed ++;
					$moyenneWindSpeed = $moyenneWindSpeed + $donnees['WindSpeed'];

					// On indique que le tableau contient au moins une mesure
					$control = 1;
				}

				// Termine le traitement de la requête
				$req->closeCursor(); 

				/**********************
				*Affichage et Moyenne *
				**********************/

				// Si il y a au moins une mesure
				if ($control == 1) 
				{
					// On fait la moyenne avec les données qu'on a récupérer
					$moyennePluie = $moyennePluie / $moyenne;
					$moyenneHumidité = $moyenneHumidité / $moyenne;
					$moyenneTemperature = $moyenneTemperature / $moyenne;
					$moyenneWindSpeed = $moyenneWindSpeed / $moyenneSpeed;

					// On met le message qui contient les valeurs dans la session message
					$_SESSION['messageDate'] = "</br>Du <strong>".date('d-m-Y H:i:s', strtotime($_SESSION['dateDebut']))." </strong> Au <strong>".date('d-m-Y H:i:s', strtotime($_SESSION['dateFin']))." </strong>";

					if ($_SESSION['Rain'] = $_POST['Rain']) 
					{
						$_SESSION['messageRain'] = "</br>La précipitation moyenne est de <strong>".round($moyennePluie, 1)." mm</strong>";
					}
					
					if ($_SESSION['Humidity'] = $_POST['Humidity']) 
					{
						$_SESSION['messageHumidity' ] = "</br>L'humidité moyen est de <strong>".round($moyenneHumidité, 1)."%</strong>";
					}
					
					if ($_SESSION['Temperature'] = $_POST['Temperature']) 
					{
						$_SESSION['messageTemperature'] = "</br>La temperature moyenne est de <strong>".round($moyenneTemperature, 1)."°C</strong>";
					}

					if ($_SESSION['WindDirection'] = $_POST['windDirection']) 
					{
						$_SESSION['messageWindDirection'] = "</br>Le vent vient principalement <strong>".$windDirection."</strong>";
					}

					if ($_SESSION['WindSpeed'] = $_POST['windSpeed']) 
					{
						$_SESSION['messageWindSpeed'] = "</br>La vitesse moyenne du vent est de <strong>".round($moyenneWindSpeed, 1)." km/h</strong>";
					}
				}

				else
				{
					// On indique qu'il n'y a aucune mesure
					$_SESSION['messageError'] = "<strong></br>Il n'y a aucune mesure prise à cette période</strong>";
				}
			}

			else
			{
				// On indique que la periode de temps n'est pas possible
				$_SESSION['messageError'] = "<strong></br>Cette periode de temps n'est pas possible !!!</strong>";
			}
			
			// Redirection a la page principal
			header('Location: formulaire.php');
		?>
	</body>
</html>