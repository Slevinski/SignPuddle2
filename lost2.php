<?php
$rSL = 0;
include 'styleA.php';
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">';
echo '<HTML>';
echo '<HEAD>';
echo '<LINK REL=STYLESHEET HREF="standard.css" TYPE="text/css">';
echo '<META http-equiv=Content-Type content="text/html; charset=utf-8">';
echo '<title>SignPuddle 2.0 ' . $subHead . '</title>';

echo '</head>';
echo '<body>';

include 'header.php';

if ($sgn){
  echo "<h1>Signs with images but without symbols</h1>";
  include $sgndir . ".sym.php";
  $idlist = array();
//find signs image
  $d = dir($sgndir);
  while ($Entry = $d->Read())
  {
    if (!(($Entry == "..") || ($Entry == ".")))
    {
      $ext=strtolower(substr($Entry,strrpos($Entry,".")+1));
      if ($ext=='php') {
        $id=strtolower(substr($Entry,0,strrpos($Entry,".")));
        $idlist[$id]=1;
      }
    }
  }

  foreach($symbols as $sb){
    foreach($sb as $sf){
      foreach($sf as $sr){
        foreach($sr as $id){
          $idlist[$id]=0;
        }
      }
    }
  }

  $idfinal = array();
  foreach ($idlist as $id=>$c){
    if ($c) {
      foreach ($imgExt as $ext){
        $file =$sgndir . '/' . $id . '.' . $ext;
      if (file_exists($file)){
          $idfinal[] = $id;
        }
      }
    }
  }


  foreach ($idfinal as $id){
    echo '<a href="canvas.php?sid=' . $id . '">' . $id . '</a><br>';  
  }

}
include 'footer.php';
?>
