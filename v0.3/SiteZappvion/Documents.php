<!DOCTYPE HTML>
<html>
  <head>
    <title>Zappvion - Documents</title>
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
      echo '<link rel="stylesheet" href="css/documentsMobile.css"/>';
    }else{
      echo '<link rel="stylesheet" href="css/documents.css"/>';
    }
    ?>
  </head>
    <body>
      <?php
        $affichage = "";
        if ($handle = opendir('Documents')) {
          while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
              if(strpos($file, '.pdf') !== false){
                //Image du document
                if (!file_exists("Documents/img/".$file.".png")){
                  /*$im = new Imagick($_SERVER["DOCUMENT_ROOT"]."/Documents/".$file);
                  $im->setIteratorIndex(0);
                  $im->setCompression(Imagick::COMPRESSION_LZW);
                  $im->setCompressionQuality(90);
                  $im->writeImage($_SERVER["DOCUMENT_ROOT"]."/Documents/img/".$file.".png");
                  $cmd = "gs -o 'Documents/img/{$file}.png' -sDEVICE=pngalpha -dLastPage=1 'Documents/{$file}'";
                  shell_exec($cmd);*/
                }
                $affichage .= '<a class="fileButton" target="_blank" href="Documents/'.$file.'"><img class="pdfImg" src="Documents/img/'.$file.'.png" height="80"><p class="fileName">'.chop($file,".pdf").'</p></a><br/>';
              }
            }
          }
          closedir($handle);
        }
      ?>
    <h1>Documents:</h1>
    <a href="index.php">
      <button id="back" class="backbutton">
        <img class="backimg" src="Icones/back.png" height="40">
        <p class="backText">Retour</p>
      </button>
    </a>
    <ul><?php echo $affichage; ?></ul>
  </body>
</html>
