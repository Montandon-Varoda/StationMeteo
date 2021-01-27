<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="detail.css">
	<title>detail</title>
</head>
<body>
	<div id="page">
		<header>
			<img src="image/iconMeteo.png">
			<h1>Station Météo</h1>
		</header>
		<section>
			<h2>Les Mesures:</h2>
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

				$moyenne = 0;
				// On va sélectionner le tableau et on va prendre toute les mesures qui se trouve dans la période de temps
				$req = $bdd->prepare('SELECT * FROM temphumrain WHERE TimeStamp >= :debut AND TimeStamp <= :fin');
				$req->execute(array('debut' => $_SESSION['dateDebut'], 'fin' => $_SESSION['dateFin']));

				// On va chercher touts ce qui se trouve dans notre tableau
				while ($donnees = $req->fetch()) 
				{
					$moyenne ++;
					
					//POUR VOIR LE DETAIL
					echo "Mesure <strong>N°".$moyenne."</strong>
					</br>Mesure prise le: <strong>".date('d-m-Y H:i:s', strtotime($donnees['TimeStamp']))." </strong>";
					if ($_SESSION['Rain']) 
					{
						echo "</br>Les précipitations sont de: <strong>".$donnees['Rain']."mm</strong>";
					}
					if ($_SESSION['Humidity']) 
					{
						echo "</br>Le taux humidité est de: <strong>".$donnees['Humidity']."%</strong>";
					}
					if ($_SESSION['Temperature']) 
					{
						echo "</br>La temperature est de: <strong>".$donnees['Temperature']."°C</strong>";
					}
					echo "</br></br>";
				}

				// Termine le traitement de la requête
				$req->closeCursor(); 
			?>	
		</section>
		<footer>
			<p>
				Développé par: <a href="https://github.com/Montandon-Varoda/StationMeteo">Montandon</a>
			</p>
		</footer>	
	</div>
</body>
</html>