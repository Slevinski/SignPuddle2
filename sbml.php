<?php
include("global.php");

//print xml header
$newline = "\n";
$left = "&lt;";
$right= "&gt;";
$sp = "&nbsp;&nbsp;";
echo $left ."?xml version=\"1.0\"?" . $right . "<br>";
echo $left . "swml dialect=\"S\" version=\"1.1\" lang=\"sgn\" glosslang=\"\"" . $right . "<br>";

//prepare arrays to analyze build files
//$symbols = array();
//$xs = array();
//$ys = array();

//select bld files
$directory = $dict . "/sl";
$d = dir($directory);
$Count=0;
while ($Entry = $d->Read())
{
  if (!(($Entry == "..") || ($Entry == ".")))
  {
    $pos=strrpos($Entry,".");
    $ext=strtolower(substr($Entry,$pos+1));
    $sign=substr($Entry,0,$pos);
    if ($ext=="bld")
    {
      //sign header
      echo $sp;
      echo $left . "sign gloss=\"" . $sign . "\"" . $right . "<br>";
      echo $sp . $sp;
      echo $left . "gloss". $right .  $sign . $left . "/gloss" . $right . "<br>";

      //analyze build file
      $filename = $directory . "/" . $Entry;
      $lines = file($filename);
      $build = $lines[count($lines)-1];
//echo $filename;
      // split build variable into symbol,x,y
      $build=split(",",$build);
      $cnt = count($build);
      $cnt = $cnt - ($cnt%3); 
      for ($i=0;$i<$cnt;$i++){
        $symbol=$build[$i];
        $i++;
        $x=$build[$i];
        $i++;
        $y=$build[$i];
        echo $sp . $sp;
        echo $left . "symbol x=\"" . $x . "\" y=\"" . $y . "\"" . $right . $symbol . $left . "/symbol" . $right . "<br>";
  
      }
      echo $sp;
      echo $left . "/sign" . $right . "<br>";
    }
  }
}

//end swml
echo $left . "/swml" . $right;

?>

