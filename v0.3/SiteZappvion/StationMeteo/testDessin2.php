<?php
  // Définition du tablau de points pour le polygone
  $values = array(
              25,  75,  // Point 11 (x, y)
              50, 125,  // Point 10 (x, y)
              25,  175, // Point 9 (x, y)
              75,  175, // Point 8 (x, y)
              125,  200,  // Point 7 (x, y)
              175,  175,    // Point 6 (x, y)
              225,  175,  // Point 5 (x, y)
              200,  125,  // Point 4 (x, y)
              225,  75,  // Point 3 (x, y)
              175, 75, // Point 2 (x, y)
              125,  50,  // Point 1 (x, y)
              75,  75    // Point 12 (x, y)
                          );
   
  // Création d'une image
  $image = imagecreatetruecolor(250, 250);
   
  // Alloue quelques couleurs
  $bg   = imagecolorallocate($image, 0, 0, 255);
  $blue = imagecolorallocate($image, 255, 0, 0);
   
  // Remplit l'arrière-plan
  imagefilledrectangle($image, 0, 0, 249, 249, $bg);
   
  // Dessine le polygone
  imagefilledpolygon($image, $values,12, $blue);
   
  // Affichage de l'image
  header('Content-type: image/png');
  imagepng($image);
  imagedestroy($image);
  ?>