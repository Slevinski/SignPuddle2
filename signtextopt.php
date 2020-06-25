<?php
$rSL = 1;
include 'styleA.php';
include 'styleB.php';
//$subHead=displayEntry(9,'t',"ui");
$subHead='SignText Options';
include 'header.php';

if(!$_REQUEST['sgntxt']){
  parse_str($GLOBALS['QUERY_STRING'],$qsArray);
  $_REQUEST['sgntxt']=$qsArray['sgntxt'];
}
$sgntxt = $_REQUEST['sgntxt'];
$list = $_REQUEST['list'];
//$list = str_replace("\n","%0D%0A" ,$list);
//$list = str_replace("%0D%0A%0D%0A","%0D%0A" ,$list);
$list=str_replace("\r","",$list);
if ($list){
  $sgntxt= lst2ksw($list);
}
/**
 * Part 2, display the KSW with options...
 */
if ($sgntxt){
  echo '<br clear="all"><br>';
  stOptions($sgntxt);
  stDisplay($sgntxt);
}

include 'footer.php'; 
?>
