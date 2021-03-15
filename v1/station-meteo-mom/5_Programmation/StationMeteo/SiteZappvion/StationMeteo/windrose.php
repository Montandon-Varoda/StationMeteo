<?php
/*=======================================================================
 // File:        windrose.php
 // Description: Graphique utilisant les outils jpgraph pour visualiser
 //              l'histoque des vents sur une journée, ainsi que la 
 //              vitesse max en km/h par cadrant
 // Created:     2020-09 par M Ding
 // Ver:         0.8 (modification sous impulsion JY Dupertuis 2020-12)
 //
 // 
 //========================================================================
 */
//Includes
include ("bdd.php");
include ("src/jpgraph.php");
include ("src/jpgraph_canvas.php");
include ("src/jpgraph_canvtools.php");

date_default_timezone_set('UTC');

//Défini la tranche à lire
/*
* Il est possible de préciser un jour et une heure
* à lire.
*
* Pour ce faire, il suffit de passer les arguments:
*
* dayToRead =   dd.mm.yyyy
* hoursToRead = xx
*
* Le système va ensuite afficher les valeurs corrésondantes pour
* la tranche précisée.
* Ex: http://localhost/siteserver/StationMeteo/?dayToRead=10.10.2020
* (Lit les données du 10.10.2020)
*
*/
//Par défaut: date & heure actuelle.s
if (isset($_GET['hoursToRead'])){
  define("HOURSTOREAD", $_GET['hoursToRead']);
}else{
  define("HOURSTOREAD", 24);
}
if (isset($_GET['dayToRead'])){
  define("DAYTOREAD", $_GET['dayToRead']);
}else{
  define("DAYTOREAD", date("Y-m-d"));
}


/*
* Défini les différentes dimensions du graphique
*/
define("WINDOWSSIZE", 700);
define("SIZE", WINDOWSSIZE / 2.4);
define("OCTOGONESIDE", tan(22.5 * (3.14 / 180)) * SIZE * 2);
define("SQUARESIZE", OCTOGONESIDE / 24);

/*
* Réglages de la taille des flèches de direction moyenne
* (inutile dans la version actuelle car les flèches sont des traits)
*/
define("ARROWSIZE", 5);

/*
* Défini les couleurs corréspondante aux seuils
* 0: 3km/h - 28km/h
* 1: 29km/h - 61km/h
* 2: 62km/h - 400km/h
*/
define("COLORS", [
  "darkgreen",
  "yellow",
  "red"
]);

/*
* Défini les catégories de vents pour les 11 segments
* des triangles.
* Actuellement défini en se basant sur l'échelle Beaufort
*/
define("SPEEDCATEGORIES",[
  3,
  6,
  12,
  20,
  29,
  39,
  50,
  62,
  75,
  89,
  102,
]);


////**** LECTURE DES DONNEES ****////
/*
* Cette partie concerne la récuperation des données de la DB
*/

//Récupère la date à lire
$day = DAYTOREAD;
$hours = HOURSTOREAD;
//Check si les heures ne dépassent pas
if ($hours > 24){
  $hours = 24;
}
//Calcule la derinère mesure à prendre en compte
$ts = strtotime(DAYTOREAD) + $hours * 3600;

//ReConversion en date
$day = date("Y-m-d H:i:s", strtotime(DAYTOREAD));
$dayHours = date("Y-m-d H:i:s", $ts);

//Récupèration les données de vitesse
$query = "SELECT * FROM WindSpeed WHERE TimeStamp >= \"".$day."\" AND TimeStamp < \"".$dayHours."\" ORDER BY TimeStamp";
$repWindSpeed = $bdd->query($query);

//Récupèration les données de directions
$query = "SELECT * FROM WindDirection WHERE TimeStamp >= \"".$day."\" AND TimeStamp <\"".$dayHours."\" ORDER BY TimeStamp";
$repWindDir = $bdd->query($query);

////**** TRAITEMENT DES DONNEES ****////
/*
* Cette partie concerne le traitement des données de la DB afin
* d'en faire des données utilisables pour l'affichage.
*/

/* CREATION DES CLASSES */
/*
* Cette classe sert à mémoriser une donnée par rapport à un temps.
*/
class Data
{
    public $timeStamp;
    public $data;
}

/*
* Cette classe permet d'enregistrer une donnée de vitesse de vent
* ainsi que sa direcion.
*/
class DataWind
{
    public $timeStamp;
    public $speed;
    public $direction;
}

