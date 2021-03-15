<?php
	// Définition du tablau de points pour le polygone
	$values = array(
	            40,  50,  // Point 1 (x, y)
	            20,  240, // Point 2 (x, y)
	            60,  60,  // Point 3 (x, y)
	            240, 20,  // Point 4 (x, y)
	            50,  40,  // Point 5 (x, y)
	            20,  20    // Point 6 (x, y)
	            );
	// Création d'une image
	$image = imagecreatetruecolor(250, 250);
	// Alloue quelques couleurs
	$bg   = imagecolorallocate($image, 0, 0, 0);
	$blue = imagecolorallocate($image, 0, 0, 255);
	// Remplit l'arrière-plan
	imagefilledrectangle($image, 0, 0, 249, 249, $bg);
	// Dessine le polygone
	imagefilledpolygon($image, $values,6, $blue);
	// Affichage de l'image
	header('Content-type: image/png');
	imagepng($image);
	imagedestroy($image);
	?>