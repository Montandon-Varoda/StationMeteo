<?php
  #Variables de connexion à la base de données
  $hostname = "nhl-mysqlw01";
  $database = "db477991";
  $username = "db477991wipy";
  $password = "z6#hP9801";

  #Connextion à la base de données
  try {
    $bdd = new PDO('mysql:host='.$hostname.';dbname='.$database.';charset=utf8;', $username, $password);
  } catch (Exception $e) {
    die('Erreur: ' . $e->getMessage());
  }
?>
