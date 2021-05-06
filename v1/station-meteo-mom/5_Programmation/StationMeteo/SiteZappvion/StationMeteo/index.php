
<!--DOCTYPE HTML-->
<!--Fichier: index.php-->
<html>
  <head>
    <title>Station Météo</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	  <meta http-equiv="refresh" content="600"/>
    <link rel="icon" href="../icon.png" />
    <?php
      gc_enable();
      gc_collect_cycles();
      //Defini le jour de la mesure
      //Si le jour à été transmit via GET
      if (isset($_GET['dayToRead'])){
        $dayToRead = date("Y-m-d", strtotime($_GET['dayToRead']));
      }
      //Sinon date = actuelle
      else{
        $dayToRead = date("Y-m-d");
      }

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
	<h2 class="Info">raffraichissement de la page toute les 10' </h2>

    <!--Bouton retour-->
    <a href="../index.php">
      <button id="back" class="backbutton">
        <img class="backimg" src="../Icones/back.png" height="40">
        <p class="backText">Retour</p>
      </button>
    </a>

    <?php
      include ("bdd.php");

      #Récupération des dernières valeurs mesurées
      $querythm = 'SELECT * FROM TempHumRain ORDER BY TimeStamp DESC LIMIT 1;';
      $queryws = 'SELECT * FROM WindSpeed ORDER BY TimeStamp DESC LIMIT 1;';
      $querywd = 'SELECT * FROM WindDirection ORDER BY TimeStamp DESC LIMIT 1;';

      $repthm = $bdd->query($querythm);
      $repws = $bdd->query($queryws);
      $repwd = $bdd->query($querywd);

      $datathm = $repthm->fetch();
      $dataws = $repws->fetch();
      $datawd = $repwd->fetch();
    ?>

    <!--Titre Valeurs-->
    <div id="divTitleVal">
      <p id="titleVal">
        Dernières Mesures:
      </p>
      <p id="timeVal">
        <?php
          #Affichage de la date et de l'heure
          echo date("d/m/Y H:i:s", strtotime($datathm['TimeStamp']));
        ?>
      </p>
    </div>

    <!--Valeur Température-->
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
            echo $datathm["Temperature"]." [°C]"
          ?>
      </p>
    </div>

    <!--Valeur Vitesse du vent-->
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
          echo $dataws['WindSpeed']." [km/h]"
        ?>
      </p>
    </div>

    <!--Valeur Pluie-->
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
          echo $datathm['Rain']." [mm/h]"
        ?>
      </p>
    </div>

    <!--Valeur Humidité-->
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
          echo $datathm['Humidity']." [%]"
        ?>
      </p>
    </div>

    <!--Valeur Direction du vent-->
    <div id="divWdDrValue">
      <p id="titleWdDrValue">
        Direction du vent
      </p>
      <div id=divImgWdDrValue class="roundDiv">
        <img id=imgWdDrValue src="img/wind_direction.png"/>
      </div>
      <p id="WdDrValue">
        <?php echo $datawd['WindDirection']?>
      </p>
    </div>

    <!--Detail Valeur-->
    <div id="divDetailMesure">
      
        <p id="titleDetailMesure">
          Détail des mesures
        </p>
        <div id=divImgDetailMesure class="roundDiv">
          <img id=imgDetailMesure src="img/detail.png"/>
        </div>
        <p id="detailMesure">
          <a href="detailMesure/connexion.php">Voir Détail</a>   
        </p>
      
    </div>

    <!--Titre Graphiques-date("d/m/Y")-->
    <div id="divTitleGraph">
      <p id="titleGraph">
        Graphiques de la journée <?php echo $dayToRead ?>: 
      </p>
    </div>


    <!--Graph Température-->
    <div id="divGraphTemp">
      <p id="titleGraphTemp">
        Température [°C]
      </p>
      <img id=imgGraphTemp src="graph.php?color=FF0000&value=Temperature&dayToRead=<?php echo $dayToRead;?>"/>
    </div>

    <!--Graph Pluie-->
    <div id="divGraphRain">
      <p id="titleGraphRain">
        Pluie [mm/h]
      </p>
      <img id=imgGraphRain src="graph.php?color=0000FF&value=Rain&dayToRead=<?php echo $dayToRead;?>"/>
    </div>


    <!--Graph Humidité-->
    <div id="divGraphHum">
      <p id="titleGraphHum">
        Humidité [%]
      </p>
      <img id=imgGraphHum src="graph.php?color=00FF00&value=Humidity&dayToRead=<?php echo $dayToRead;?>"/>
    </div>

    <!-- Rose des vents -->
    <div id="divGraphWin">
      <p id="titleGraphWin">
        Vent
      </p>
      <p id="descriptionGraphWin">
        Les triangles correspondent aux 8 points cardinaux. </br>
        Les carrés représentes les heures (0h à 23h) où la vitesse du vent est >3km/h </br>
        Les barres représentes les vitesses <strong>moyenne</strong> (si elle dépasse 3km/h) par direction.</br></br>
        Voici l'échelle Beaufort utilisée dans ce graphique:
        <ul id="listeBeaufort">
          <li style="color: green">1 = 3 à 5 km/h (brise très légère)</li>
          <li style="color: green">2 = 6 à 11 km/h (brise légère)</li>
          <li style="color: green">3 = 12 à 19 km/h (vent faible)</li>
          <li style="color: green">4 = 20 à 28 km/h (vent modéré)</li>
          <li style="color: yellow">5 = 29 à 38 km/h (vent assez fort)</li>
          <li style="color: yellow">6 = 39 à 49 km/h (vent fort)</li>
          <li style="color: yellow">7 = 50 à 61 km/h (vent très fort)</li>
          <li style="color: red">8 = 62 à 74 km/h (vent tempétueux)</li>
          <li style="color: red">9 = 75 à 88 km/h (fort coup de vent)</li>
          <li style="color: red">10 = 89 à 102 km/h (tempête)</li>
          <li style="color: red">11 = 102 à 420 km/h (ouragan)</li>
        </ul>
      </p>
		<section>
			<img id="imgGraphWin"  style ="color: red" alt= "Pas eu de vent > 3km/h
			dans cette journée "  
			src="windrose.php?dayToRead=<?php echo $dayToRead;?>"/>
		</section>
    <?php
      gc_collect_cycles();
    ?>
	</div>
    <!--spacer-->
    <div class="Spacer">Space</div>
  </body>
</html>
