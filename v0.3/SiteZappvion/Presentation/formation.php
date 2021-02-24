<!DOCTYPE HTML>
<html>
  <head>
    <title>Présentation - Formation</title>
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
      echo '<link rel="stylesheet" href="../css/formationMobile.css"/>';
    }else{
      echo '<link rel="stylesheet" href="../css/formation.css"/>';
    }
    ?>
  </head>
  <body>
    <h1 class="Title">Formation</h1>

    <!Bouton retour>
    <a href="index.php">
      <button id="back" class="backbutton">
        <img class="backimg" src="../Icones/back.png">
        <p class="backText">Retour</p>
      </button>
    </a>

    <!Formation>
    <div id="divFormation">
      <img id="formationImg" src="ImagesFormation/formation.jpg">
      <p id="formationText">
        La formation au sein du centre de formation de la Base Aérienne de Payerne, se déroule sur quatre ans.
        </br></br>
        La formation des Cours Inter Entreprise est assurée par le centre de formation.
      </p>
    </div>

    <!EPSIC>
    <div id="divEPSIC">
      <img id="EPSICImg" src="ImagesFormation/epsic.jpg">
      <p id="EPSICText">
        La formation théorique est assurée par l'EPSIC à Lausanne.
      </p>
    </div>

    <!Examen>
    <div id="divExamen">
      <p id="examenText">
        Après deux ans , l'apprenant passe un examen pratique au CPNV
      </p>

      <img id="prodImg" src="ImagesFormation/prod.jpg">
      <p id="prodText">
        3h production
      </p>

      <img id="mesureImg" src="ImagesFormation/mesure.jpg">
      <p id="mesureText">
        3h mesure
      </p>

      <img id="microImg" src="ImagesFormation/micro.jpg">
      <p id="microText">
        3h microcontrôleur
      </p>
    </div>

    <!stages>
    <div id="divStages">
      <img id="aerodromeImg" src="ImagesFormation/aerodrome.jpg">
      <p id="aerodromeText">
        Dans sa troisième année il réalise des stages au sein de la Base Aérienne
      </p>
      <img id="meteoImg" src="ImagesFormation/meteo.jpg">
      <p id="meteoText">
        ainsi qu'à la station météorologique de Payerne.
      </p>
    </div>

  </body>
</html>