/*
* Cette classe permet de stocker toutes les infos nécessaires
* pour une direction donnée.
* On pourra ensuite dessiner le triangle corréspondant juste à l'aide
* de cette classe.
*/
class DataCardinal
{
  public $name; //Nom de la direction ("N", "NE", etc...)
  public $averageSpeed; //Vitesse moyenne dans cette direction
  public $maxSpeed; //Vitesse max dans cette direction
  public $speedHistory = [24];  //Historique des vitesses max / h dans cette direction

  function __construct($name)
  {
    $this->name = $name;
    //Met tout le tableau des 24h à zéro
    for ($i=0; $i < 24; $i++) {
      $this->speedHistory[$i] = 0;
    }
  }
}

/* INSTANCE DES CLASSES */

//Création des données de directions
$dataDirections = array();

//Création des données de vitesse
$dataSpeeds = array();

//Création du tableau de données général (Vitesse et direction)
$dataWinds = array();

//Création des objets finaux
$dataCardinals = array();

//Creation des instances pour chacune des directions
$dataCardinals[] = new DataCardinal("N");
$dataCardinals[] = new DataCardinal("NE");
$dataCardinals[] = new DataCardinal("E");
$dataCardinals[] = new DataCardinal("SE");
$dataCardinals[] = new DataCardinal("S");
$dataCardinals[] = new DataCardinal("SW");
$dataCardinals[] = new DataCardinal("W");
$dataCardinals[] = new DataCardinal("NW");

/* REMPLISSAGE DES TABLEAUX */
while (($data = $repWindSpeed->fetch()) !== false) {
  $dataSpeed = new Data();

  $dataSpeed->timeStamp = strtotime(($data['TimeStamp'])); //Récupère le timestamp
  $dataSpeed->data = (intval($data['WindSpeed'])); //Récupère la vitesse
  $dataSpeeds[] = $dataSpeed; //Ajout de la donnée au tableau
}

//Remplissage des tableaux de données concernant la direction des vents
while (($data = $repWindDir->fetch()) !== false) {
  $dataDirection = new Data();

  $dataDirection->timeStamp = strtotime(($data['TimeStamp'])); //Récupère le timestamp
  $dataDirection->data = ($data['WindDirection']); //Récupère la vitesse
  $dataDirections[] = $dataDirection; //Ajout de la donnée au tableau
}


/*
* Cette fonction permet de classer deux tableaux de type "DATA"
* de manière chronologique en se basant sur le timestamp.
*
* le résultat retourné est le nouveau tableau composé de tous les
* "DATA" triés.
*/
function sortDataArray($arr1, $arr2){
  $n1 = sizeof($arr1);
  $n2 = sizeof($arr2);

  $i = 0;
  $j = 0;
  $k = 0;

  $arr3 = [];
  
  /*
	Trie les tables tant que l'une et l'autre n'est pas finie (DJ)
  */

  while ($i < $n1 && $j < $n2){
    if ($arr1[$i]->timeStamp < $arr2[$j]->timeStamp){
      $arr3[$k] = $arr1[$i];
      $i++;
    }else{
      $arr3[$k] = $arr2[$j];
      $j++;
    }
    $k++;
  }
  
  /*
	Fini d'insérer la table non terminée
  
  */

  while ($i < $n1){
    $arr3[$k] = $arr1[$i];
    $i++;
    $k++;
  }

  while ($j < $n2){
    $arr3[$k] = $arr2[$j];
    $j++;
    $k++;
  }

  return $arr3;

}

/*
* Cette partie consiste à remplir le tableau des (vents & dir) / temps. Pour ce faire,
* Le système va parcourir toutes les vitesses mesurées et pour chacunes, déterminer
* la direction dans laquelle le vent soufflait.
*
* ex:
* Vitesse:    13:35:10 = 3km/h
*             13:36:30 = 0km/h
*             13:50:15 = 3km/h
*
* Direction:  13:30:00 = "N"
*             13:50:10 = "NE"
*
* Mesures Mixtes:
* 1:
* Vitesse:      3km/h
* Direction:    "N"
* TimeStamp:    13:35:10
*
* 2:
* Vitesse:      0km/h
* Direction:    "N"
* TimeStamp:    13:36:30
*
* 3:
* Vitesse:      3km/h
* Direction:    "NE"
* TimeStamp:    13:50:15
*/
//Parcours toutes les vitesses enregistrées

//Création des variables de mémorisation des états
$speed = null;
$direction = null;
//Tri des tableaux de données dans un grand tableau
$datas = sortDataArray($dataSpeeds, $dataDirections);

