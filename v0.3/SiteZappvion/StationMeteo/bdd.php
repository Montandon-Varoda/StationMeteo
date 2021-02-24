<?php
  #Variables de connexion à la base de données
  # $hostname = "nhl-mysqlw01";
  $hostname = "localhost"; //old: 172.16.32.50
  $database = "db477991";
  $username = "root"; //old: db477991wipy
  $password = ""; //old: z6#hP9801

  #Connextion à la base de données
  try {
    $bdd = new PDO('mysql:host='.$hostname.';dbname='.$database.';charset=utf8;',
	$username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  } catch (Exception $e) {
    die('Erreur: ' . $e->getMessage());
  }
?>
