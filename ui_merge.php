<?php

//get main ui list of elements...
$iDir = 'data/ui/1';
if (!is_dir($iDir)){
  die ('Does not exists ' . $iDir);
}

$d = dir($iDir);
$ids=array();

//scan id and copy PNGs if needed
while ($Entry = $d->Read()){
  if (!(($Entry == "..") || ($Entry == "."))){
    $pos=strrpos($Entry,".");
    $ext=strtolower(substr($Entry,$pos+1));
    $sid=substr($Entry,0,$pos);
    if ($ext=='xml'){
      if (strval(intval($sid))==$sid){
        $ids[]=$sid;
      }
    }
  }
}
$d->close();

//get list of other UIs
$iDir = 'data/ui';
$d = dir($iDir);
$uis=array();

//scan id and copy PNGs if needed
while ($Entry = $d->Read()){
  if (!(($Entry == "..") || ($Entry == "."))){
    if (is_dir($iDir . '/' . $Entry)){
      if (strval(intval($Entry))==$Entry && $Entry!='1'){
        $uis[]=$Entry;
      }
    }
  }
}
$d->close();


//now list uis
foreach ($uis as $ui){
  //unlink sym, trm, spml
  @unlink('data/ui/' . $ui . '.sym.php');
  @unlink('data/ui/' . $ui . '.trm.php');
  @unlink('data/spml/ui' . $ui . '.spml');
  foreach ($ids as $id){
    $ifile = 'data/ui/1/' . $id . '.xml';
    $ofile = 'data/ui/' . $ui . '/' . $id . '.xml';
    if(!file_exists($ofile)) {
      copy($ifile,$ofile);
      $ifile = 'data/ui/1/' . $id . '.png';
      $ofile = 'data/ui/' . $ui . '/' . $id . '.png';
      if(file_exists($ifile)) {
        copy($ifile,$ofile);
      }
    }
  }
}
?>
