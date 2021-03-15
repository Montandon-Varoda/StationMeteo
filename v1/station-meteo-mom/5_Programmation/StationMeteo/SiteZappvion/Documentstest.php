<!DOCTYPE HTML>
<html>
  <head>
    <title>Zappvion - Documents</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="icon.png" />
  </head>
    <body>
      <?php
        $thelist = "";
        if ($handle = opendir('test')) {
          while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
              if(strpos($file, '.pdf') !== false){
                //Image du document
                if (!file_exists("test/img/".$file.".png")){
                  $cmd = "gs -o 'test/img/{$file}.png' -sDEVICE=pngalpha -dLastPage=1 'test/{$file}' > /dev/null";
                  #shell_exec("convert {$file}[0] img/{$file}.png");
                  system($cmd);
                }
                $thelist .= '<a class="fileButton" target="_blank" href="test/'.$file.'"><img class="pdfImg" src="test/img/'.$file.'.png" height="80"><p class="fileName">'.chop($file,".pdf").'</p></a><br/>';
              }
            }
          }
          closedir($handle);
        }
      ?>
    <h1>Documents:</h1>
    <ul><?php echo $thelist; ?></ul>

  </body>
</html>
