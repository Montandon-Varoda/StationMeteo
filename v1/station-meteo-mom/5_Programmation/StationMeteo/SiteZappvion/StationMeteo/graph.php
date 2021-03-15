<?php
  include ("src/jpgraph.php");
  include ("src/jpgraph_line.php");
  include ("bdd.php");

  //Défini la tranche à lire
  //Si une configuration est envoyée par le client
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


  #Récupération des valeurs envoyées
  $color = $_GET['color']; #Donner la couleur en hex sans le #
  $value = $_GET['value'];

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

  #Récupération des valeurs des dernières 24h
  $query = "SELECT * FROM TempHumRain WHERE TimeStamp >= \"".$day."\" AND TimeStamp < \"".$dayHours."\";";
  $rep = $bdd->query($query);

  #Memorise la dernière heure affichée
  $lasttime = 100;
  $i = 0;
  #Création des listes pour le graphique (ydata=valeur mesurée et xdata=heure)
  while (($data = $rep->fetch())!== false) {

    #Si l'heure une heure pile et différentre de l'ancienne, l'ajouter à la liste
    $time = date("H", strtotime($data['TimeStamp']));
    if ($lasttime != $time){
      $lasttime = $time;
      $xdata[] = $time."h";
    }else{
      $xdata[] = "";
    }
    #Ajout de la valeur mesurée
    $ydata[] = $data[$value];
  }
  $rep->closeCursor();

  #Création du graphique
  $graph = new Graph(1200,300,"auto");
  $graph->SetScale("textlin");
  $graph->img->SetColor("#222222");
  $graph->img->FilledRectangle(0, 0, 1200, 300);


  #Position du graphique dans l'image
  $graph->img->SetMargin(45,30,35,35);


  #Police d'écriture
  $graph->xaxis->SetFont(FF_FONT1,FS_BOLD);
  $graph->xaxis->SetColor("#CCCCCC");
  $graph->xgrid->SetLineStyle("solid");

  $graph->yaxis->HideZeroLabel();
  $graph->yaxis->HideLine(false);
  $graph->yaxis->HideTicks(false,false);
  $graph->yaxis->SetColor("#CCCCCC");
  #Afficher les heures sur l'axe des x
  $graph->xaxis->SetTickLabels($xdata);

  $graph->img->SetAntiAliasing(false);

  #Création de la ligne avec les valeurs mesurées
  $lineplot = new LinePlot($ydata);

  #Ajout de la ligne au graphique
  $graph->Add($lineplot);

  #Définition de la couleur de la ligne
  $lineplot->SetColor("#".$color);
  $lineplot->SetWeight(2);
  $lineplot->SetStyle("solid");

  #Afficher les lignes verticales
  $graph->xgrid->Show(true);
  $graph->ygrid->Show(true);

  #Couleur des lignes verticales
  $graph->xgrid->SetColor("#666666");

  #Couleur des lignes horizontales
  $graph->ygrid->SetColor("#666666");
  $graph->ygrid->SetFill(true, "#222222", "#444444");

  #Afficher l'image du graphique
  $graph->Stroke();
?>
