<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'styleA.php';

function Timestamp($time = "now"){
  $dt = new DateTime($time);
  $dt->setTimezone(new DateTimeZone('UTC'));
  //try {
  //  return $dt->format('Y-m-d\TH:i:s.v\Z');
  //} catch (Exception $e){
    return $dt->format('Y-m-d\TH:i:s\Z');
  //}
}

// load puddle_head source
  $input = file_get_contents('puddle_list.txt');
  $lines = explode("\n",$input);
  $pudlist = array();
  $pudname = array();
  foreach ($lines as $line){
    $parts = explode("\t",$line);
    if (count($parts) >=3){
      $collection = $parts[0];
      $pudl = $parts[1];
      $name = $parts[2];
      $pudlist[$pudl]=$collection;
      $pudname[$pudl]=$name;
    }
  }



  header("Content-Type: text/plain; charset=utf-8");
  header('Content-Disposition: filename=user_collections.txt');
  $adms = glob('data/adm/*.adm.php');
  foreach ($adms as $adm){
    $parts = explode('.',$adm);
    $sub = explode('/',$parts[0]);
    include ($adm);
    if (array_key_exists($sub[2],$pudlist)){
      $spml = 'data/sgn/' . substr($sub[2],3) . ".spml";
      $xml = simplexml_load_file($spml);
      $attr= $xml->attributes();
      if ($attr['cdt']>0) {
        $cdt = Timestamp(date('Y-m-d H:i:s',intval($attr['cdt'])));
      } else {
        $cdt = Timestamp();
      }
      $mdt = Timestamp();
      echo $pudlist[$sub[2]] . "\t" . $sub[2] . "\t" . $pudname[$sub[2]] . "\tadmin\t" . $cdt . "\t" . $mdt . "\t" . $view . "\t" . $add . "\t" . $edit . "\t" . $register . "\t" . $upload . "\n";
    }
  }
?>
