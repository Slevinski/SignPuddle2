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
  echo "<h1>Signs without terms</h1>"; 
  include $sgndir . ".trm.php";
  $cTerms = count($terms);
  $idlist = array();

  $d = dir($sgndir);
  while ($Entry = $d->Read())
  {
    if (!(($Entry == "..") || ($Entry == ".")))
    {
      $ext=strtolower(substr($Entry,strrpos($Entry,".")+1));
      if ($ext=='php')
      {
        $id=strtolower(substr($Entry,0,strrpos($Entry,".")));
        $idlist[$id]=1;
      }
    }
  }


  foreach($terms as $ids){
    foreach($ids as $id){
      $idlist[$id]=0;
    }
  }

 foreach ($idlist as $id=>$c){
  if ($c) {
   echo '<a href="canvas.php?sid=' . $id . '">' . $id . '</a><br>';  
  }
 }
}
include 'footer.php';
?>
