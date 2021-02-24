<!--
Sumulation entrée Wipy Sation météo

Permet de simuler manuellement des requêtes wipy
se désactive selon le if de début pour sécuriser en condition réelle

'VitesseVent' => $_POST["windS1"],
'DirectionVent' => $_POST["windD1"],
'Pluie' => $_POST["rain1"],
'Temperature' => $_POST["temp1"],
'Humidite' => $_POST["hum1"]
-->

<?php
# True = En test
# False = En conditions réelles

if ( True ){
?>

<h1>Sumulation entrée Wipy Sation Météo</h1>

<form action = "wipy.php" method = "post">
	<label for = "windS1">Vitesse Vent [ float: XXX.X ]</label>
	<input type = "text" name = "windS1">
	</br>
	<label for = "windD1">DirectionVent [ int: XXX ]</label>
	<input type = "text" name = "windD1">
	</br>
	<label for = "rain1">Pluie [ float: XXX.X ]</label>
	<input type = "text" name = "rain1">
	</br>	<label for = "temp1">Temperature [ float: XX.X ]</label>
	<input type = "text" name = "temp1">
	</br>
	<label for = "hum1">Humidite [ int: XX ]</label>
	<input type = "text" name = "hum1">
	</br>
	<input type = "hidden" name = "simulation" value = "true">
	<button method = "submit">Valider</button>
	
</form>

<?php
} else {
	header("Location:index.php");
}
?>