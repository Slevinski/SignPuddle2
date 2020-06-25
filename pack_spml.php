<?php

//need packing functions...
//check data of packng

/*******
 * Init
**/
$type = @$_REQUEST['type'];
$id = @$_REQUEST['id'];
if ($type=='' and $id==''){
  $type='ui';
  $id=1;
}
include 'spml.php';
$filename = pack_spml($type,$id);


$d = dir('data/' . $type);
$ids = array();
while ($Entry = $d->Read()) {
  if (($Entry == "..") or ($Entry == ".")) continue;
  if (is_dir('data/' . $type . '/' . $Entry)) {
    $ids[] = 0+ $Entry;
  }
}

sort($ids);
$next = -1;
foreach ($ids as $i=>$pid){
  if ($next>=0){
    $next=$i;
    break;
    echo "never echoes";
  }
  if ($id ==$pid){
    $next = $i;
  }
}
if($id == $ids[$next]) {
  if ($type=='ui'){
    $type='sgn';
    $id = 1;
  } else {
    echo "Done!";
    die();
  }
} else {
  $id = $ids[$next];
}

  echo '<html><head>';
  echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=pack_spml.php?type=' . $type . '&id=' . $id;
  echo '">' . "\n";

  echo '</head><body>';
  echo '<a href="' . $filename . '">Puddle in SPML</a>';
  
  echo '<p>Next is type ' . $type . ', id ' . $id;
  echo '</body></html>';
  die();
?>