for($i = 0; $i < sizeof($datas); $i++){
  //Si la donnée est une vitesse
  if ((gettype($datas[$i]->data)) != "string"){
    $speed = $datas[$i]->data;
  }else{
    $direction = $datas[$i]->data;
  }

  //Si au moins une donnée de vitesse et de direction est disponible
  if (!is_null($speed) && !is_null($direction)){
    //Création de la nouvelle donnée de vent
    $dataWind = new DataWind();
    $dataWind->speed = $speed;
    $dataWind->direction = $direction;
    $dataWind->timeStamp = $datas[$i]->timeStamp;

    //Si la donnée d'avant est arrivée en même temps
    if ((sizeof($dataWinds) > 0) && ($dataWind->timeStamp == $dataWinds[sizeof($dataWinds)-1]->timeStamp)){
        //Remplace l'ancienne par la nouvelle
        $dataWinds[sizeof($dataWinds)-1]=$dataWind;
    }else{
        $dataWinds[] = $dataWind;
    }
  }
}


/*
* Cette partie va servir à transformer les données de "dataWinds"
* en donnée de "dataCardinal" ce qui signifie que le but est d'attribuer
* à chacune des 8 positions cardinales leurs attributs (voir plus haut).
*
* Si je reprends mon exemple plus haut:
*
* 1:
* Vitesse:      3km/h
* Direction:    "N"
* TimeStamp:    13:35:10
*
* 2:
* Vitesse:      0km/h
* Direction:    "N"
* TimeStamp:    13:36:30
*
* 3:
* Vitesse:      3km/h
* Direction:    "NE"
* TimeStamp:    13:50:15
*
* On peut donc en déduire que:
* 1:  Durée:      00:01:20
*     Vitesse:    3km/h
*     Direction:  "N"
*
* 2:  Durée:      00:13:25
*     Vitesse:    0km/h
*     Direction:  "N"
*
* 3:  Durée:      13:50:15 -> maintenant
*     Vitesse:    3km/h
*     Direction:  "NE"
*
* C'est de cette manière que je peux définir la vitesse max ainsi que la moyenne
* pour chacunes des directions.
* Notez que pour le cas de la mesure n° 3, il est impossible de dire avec certitude
* le temps que ce vent à duré. On ne tient donc pas compte de cette mesure.
*/
//Parcours toutes les données mixtes
for ($i = 0; $i < sizeof($dataWinds) - 1; $i++) {
  //Récupere la tranche d'heure 0 à 23
  $hour = (round(date("H", $dataWinds[$i]->timeStamp)));

  //Récupere la durée du vent
  $duration = $dataWinds[$i + 1]->timeStamp - $dataWinds[$i]->timeStamp;

  //Récupere la vitesse du vent
  $speed = $dataWinds[$i]->speed;

  //Récupere la direction du vent
  $direction = $dataWinds[$i]->direction;

  //Test la direction
  switch($direction){
    case "N":
      //Attribue la valeur max du jour
      if ($dataCardinals[0]->maxSpeed < $speed){
        $dataCardinals[0]->maxSpeed = $speed;
      }
      //Attribue la valeur max des vents par heure
      if ($dataCardinals[0]->speedHistory[$hour] < $speed){
        $dataCardinals[0]->speedHistory[$hour] = $speed;
      }
      //Attribue la moyenne des vents par jour
      $dataCardinals[0]->averageSpeed += ($speed / 3.6) * $duration; //(m/s)
      break;
    case "NE":
    //Attribue la valeur max du jour
    if ($dataCardinals[1]->maxSpeed < $speed){
      $dataCardinals[1]->maxSpeed = $speed;
    }
    //Attribue la valeur max des vents par heure
    if ($dataCardinals[1]->speedHistory[$hour] < $speed){
      $dataCardinals[1]->speedHistory[$hour] = $speed;
    }
    //Attribue la moyenne des vents par jour
    $dataCardinals[1]->averageSpeed += ($speed  / 3.6) * $duration; //(m/s)
      break;
    case "E":
    //Attribue la valeur max du jour
    if ($dataCardinals[2]->maxSpeed < $speed){
      $dataCardinals[2]->maxSpeed = $speed;
    }
    //Attribue la valeur max des vents par heure
    if ($dataCardinals[2]->speedHistory[$hour] < $speed){
      $dataCardinals[2]->speedHistory[$hour] = $speed;
    }
    //Attribue la moyenne des vents par jour
    $dataCardinals[2]->averageSpeed += ($speed / 3.6) * $duration; //(m/s)
      break;
    case "SE":
    //Attribue la valeur max du jour
    if ($dataCardinals[3]->maxSpeed < $speed){
      $dataCardinals[3]->maxSpeed = $speed;
    }
    //Attribue la valeur max des vents par heure
    if ($dataCardinals[3]->speedHistory[$hour] < $speed){
      $dataCardinals[3]->speedHistory[$hour] = $speed;
    }
    //Attribue la moyenne des vents par jour
    $dataCardinals[3]->averageSpeed += ($speed / 3.6) * $duration; //(m/s)
      break;
    case "S":
    //Attribue la valeur max du jour
    if ($dataCardinals[4]->maxSpeed < $speed){
      $dataCardinals[4]->maxSpeed = $speed;
    }
    //Attribue la valeur max des vents par heure
    if ($dataCardinals[4]->speedHistory[$hour] < $speed){
      $dataCardinals[4]->speedHistory[$hour] = $speed;
    }
    //Attribue la moyenne des vents par jour
    $dataCardinals[4]->averageSpeed += ($speed / 3.6) * $duration; //(m/s)
      break;
    case "SW":
    //Attribue la valeur max du jour
    if ($dataCardinals[5]->maxSpeed < $speed){
      $dataCardinals[5]->maxSpeed = $speed;
    }
    //Attribue la valeur max des vents par heure
    if ($dataCardinals[5]->speedHistory[$hour] < $speed){
      $dataCardinals[5]->speedHistory[$hour] = $speed;
    }
    //Attribue la moyenne des vents par jour
    $dataCardinals[5]->averageSpeed += ($speed / 3.6) * $duration; //(m/s)
      break;
    case "W":
    //Attribue la valeur max du jour
    if ($dataCardinals[6]->maxSpeed < $speed){
      $dataCardinals[6]->maxSpeed = $speed;
    }
    //Attribue la valeur max des vents par heure
    if ($dataCardinals[6]->speedHistory[$hour] < $speed){
      $dataCardinals[6]->speedHistory[$hour] = $speed;
    }
    //Attribue la moyenne des vents par jour
    $dataCardinals[6]->averageSpeed += ($speed / 3.6) * $duration; //(m/s)
      break;
    case "NW":
    //Attribue la valeur max du jour
    if ($dataCardinals[7]->maxSpeed < $speed){
      $dataCardinals[7]->maxSpeed = $speed;
    }
    //Attribue la valeur max des vents par heure
    if ($dataCardinals[7]->speedHistory[$hour] < $speed){
      $dataCardinals[7]->speedHistory[$hour] = $speed;
    }
    //Attribue la moyenne des vents par jour
    $dataCardinals[7]->averageSpeed += ($speed / 3.6) * $duration; //(m/s);
      break;
  }
}

