<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="detail.css" media="screen">
	<link rel="stylesheet" type="text/css" href="detailPrint.css" media="print">
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

				$numero = 0;
				// On va sélectionner le tableau et on va prendre toute les mesures qui se trouve dans la période de temps
				$req = $bdd->prepare('SELECT * FROM temphumrain WHERE TimeStamp >= :debut AND TimeStamp <= :fin');
				$req->execute(array('debut' => $_SESSION['dateDebut'], 'fin' => $_SESSION['dateFin']));

				$reqD = $bdd->prepare('SELECT * FROM winddirection WHERE TimeStamp >= :debut AND TimeStamp <= :fin');
				$reqD->execute(array('debut' => $_SESSION['dateDebut'], 'fin' => $_SESSION['dateFin']));

				$reqS = $bdd->prepare('SELECT * FROM windspeed WHERE TimeStamp >= :debut AND TimeStamp <= :fin');
				$reqS->execute(array('debut' => $_SESSION['dateDebut'], 'fin' => $_SESSION['dateFin']));

				echo "
				<table>
					<tr>
						<th>
							N°
						</th>
						<th>
							Date
						</th>
						<th>
							Précipitations
						</th>
						<th>
							Humidité
						</th>
						<th>
							Temperature
						</th>
						<th>
							Direction du vent
						</th>
						<th>
							Vitesse du vent
						</th>
					</tr>";	 
				// On va chercher touts ce qui se trouve dans notre tableau
				while ($donnees = $req->fetch()) 
				{
					$donneesD = $reqD->fetch();
					$donneesS = $reqS->fetch();
					$numero ++;
					
					echo "<tr><td>".$numero."</td>";
					echo "<td>".date('d-m-Y H:i:s', strtotime($donnees['TimeStamp']))."</td>";
					if ($_SESSION['Rain']) 
					{
						echo "<td>".$donnees['Rain']." mm</td>";
					}
					else
					{
						echo "<td>-</td>";
					}
					if ($_SESSION['Humidity']) 
					{
						echo "<td>".$donnees['Humidity']."%</td>";
					}
					else
					{
						echo "<td>-</td>";
					}
					if ($_SESSION['Temperature']) 
					{
						echo "<td>".$donnees['Temperature']."°C</td>";
					}
					else
					{
						echo "<td>-</td>";
					}

					if ( $_SESSION['WindDirection']) 
					{
						echo "<td>".$donneesD['WindDirection']."</td>";
					}
					else
					{
						echo "<td>-</td>";
					}

					if ( $_SESSION['WindSpeed']) 
					{
						echo "<td>".$donneesS['WindSpeed']." km/h</td>";
					}
					else
					{
						echo "<td>-</td>";
					}
					echo "</tr>";	
				}
				echo "</table>";
				// Termine le traitement de la requête
				$req->closeCursor(); 
				$reqD->closeCursor();
				$reqS->closeCursor();
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