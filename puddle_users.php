<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'styleA.php';

if ($_REQUEST['key']=='admin_only') {
  header("Content-Type: text/plain; charset=utf-8");
  header('Content-Disposition: filename=puddleuser.txt');

  $adms = glob('data/adm/*.adm.php');
  foreach ($adms as $adm){
    $parts = explode('.',$adm);
    $sub = explode('/',$parts[0]);
    include ($adm);
    foreach ($localusers as $name=>$user){
      if ($name){
        echo $sub[2] . "\t" . $name . "\t" . $user['security'] . "\n";
      }
    }
  }
}

?>
