<?php
include 'styleA.php';
if ($ui and $sgn){
  $type='sgn';
  $pid = $sgn;
} else {
  $type='ui';
  if ($ui) {
    $pid=$ui;
  } else if ($sgn) {
    $type='ui';
    $pid=$sgn;
  }
}



//  include "db.php";
//  include 'fsw.php';
  $input = file_get_contents('puddle_head.txt');
  $lines = explode("\n",$input);
  $pudlist = array();
  foreach ($lines as $line){
    $parts = explode("\t",$line);
    if (count($parts) >=4){
      $code = $parts[0];
      $pudlist[$code]=$parts;
    }
  }

  $parts = $pudlist[$type . $pid];
  $pos = $parts[4];

  $adm = 'data/adm/' . $type . $pid . '.adm.php';
  if (file_exists($adm)){ 
    include ($adm);
  } else {
    $view=0;
    $add=1;
    $edit=1;
    $register=1;
    $upload=4;
  }
  $file = 'data/spml/' . $type . $pid . '.spml';
  if (file_exists($file)){
    header("Content-Type: text/plain; charset=utf-8");
    header('Content-Disposition: filename=' . $type . $pid . '.head');
    $xml = simplexml_load_file($file);
    if ($xml->entry->count()) {
      $attr = $xml->attributes();
      $cdt = date('Y-m-d H:i:s',intval($attr['cdt']));
      $qqq = 'puddle_' . $type . $pid;
      echo "$type$pid\t" . $parts[1] . "\t" . $parts[2] . "\t" . $parts[3] . "\t$qqq\t" . $xml->term . "\t" . $xml->png . "\t" . $pos . "\tadmin\t$cdt\t$view\t$add\t$edit\t$register\t$upload\n";
    }
  }


die();



    $xml = read_spml($type,$pid);
    foreach($xml->children() as $entry) {
      $output = array();
      $output['term'] = array();
      $output['text'] = array();
      $arr = $entry->attributes();
      $name = $entry->getName();
      switch($name){
        case "entry":
          break;
        case "png":
        case "svg":
//          $outfile =  $data . '/' . $type . '/' . $id . '.' . $name;
//          file_put_contents($outfile,base64_decode($item));
          echo "# image" . "\n";
          break;
        default:
          echo "#" . $name . "\t" . $entry . "\n";
        }
      }


die();

      $terms = array();
      $signs = array();
      $arr = $entry->attributes();
      $id = $arr['id'];
      foreach($entry->children() as $item) {
        if ($item->getName() == 'term'){
          if (fswText($item)){
            $signs[] = $item;
          } else {
            $terms[] = str_replace('"',"'",$item);
          }
        }
        echo $arr['id'] . "\t" . $arr['cdt'] . "\t" . $arr['mdt'] . "\t" . json_encode($entry->term) . "\n";
      }
?>

