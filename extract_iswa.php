<?php
$tmp = "data/tmp/spreader-" . time() . ".json";
put_file_contents($tmp,$json);

include 'styleA.php';
include 'library/zip/pclzip.lib.php';

//$type=$_REQUEST['type'];
//$id=$_REQUEST['id'];
if ($ui and $sgn){
  $type='sgn';
  $id = $sgn;
} else {
  $type='ui';
  $id=$sgn;
}
$dir = 'data/' . $type . '/' . $id;
if (!is_dir($dir) || $type=='' || $id==''){
  die ('Can not zip ' . $type . '/' . $id);
}
$zipfile = $type . '.' . $id . '.zip';
$zipDir = 'data/tmp/';
$zipfull = $zipDir . $zipfile;
unlink($zipfull);

$archive = new PclZip($zipfull);

//add main files
$files=array();
foreach (glob($dir . '.*') as $file){
  if (strpos($file,'.adm.php')) continue;//no longer needed as it moved
  $files[]=$file;
}

//add main data
$files[]=$dir;

$v_list = $archive->add($files, PCLZIP_OPT_REMOVE_PATH, 'data/' . $type . '/');

if ($v_list == 0) {
  echo '<h2>Error</h2>' . $archive->errorInfo(true);
} else {
  $filename = $data . '/tmp/' . $zipfile;;
  header("Content-Length: " . filesize($filename));
  header('Content-Type: application/zip');
  header('Content-Disposition: attachment; filename=' . $zipfile);
  readfile($filename);
  die();

  echo '<a href="' . $zipfull . '">' . $zipfile . '</a>';
}
//include 'footer.php';
?>
