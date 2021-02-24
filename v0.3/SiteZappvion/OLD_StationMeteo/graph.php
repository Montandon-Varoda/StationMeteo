<?php
  include ("src/jpgraph.php");
  include ("src/jpgraph_line.php");
  include ("bdd.php");

  #Récupération des valeurs envoyées
  $color = $_GET['color']; #Donner la couleur en hex sans le #
  $value = $_GET['value'];

  #Récupération du dernier ID enregistré
  $query = 'SELECT MAX(ID) FROM StationMeteo;';
  $rep = $bdd->query($query);
  $data = $rep->fetch();
  #-144 permet de sélectionner les valeurs des dernières 24h (6/h * 24h = 144)
  $lastID = $data[0]-144;

  #Récupération des valeurs des dernières 24h
  $query = 'SELECT Heure,'.$value.' FROM StationMeteo WHERE ID >= '.$lastID.';';
  $rep = $bdd->query($query);

  #Création des listes pour le graphique (ydata=valeur mesurée et xdata=heure)
  $time = 0;
  while ( ($data = $rep->fetch())!== false) {
    if((substr($data['Heure'], 0, 2) != $time) && (substr($data['Heure'], 3, 2) == 0)){
      #Si l'heure une heure pile et différentre de l'ancienne, l'ajouter à la liste
      $xdata[] = substr($data['Heure'], 0, 5);
      #Mise à jour de la variable d'heure
      $time = substr($data['Heure'], 0, 2);
    }else {
      #Si l'heure est autre qu'une heure pile, ne pas l'afficher
      $xdata[] = '';
    }
      #Ajout de la valeur mesurée
      $ydata[] = $data[$value];
  }
  $rep->closeCursor();

  #Création du graphique
  $graph = new Graph(1200,300,"auto");
  $graph->SetScale("textlin");

  #Position du graphique dans l'image
  $graph->img->SetMargin(45,30,35,35);

  #Police d'écriture
  $graph->xaxis->SetFont(FF_FONT1,FS_BOLD);

  #Afficher les heures sur l'axe des x
  $graph->xaxis->SetTickLabels($xdata);

  $graph->img->SetAntiAliasing(false);

  #Création de la ligne avec les valeurs mesurées
  $lineplot = new LinePlot($ydata);

  #Ajout de la ligne au graphique
  $graph->Add($lineplot);

  #Définition de la couleur de la ligne
  $lineplot->SetColor("#".$color);

  #Afficher les lignes verticales
  $graph->xgrid->Show( true);

  #Couleur des lignes verticales
  $graph->xgrid->SetColor("gray@0.7");

  #Afficher l'image du graphique
  $graph->Stroke();
?>