//La durée total des mesures corréspond au temps écoulé entre la première et la dernière mesure
$totalDuration = $dataWinds[sizeof($dataWinds) - 1]->timeStamp - $dataWinds[0]->timeStamp;

//Defini les moyennes
foreach ($dataCardinals as &$dataCardinal) {
    //Moyenne (m/s)
    $dataCardinal->averageSpeed /= $totalDuration;
    //Transformation en km/h
    $dataCardinal->averageSpeed *= 3.6;
}

////**** AFFICHAGE DES DONNEES SUR LE GRAPHIQUE ****////
/*
* Cette partie concerne le dessin de l'octogone et
* l'incrustation des données mesurées
*/

//Creation d'un canvas
$g = new CanvasGraph(WINDOWSSIZE,WINDOWSSIZE,'auto');
$g->SetMargin(0,0,0,0);

/*
* Background: #222222
*/
$g->InitFrame();
$g->img->SetColor("#222222");
$g->img->FilledRectangle(0, 0, WINDOWSSIZE, WINDOWSSIZE);

//** FONCTIONS UTILES **//

/*
* Cette fonction permet d'avoir un affichage numériques des valeurs moyennes
* et max d'une tranche donnée.
*
* Elle n'est pas indispensable à l'affichage du graphique mais est présente comme
* Supplément.
*
* Pour y accéder depuis le site, il suffit de transmettre par requête GET:
*
* view = "raw"
*
* Notez qu'il est important d'effectuer la requête sur CETTE page et non pas sur le
* site principal.
* ex: http://localhost/siteserver/StationMeteo/windrose.php?dayToRead=10.10.2020&view=raw
*/
function writeRawValues($dataCardinals, $dataWinds){
  echo("<h1>Valeurs des vents</h1>");
  echo("<h3>Date: ".DAYTOREAD."</h3>");
  echo("<h2>Moyennes/Max</h2>");
  echo("<style>
          table, th, td
          {
            border: 1px solid black;
            font-size: 20px;
          }
          #tableRaw th
          {
            cursor: pointer;
          }
          #tableRaw th:hover
          {
            background-color: #000000;
            color: white;
          }
          td, th
          {
            background-color: #F0F0F0;
          }
          h2, h1
          {
            text-decoration: underline;
            margin-bottom: 5px;
          }
          h3
          {
            margin-top: 10px;
            margin-bottom: 10px;
            font-weight: lighter;
            font-size: 20px;
          }
        </style>");

  $table = "<table>
              <tr>
                <th>Direction</th>
                <th>Moyenne (km/h)</th>
                <th>Max (km/h)</th>
              </tr>";
  echo($table);
  for ($i = 0; $i < 8; $i++) {
    echo("<tr>
            <td>".$dataCardinals[$i]->name."</td>
            <td>".round($dataCardinals[$i]->averageSpeed,2)."</td>
            <td>".round($dataCardinals[$i]->maxSpeed)."</td>
          </tr>");
  }
  echo("</table>");

  //Affiche toutes les mesures de vents détaillées
  echo("<h2>Mesures Détaillées</h2>");
  //Affiche nb de mesures, temps total.
  echo("<h3>Mesures:     ".sizeof($dataWinds)."</h3>");
  echo("<h3>Temps Total: ".date("H:i:s",$dataWinds[sizeof($dataWinds) - 1]->timeStamp - $dataWinds[0]->timeStamp)."</h3>");
  //Affiche la table
  $table = "<table id='tableRaw'>
              <tr>
                <th onclick='sortTable(0)'>Direction</th>
                <th onclick='sortTable(1)'>Vitesse (km/h)</th>
                <th onclick='sortTable(2)'>Début</th>
                <th onclick='sortTable(3)'>Fin</th>
                <th onclick='sortTable(4)'>Durée</th>
              </tr>";
  echo($table);
  //Affichage des valeures
  for ($i= sizeof($dataWinds)-2; $i > -1; $i--) {
    echo("<tr>
            <td>".$dataWinds[$i]->direction."</td>
            <td>".round($dataWinds[$i]->speed)."</td>
            <td>".date("H:i:s",$dataWinds[$i]->timeStamp)."</td>
            <td>".date("H:i:s",$dataWinds[$i + 1]->timeStamp)."</td>
            <td>".date("H:i:s",$dataWinds[$i + 1]->timeStamp - $dataWinds[$i]->timeStamp)."</td>
          </tr>");
  }
  $i = sizeof($dataWinds)-1;
  //Affiche la derinère mesure
  echo("<tr>
          <td>".$dataWinds[$i]->direction."</td>
          <td>".round($dataWinds[$i]->speed)."</td>
          <td>".date("H:i:s",$dataWinds[$i]->timeStamp)."</td>
          <td>Undefined</td>
          <td>Undefined</td>
        </tr>");
  echo("</table>");
  echo("<script src='sorter.js'></script>");
}

/*
* Decale des valeurs de la moitié de l'écran
* pour simplifier les calculs
*/
function translate($array){
  for ($i=0; $i < sizeof($array); $i++) {
    $array[$i] += WINDOWSSIZE / 2;
  }
  return $array;
}

/*
* Dessine un simple triangle
*/
function drawTriangle($g, $height, $color){
  //Défini la hauteur du triangle
  $adj = $height;
  //Défini les 3 points du triangle
  $hyp = $adj / cos(22.5 * (3.14 / 180));
  $opp = $hyp * sin(22.5 * (3.14 / 180));

  //Dessine le triangle
  $g->img->SetColor($color);
  $pos = array(0,0 , -$opp, -$adj, $opp, -$adj, 0,0);
  $pos = translate($pos);
  $g->img->Polygon($pos);
}

/*
* Dessine un triangle (plein)
*/
function fillTriangle($g, $height, $color){

  //Défini la hauteur du triangle
  $adj = $height;
  //Défini les 3 points du triangle
  $hyp = $adj / cos(22.5 * (3.14 / 180));
  $opp = $hyp * sin(22.5 * (3.14 / 180));

  //Dessine le triangle
  $g->img->SetColor($color);
  $pos = array(0,0 , -$opp, -$adj, $opp, -$adj, 0,0);
  $pos = translate($pos);
  $g->img->FilledPolygon($pos);
}

/*
* Dessine l'historique des carrés au dessus du triangle
*/
function drawSquares($g, $arraySquares){

  /*
  * Défini la position du carré du premier carré de gauche.
  * topLeft:      la coordonée x,y de l'angle haut gauche du carré
  * bottomRight:  la coordonée x,y de l'angle bas droite du carré
  */
  $topLeft = [WINDOWSSIZE / 2 - OCTOGONESIDE / 2, (WINDOWSSIZE / 2 - SIZE)];
  $bottomRight = [$topLeft[0] + SQUARESIZE, $topLeft[1] - SQUARESIZE];

  //Dessine les 24 carrés
  for ($i=0; $i < 24; $i++) {
    //Commence par définir la couleur du carré
    //Vert
    if ($arraySquares[$i] >= 3 && $arraySquares[$i] <= 28){
        $g->img->SetColor(COLORS[0]);
    }
    //Jaune
    else if ($arraySquares[$i] >= 29 && $arraySquares[$i] <= 61){
        $g->img->SetColor(COLORS[1]);
    }
    //Rouge
    else if ($arraySquares[$i] >= 62 && $arraySquares[$i] <= 400){
        $g->img->SetColor(COLORS[2]);
    }else{
        $g->img->SetColor("#555555");
    }
    //Dessine le carré
    $g->img->FilledRectangle($topLeft[0], $topLeft[1], $bottomRight[0], $bottomRight[1]);

    //Dessine le contour blanc toute les 6h
    if ($i % 6 == 0 && $i > 0){
      $g->img->SetColor("#FFFFFF");
    }else {
      $g->img->SetColor("black");
    }
    $g->img->Rectangle($topLeft[0], $topLeft[1], $bottomRight[0], $bottomRight[1]);

    $topLeft[0] += SQUARESIZE;
    $bottomRight[0] += SQUARESIZE;
  }
}

/*
* Fonction qui dessine une flèche indiquant la vitesse moyenne du vent
*/
function drawArrow($g, $height){
  //Défini la  hauteur de la flèche
  $topPos = [WINDOWSSIZE / 2, WINDOWSSIZE / 2 - $height];
  //Dessine la flèche
  $g->img->FilledRectangle(WINDOWSSIZE / 2 - 2, WINDOWSSIZE / 2, $topPos[0] + 2, $topPos[1]);
}


/*
* Fonction qui dessine un triangle complet avec les graduations
*/
function drawWrTriangle($g, $data){
  //Ecrit le text du point cardinal
  $txt=$data->name;
  $t = new Text($txt,300,SIZE / 2);
  $t->SetFont(FF_FONT2,FS_BOLD,10);
  $t->SetPos(WINDOWSSIZE / 2, 15);
  $t->SetColor("#AAAAAA");

  $t->Stroke($g->img);

  //Dessine tous les triangles
  //Défini l'espace entre les graduations
  $space = SIZE / 11;

  //Commence par dessiner les 11 triangles (segments)
  for ($i=10; $i >= 0; $i--) {
    if ($data->maxSpeed >= SPEEDCATEGORIES[$i]){
      if ($i <= 3){
        fillTriangle($g, ($i + 1) * $space, COLORS[0]);
      }else if ($i <= 6){
        fillTriangle($g, ($i + 1) * $space, COLORS[1]);
      }else if ($i < 12){
        fillTriangle($g, ($i + 1) * $space, COLORS[2]);
      }
    }
  }

  //Dessine les triangles noirs afin de percevoir les différents ségments
  for ($i=1; $i < 12; $i++) {
    drawTriangle($g, $i * $space, "#000000");
    drawSquares($g, $data->speedHistory);
  }

  //Dessine la flèche au bon endroit
  for ($i = 0; $data->averageSpeed >= SPEEDCATEGORIES[$i]; $i++){

  }

  drawArrow($g, $i * $space);
}

//Dessine l'octogone
for ($i=1; $i < 9; $i++) {
  drawWrTriangle($g, $dataCardinals[$i - 1]);
  $g->img->SetAngle($i * 45);
}

//On a la possibilitée d'avoir les infos en litéral.
if (isset($_GET['view'])){
  if ($_GET['view'] == "raw"){
    writeRawValues($dataCardinals, $dataWinds);
  }else{
    $g->Stroke();
  }
}else{
  $g->Stroke();
}
?>
