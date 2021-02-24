<?php

  #Variables login/autres
  $dbUser = "db477991wipy";
  $dbPasswd = "z6#hP9801";
  $hostname = "nhl-mysqlw01";
  $database = "db477991";
  $testMode = 0;

  #Connexion à la base de donnée
  try {
    $bdd = new PDO("mysql:host=".$hostname.";dbname=".$database.";charset=utf8", $dbUser, $dbPasswd);
  } catch (Exception $e) {
    die("Erreur : ".$e->getMessage());
  }

  #Choix d'afficher les secondes ou les minutes si on est en mode de test
  if ($testMode == 1) {
    $sec = (int)(date("s")/10)*10;
    $heure = date("H").":".date("i").":".$sec;
  }
  else {
    $heure = date("H").":".date("i");
  }
  #Récupération de la date
  $date = date("d.m.Y");

  #Préparation de la requête sql
  $req = $bdd->prepare("INSERT INTO StationMeteo(Date, Heure, VitesseVent,
  DirectionVent, Pluie, Temperature, Humidite) VALUES(:Date, :Heure,
  :VitesseVent, :DirectionVent, :Pluie, :Temperature, :Humidite)");

  #Execution de la requête avec les valeurs envoyées du wipy
  $req->execute(array(
    'Date' => $date,
    'Heure' => $heure,
    'VitesseVent' => $_POST["windS1"],
    'DirectionVent' => $_POST["windD1"],
    'Pluie' => $_POST["rain1"],
    'Temperature' => $_POST["temp1"],
    'Humidite' => $_POST["hum1"]
  ));

  #Affiche OK quand tout est terminé
  echo "OK";
?>
