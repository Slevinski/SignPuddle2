<?php
  $d = dir("iswa/pack");
  $sss = array();
  $keyC = array();
  $keyS = array();
  $c = 0;
  $s = 0;
  while (false !== ($entry = $d->read())) { 
  if ($entry<>"." and $entry<>".."){
    $idC = substr($entry,0,5);
    $idS = substr($entry,6,6);
    $idF = substr($entry,13,2);
    $sss[$idC][$idS][$idF]++;
    if (count($sss[$idC])==1 and $keyC[$idC]===null){
      $keyC[$idC]=$c;$c++;$s=0;
    }
    if (count($sss[$idC][$idS])==1 and $keyS[$idC.$idS]===null) 
      {$keyS[$idC.$idS]=$s;$s++;}
  }
  }
  
  // output category group array
  echo "//Categories and groups\n";
  echo "keys = new Array(" . count($sss) . ")\n\n";

  //output symbol variation array dimension
  echo "//Groups\n";
  foreach ($sss as $Cat => $sssSym){
    echo "keys[" . $keyC[$Cat] . "] = new Array(" . count($sssSym) . ")\n";
  }
  echo "\n";

  //output detail array information
  foreach ($sss as $Cat => $sssSym){
    foreach ($sssSym as $Sym => $sssRot){
      echo "keys[" . $keyC[$Cat] . "][" . $keyS[$Cat.$Sym] . "] = \"$Cat-$Sym\"\n";  
    }
  }
  
?>
