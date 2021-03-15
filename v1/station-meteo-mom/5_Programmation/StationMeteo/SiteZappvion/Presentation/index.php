<!DOCTYPE HTML>
<html>
  <head>
    <title>Zappvion - Présentation</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="../icon.png" />
    <?php
    function isMobileDevice(){
      $aMobileUA = array(
          '/iphone/i' => 'iPhone',
          '/ipod/i' => 'iPod',
          '/ipad/i' => 'iPad',
          '/android/i' => 'Android',
          '/blackberry/i' => 'BlackBerry',
          '/webos/i' => 'Mobile'
      );

      //Return true if Mobile User Agent is detected
      foreach($aMobileUA as $sMobileKey => $sMobileOS){
          if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
              return true;
          }
      }
      //Otherwise return false..
      return false;
    }
    if (isMobileDevice() == true) {
      echo '<link rel="stylesheet" href="../css/presentationMobile.css"/>';
    }else{
      echo '<link rel="stylesheet" href="../css/presentation.css"/>';
    }
    ?>
  </head>
  <body>
    <p class="invisible">salut</p>
    <h1 class="Title">Présentation</h1>

    <!Bouton retour>
    <a href="../">
      <button id="back" class="backbutton">
        <img class="backimg" src="../Icones/back.png">
        <p class="backText">Retour</p>
      </button>
    </a>
    <!Bouton Atelier>
    <a href="atelier.php" id="atelier">
      <img id="atelierImg" class="presentationImg" src="Images/atelierVide.jpg">
      <div class="textBackground" id="atelierBackgrnd"></div>
      <p id="atelierText" class="presentationText">Atelier</p>
    </a>

    <!Bouton Formation>
    <a href="formation.php" id="formation">
      <img id="formationImg" class="presentationImg" src="Images/formation.jpg">
      <div class="textBackground" id="formationBackgrnd"></div>
      <p id="formationText" class="presentationText">Formation</p>
    </a>

    <!Bouton Metier>
    <a href="metier.php" id="metier">
      <img id="metierImg" class="presentationImg" src="Images/metier.jpg">
      <div class="textBackground" id="metierBackgrnd"></div>
      <p id="metierText" class="presentationText">Métier</p>
    </a>
    <p class="invisible">salut</p>
  </body>
</html>
