<!DOCTYPE HTML>
<html>
  <head>
    <title>Zappvion</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="icon.png" />
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
      echo '<link rel="stylesheet" href="css/zappvionMobile.css"/>';
    }else{
      echo '<link rel="stylesheet" href="css/zappvion.css"/>';
    }
    ?>
  </head>
  <body>
    <p class="invisible">salut</p>
    <h1 class="title">
      Bienvenue sur le site des apprenants électroniciens
      de la base aérienne de Payerne
    </h1>

    <!Bouton Meteo>
    <div class="dropdownMeteo">
      <img id="circleButton" class="imgmeteo" src="Icones/meteo.png">
      <div id="myDropdown" class="dropdownMeteo-content">
        <a class="aStation" href="StationMeteo">
          <img class="stationImg" id="meteoImg" src="Icones/stationMeteo.png">
          <p class="stationText">Station Météo</p>
        </a>
        <a class="aNoaa" href="Noaa" target="_blank">
          <img class="noaaImg" id="meteoImg" src="Icones/satellite.png">
          <p class="noaaText">Noaa</p>
        </a>
      </div>
    </div>

    <!Bouton Media>
    <div class="dropdownMedia">
      <img id="circleButton" class="imgmedia" src="Icones/camera.png">
      <div id="myDropdown" class="dropdownMedia-content">
        <a class="aImg" href="zenphoto/index.php?album=Images" target="_blank">
          <img class="photoImg" id="mediaImg" src="Icones/photo.png">
          <p class="photoText">Photos</p>
        </a>
        <a class="aVideo" href="zenphoto/index.php?album=Videos" target="_blank">
          <img class="videoImg" id="mediaImg" src="Icones/video.png">
          <p class="videoText">Vidéos</p>
        </a>
      </div>
    </div>

    <!Bouton Documents>
    <a href="Documents.php">
      <img id="circleButton" class="imgdoc" src="Icones/document.png">
      <p class="docText">Documents</p>
    </a>

    <!Bouton ELO>
    <a href="Presentation/">
      <img id="circleButton" class="imgelo" class="ELO" src="Icones/LogoEloPetit.png">
      <p class="eloText" id="mediaText">Présentation</p>
    </a>
	
	<!Bouton Horloge Parlante>
    <a href="HorlogeParlante/index.php">
      <img id="circleButton" class="imghp" src="Icones/horlogeparlante.png">
      <p class="hpText">Horloge Parlante HAM</p>
    </a>
	
    <p class="invisible">salut</p>
  </body>
</html>
