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

			// On control si la periode de temps est correcte 
			if ($_POST['dateDebut'] < $_POST['dateFin']) 
			{
				// On va sélectionner le tableau et on va prendre toute les mesures qui se trouve dans la période de temps
				$req = $bdd->prepare('SELECT * FROM temphumrain WHERE TimeStamp >= :debut AND TimeStamp <= :fin');
				$req->execute(array('debut' => $_POST['dateDebut'], 'fin' => $_POST['dateFin']));

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
					
					/*
					//POUR VOIR LE DETAIL
					echo "</br>Mesure <strong>".$moyenne."</strong>
					</br>Date <strong>".date('d-m-Y H:i:s', strtotime($donnees['TimeStamp']))." </strong>
					</br>Pluie <strong>".$donnees['Rain']."mm</strong>
					</br>Humidité <strong>".$donnees['Humidity']."%</strong>
					</br>Temperature <strong>".$donnees['Temperature']."°C</strong></br>";
					*/
				}

				// Termine le traitement de la requête
				$req->closeCursor(); 

				// Si il y a au moins une mesure
				if ($control == 1) 
				{
					// On fait la moyenne avec les données qu'on a récupérer
					$moyennePluie = $moyennePluie / $moyenne;
					$moyenneHumidité = $moyenneHumidité / $moyenne;
					$moyenneTemperature = $moyenneTemperature / $moyenne;

					// On met le message qui contient les valeurs dans la session message
					$_SESSION['message'] = "
					</br>Du <strong>".date('d-m-Y H:i:s', strtotime($_POST['dateDebut']))." </strong> 
					Au <strong>".date('d-m-Y H:i:s', strtotime($_POST['dateFin']))." </strong>
					</br>La précipitation moyenne est de <strong>".round($moyennePluie, 1)."mm</strong>
					</br>L'humidité moyen est de <strong>".round($moyenneHumidité, 1)."%</strong>
					</br>La temperature moyenne est de <strong>".round($moyenneTemperature, 1)."°C</strong>";
				}

				else
				{
					// On indique qu'il n'y a aucune mesure
					$_SESSION['message'] = "<strong></br>Il n'y a aucune mesure prise à cette période</strong>";
				}
			}

			else
			{
				// On indique que la periode de temps n'est pas possible
				$_SESSION['message'] = "<strong></br>Cette periode de temps n'est pas possible !!!</strong>";
			}
			
			// Redirection a la page principal
			header('Location: formulaire.php');
		?>
	</body>
</html>