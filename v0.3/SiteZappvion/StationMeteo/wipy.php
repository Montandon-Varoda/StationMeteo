<?php
	# Test
	$testMode = 0;

  #Connexion à la base de donnée
  include("bdd.php");

	#Test de quelle mesure il s'agit.
	#Vitesse du vent
	if (isset($_POST['windS1'])){
		#Préparation de la requete
		$req = $bdd->prepare("INSERT INTO windspeed (WindSpeed) VALUES(:WindSpeed)");
		#Lecture des paramètres du wipy
		$param = array(
			'WindSpeed' => $_POST["windS1"]);

		echo ("OK");
	}
	#Direction du vent
	else if (isset($_POST['windD1'])){
		#Préparation de la requete
		$req = $bdd->prepare("INSERT INTO winddirection(WindDirection) VALUES(:WindDirection)");
		#Lecture des paramètres du wipy
		$param = array(
			'WindDirection' => $_POST["windD1"]);

		echo ("OK");
	}
	#Autres mesures
	else if (isset($_POST['hum1'])){
		#Préparation de la requete
		$req = $bdd->prepare("INSERT INTO temphumrain(Temperature, Humidity, Rain) VALUES(:Temperature, :Humidity, :Rain)");

		#Lecture des paramètres du wipy
		$param = array(
			'Temperature' => $_POST["temp1"],
			'Humidity' => $_POST["hum1"],
			'Rain' => $_POST["rain1"]);

		echo ("OK");
	}
	#Execute la requete
	$req->execute($param);
?>
