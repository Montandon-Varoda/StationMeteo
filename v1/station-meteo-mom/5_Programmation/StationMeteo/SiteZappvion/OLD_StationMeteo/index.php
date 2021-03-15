<!DOCTYPE HTML>
<!Fichier: index.php>
<html>
  <head>
    <title>Station Météo</title>
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
      echo '<link rel="stylesheet" href="../css/stationMeteoMobile.css"/>';
    }else{
      echo '<link rel="stylesheet" href="../css/stationMeteo.css"/>';
    }
    ?>
  </head>
  <body>
    <h1 class="Title">Station Météo</h1>

    <!Bouton retour>
    <a href="../index.php">
      <button id="back" class="backbutton">
        <img class="backimg" src="../Icones/back.png" height="40">
        <p class="backText">Retour</p>
      </button>
    </a>

    <?php
      include ("bdd.php");

      #Récupération des dernières valeurs mesurées
      $query = 'SELECT * FROM StationMeteo ORDER BY ID DESC LIMIT 1;';
      $rep = $bdd->query($query);
      $data = $rep->fetch();

    ?>

    <!Titre Valeurs>
    <div id="divTitleVal">
      <p id="titleVal">
        Dernières Mesures:
      </p>
      <p id="timeVal">
        <?php
          #Affichage de la date et de l'heure
          echo $data['Date']." - ".substr($data['Heure'], 0, 5)
        ?>
      </p>
    </div>

    <!Valeur Température>
    <div id="divTempValue">
      <p id="titleTempValue">
        Température
      </p>
      <div id=divImgTempValue class="roundDiv">
        <img id=imgTempValue src="img/thermometer.png"/>
      </div>
      <p id="TempValue">
         <?php
            #Affichage de la température
            echo $data["Temperature"]." [°C]"
          ?>
      </p>
    </div>

    <!Valeur Vitesse du vent>
    <div id="divWdSpValue">
      <p id="titleTempValue">
        Vitesse du vent
      </p>
      <div id=divImgWdSpValue class="roundDiv">
        <img id=imgWdSpValue src="img/wind.png"/>
      </div>
      <p id="WdSpValue">
        <?php
          #Affichage de la vitesse du vent
          echo $data['VitesseVent']." [km/h]"
        ?>
      </p>
    </div>

    <!Valeur Pluie>
    <div id="divRainValue">
      <p id="titleRainValue">
        Pluie
      </p>
      <div id=divImgRainValue class="roundDiv">
        <img id=imgRainValue src="img/rain.png"/>
      </div>
      <p id="RainValue">
        <?php
          #Affichage de la pluie
          echo $data['Pluie']." [mm/h]"
        ?>
      </p>
    </div>

    <!Valeur Humidité>
    <div id="divHumValue">
      <p id="titleHumValue">
        Humidité
      </p>
      <div id=divImgHumValue class="roundDiv">
        <img id=imgHumValue src="img/humidity.png"/>
      </div>
      <p id="HumValue">
        <?php
          #Affichage de l'humidité
          echo $data['Humidite']." [%]"
        ?>
      </p>
    </div>

    <!Valeur Direction du vent>
    <div id="divWdDrValue">
      <p id="titleWdDrValue">
        Direction du vent
      </p>
      <div id=divImgWdDrValue class="roundDiv">
        <img id=imgWdDrValue src="img/wind_direction.png"/>
      </div>
      <p id="WdDrValue">
        <?php echo $data['DirectionVent']."°N" ?>
      </p>
    </div>


    <!Titre Graphiques>
    <div id="divTitleGraph">
      <p id="titleGraph">
        Graphiques des dernières 24h:
      </p>
    </div>

    <!Graph Température>
    <div id="divGraphTemp">
      <p id="titleGraphTemp">
        Température [°C]
      </p>
      <img id=imgGraphTemp src="graph.php?color=E72F2F&value=Temperature"/>
    </div>

    <!Graph Pluie>
    <div id="divGraphRain">
      <p id="titleGraphRain">
        Pluie [mm/h]
      </p>
      <img id=imgGraphRain src="graph.php?color=3687E7&value=Pluie"/>
    </div>

    <!Graph Vitesse du vent>
    <div id="divGraphWdSp">
      <p id="titleGraphWdSp">
        Vitesse du vent [km/h]
      </p>
      <img id=imgGraphWdSp src="graph.php?color=DDD62C&value=VitesseVent"/>
    </div>

    <!Graph Humidité>
    <div id="divGraphHum">
      <p id="titleGraphHum">
        Humidité [%]
      </p>
      <img id=imgGraphHum src="graph.php?color=47D62F&value=Humidite"/>
    </div>

    <!spacer>
    <div class="Spacer">Space</div>
  </body>
</html>
