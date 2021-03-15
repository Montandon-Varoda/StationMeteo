<?php
	/********************************************************
	*Titre:			Détail Météo							*
	*Auteur:		Maxime Montandon						*
	*Date:			27.01.2021								*
	*Lieux:			Force Aérienne de Payerne 				*
	*Description:	Il va chercher tout les valeurs mesurés * 
	*dans la base de données selon la période entrée et il 	*
	*renvoie la moyenne de tout les mesures	à la page du 	*
	*formulaire 											*					
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
	<link rel="stylesheet" type="text/css" href="detail.css" media="screen">
	<link rel="stylesheet" type="text/css" href="detailPrint.css" media="print">
	<link rel="icon" href="../../icon.png" />
	<title>Détail Mesure</title>
</head>
<body>
	<div id="page">
		<header>
			<h1>Station Météo</h1>
			<!--Bouton retour-->
			<a href="formulaire.php">
				<button id="back" class="backbutton">
				    <img class="backimg" src="../../Icones/back.png" height="40">
				    <p class="backText">Retour</p>
				</button>
			</a>
		</header>
		<section>
			<h2>Les Mesures:</h2>
			<?php
				try 
				{
					// On se connecte à SQL
				 	$bdd = new PDO('mysql:host=localhost;dbname=db477991;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
				} 
				catch (Exception $e) 
				{
					// En cas d'erreur, on affiche un message et on arrête tout
					die('Erreur: '.$e->getMessage());	
				}
				//Si il n'y a pas d'erreur on peut continuer

				//recupereation de toutes les dates des mesures durant la période
				$req = $bdd->prepare('
					SELECT TimeStamp FROM temphumrain WHERE TimeStamp >= :debut AND TimeStamp <= :fin
					UNION  
					SELECT TimeStamp FROM winddirection WHERE TimeStamp >= :debut AND TimeStamp <= :fin
					UNION  
					SELECT TimeStamp FROM windspeed WHERE TimeStamp >= :debut AND TimeStamp <= :fin
					ORDER BY TimeStamp');
				$req->execute(array('debut' => $_SESSION['dateDebut'], 'fin' => $_SESSION['dateFin']));

				/*
				$req = $req->fetchAll(PDO::FETCH_ASSOC); 
                echo '<pre>';
                print_r($req);
                echo '</pre>';
                */

				$numero = 0;
				// On va sélectionner le tableau et on va prendre toute les mesures qui se trouve dans la période de temps
				$reqT = $bdd->prepare('SELECT * FROM temphumrain WHERE TimeStamp >= :debut AND TimeStamp <= :fin');
				$reqT->execute(array('debut' => $_SESSION['dateDebut'], 'fin' => $_SESSION['dateFin']));

				$reqD = $bdd->prepare('SELECT * FROM winddirection WHERE TimeStamp >= :debut AND TimeStamp <= :fin');
				$reqD->execute(array('debut' => $_SESSION['dateDebut'], 'fin' => $_SESSION['dateFin']));

				$reqS = $bdd->prepare('SELECT * FROM windspeed WHERE TimeStamp >= :debut AND TimeStamp <= :fin');
				$reqS->execute(array('debut' => $_SESSION['dateDebut'], 'fin' => $_SESSION['dateFin']));


				//affiche l'en-tête du tableau de mesures
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
				// On va chercher touts ce qui se trouve dans nos base de données et on l'affiche les mesures dans notre tableau
				$donneesT = $reqT->fetch();
				$donneesD = $reqD->fetch();
				$donneesS = $reqS->fetch();

				while ($donnees = $req->fetch()) 
				{
					//On vient regarder a quelle base de données la date appartient et on controle si la condition a été cochée
					if (($donnees['TimeStamp'] == $donneesT['TimeStamp'] AND ($_SESSION['Rain'] OR $_SESSION['Humidity'] OR $_SESSION['Temperature'])) OR ($donnees['TimeStamp'] == $donneesD['TimeStamp'] AND $_SESSION['WindDirection']) OR ($donnees['TimeStamp'] == $donneesS['TimeStamp'] AND $_SESSION['WindSpeed'])) 
					{
						//Affiche le numero de mesure
						$numero ++;
						echo "<tr><td>".$numero."</td>";

						// Affiche la date de la mesure
						echo "<td>".date('d-m-Y H:i:s', strtotime($donnees['TimeStamp']))."</td>";

						//Cotrole si la date corespond
						if ($donnees['TimeStamp'] == $donneesT['TimeStamp']) 
						{
							//Controle si la condition a été coché 
							if ($_SESSION['Rain']) 
							{
								echo "<td>".$donneesT['Rain']." mm</td>";
							}
							else
							{
								echo "<td>-</td>";
							}
							//Controle si la condition a été coché 
							if ($_SESSION['Humidity']) 
							{
								echo "<td>".$donneesT['Humidity']."%</td>";
							}
							else
							{
								echo "<td>-</td>";
							}
							//Controle si la condition a été coché 
							if ($_SESSION['Temperature']) 
							{
								echo "<td>".$donneesT['Temperature']."°C</td>";
							}
							else
							{
								echo "<td>-</td>";
							}
							$donneesT = $reqT->fetch();
						}
						else
						{
							echo "<td>-</td>";
							echo "<td>-</td>";
							echo "<td>-</td>";	
						}
							
						//Cotrole si la date corespond	
						if ($donnees['TimeStamp'] == $donneesD['TimeStamp']) 
						{
							//Controle si la condition a été coché 
							if ( $_SESSION['WindDirection']) 
							{
								echo "<td>".$donneesD['WindDirection']."</td>";
							}
							else
							{
								echo "<td>-</td>";
							}
							$donneesD = $reqD->fetch();
						}
						else
						{
							echo "<td>-</td>";	
						}

						//Cotrole si la date corespond
						if ($donnees['TimeStamp'] == $donneesS['TimeStamp']) 
						{
							//Controle si la condition a été coché 
							if ($_SESSION['WindSpeed']) 
							{
								echo "<td>".$donneesS['WindSpeed']." km/h</td>";
							}
							else
							{
								echo "<td>-</td>";
							}
							$donneesS = $reqS->fetch();
						}
						else
						{
							echo "<td>-</td>";	
						}

						// fin de la ligne du tableau
						echo "</tr>";
					}
					
				}
				
				//fin du tableau
				echo "</table>";
				
				// Termine le traitement de la requête
				$req->closeCursor(); 
				$reqT->closeCursor();
				$reqD->closeCursor();
				$reqS->closeCursor();
			?>	
		</section>	
	</div>
</body>
</html>