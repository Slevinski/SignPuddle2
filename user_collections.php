<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'styleA.php';

if ($_REQUEST['key']=='admin_only') {

// load puddle_head source
  $input = file_get_contents('puddle_list.txt');
  $lines = explode("\n",$input);
  $pudlist = array();
  foreach ($lines as $line){
    $parts = explode("\t",$line);
    if (count($parts) >=3){
      $collection = $parts[0];
      $pudl = $parts[1];
      $pudlist[$pudl]=$collection;
    }
  }



  header("Content-Type: text/plain; charset=utf-8");
  header('Content-Disposition: filename=user_collections.txt');

  $adms = glob('data/adm/*.adm.php');
  foreach ($adms as $adm){
    $parts = explode('.',$adm);
    $sub = explode('/',$parts[0]);
    include ($adm);
    foreach ($localusers as $name=>$user){
      if ($name){
        $pudl = $sub[2];
        if (array_key_exists($pudl,$pudlist)){
          echo $pudlist[$pudl] . "\t" . $name . "\t" . $user['security'] . "\n";
        }
      }
    }
  }
}

?>
