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
  echo "<h1>Signs with a spelling but without a sequence</h1>";
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
        $idlist[]=$id;
      }
    }
  }

  $idout = array();
  foreach($idlist as $id){
//echo $id . "<br>";
    $sign = readSign($id);
    if ($sign['bld']<>"" and $sign['seq']=="") $idout[]=$id;
    if (count($idout)==100) break;
  }

  if (count($idout)==100) echo "first 100<br><br>";
  foreach ($idout as $id){
    echo '<a href="canvas.php?sid=' . $id . '">' . $id . '</a><br>';  
  }

}
include 'footer.php';
?>
