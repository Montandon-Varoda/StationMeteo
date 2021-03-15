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
      echo '<link rel="stylesheet" href="../css/metierMobile.css"/>';
    }else{
      echo '<link rel="stylesheet" href="../css/metier.css"/>';
    }
    ?>
  </head>
  <body>
    <h1 class="Title">Métier</h1>

    <!Bouton retour>
    <a href="index.php">
      <button id="back" class="backbutton">
        <img class="backimg" src="../Icones/back.png">
        <p class="backText">Retour</p>
      </button>
    </a>

    <!Print>
    <div id="divPrint">
      <img id="printImg" src="ImagesMetier/print.jpg">
      <p id="printText">
        L'électronicien doit travailler de manière autonome. Il doit être capable de créer et réaliser des circuits imprimés.
      </p>
    </div>

    <!Mesure>
    <div id="divMesure">
      <img id="mesureImg" src="ImagesMetier/mesure.jpg">
      <p id="mesureText">
        Réaliser des mesures de test et contrôle, ainsi que des dépannages de circuits complexes.
      </p>
    </div>

    <!Interface>
    <div id="divInterface">
      <img id="interfaceImg" src="ImagesMetier/interface.jpg">
      <p id="interfaceText">
        Créer des programmes pour automatiser des tâches, interfacer des machines ou permettre aux humains de communiquer avec des machines spécialisées.
      </p>
    </div>

    <!Doc>
    <div id="divDoc">
      <img id="docImg" src="ImagesMetier/doc.jpg">
      <p id="docText">
        L'électronicien doit savoir documenter l'entier de son travail, pour assurer une perrenité de ses travaux.
      </p>
    </div>

    <!Capacites>
    <div id="divCapacites">
      <p id="capacitesText">
        L'électronicien doit posséder une grande capacité de concentration et d'analyse. Il doit être résistant au stress. Avoir de bonne capacité manuelle.
      </p>
    </div>

  </body>
</html>
