<!DOCTYPE HTML>
<html>
  <head>
    <title>Présentation - Atelier</title>
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
      echo '<link rel="stylesheet" href="../css/atelierMobile.css"/>';
    }else{
      echo '<link rel="stylesheet" href="../css/atelier.css"/>';
    }
    ?>
  </head>
  <body>
    <h1 class="Title">Atelier</h1>

    <!Bouton retour>
    <a href="index.php">
      <button id="back" class="backbutton">
        <img class="backimg" src="../Icones/back.png">
        <p class="backText">Retour</p>
      </button>
    </a>

    <!Places de travail>
    <div id="divPlaces">
      <img id="atelierImg" src="Images/atelierVide.jpg">
      <p id="places">
        L'atelier d'électronique comporte 20 places de travail, dont 16 sont occupées par nos apprenants.
      </p>
    </div>

    <!Local circuit>
    <div id="divLocalCircuit">
      <p id="gravText">
        Un local "circuit", qui nous permet de graver les prints développés sur Target ou Kicad.
      </p>
      <img id="gravImg" src="ImagesAtelier/graveuse.jpg"/>
    </div>

    <!Imprimante 3d>
    <div id="divImpr3d">
      <img id="impr3dImg" src="ImagesAtelier/imprimante3D.jpg"/>
      <p id="impr3dText">
        Une imprimante 3D nous permet de réaliser nos propres pièces mécanique.
      </p>
    </div>

    <!Salle Théorie>
    <div id="divSalleTh">
      <img id="salleThImg1" src="ImagesAtelier/salleTh1.jpg"/>
      <img id="salleThImg2" src="ImagesAtelier/salleTh2.jpg"/>
      <p id="salleThText">
        Deux salles de théorie.
      </p>
    </div>

    <!Notebook>
    <div id="divNotebook">
      <p id="notebookText">
        Chaque apprenant vient avec son notebook, qui peut se connecter au réseau spécifique du centre de formation.
		Il réalise lors de sa première année son alimentation personelle.
      </p>
      <img id="notebookImg" src="ImagesAtelier/notebook.jpg"/>
    </div>

    <!Appareils>
    <div id="divAppareils">
      <p id="appareilsText">
        Tous les appareils nécessaires à la formation sont à disposition.
      </p>
      <img id="appareilsImg" src="ImagesAtelier/appareils.jpg"/>
    </div>
  </body>
</html>
