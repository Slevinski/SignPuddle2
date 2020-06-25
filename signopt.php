<?php
$rSL = 1;
include 'styleA.php';
include 'styleB.php';
//$subHead=displayEntry(9,'t',"ui");
$subHead='Sign Options';
include 'header.php';

$ksw = trim($_REQUEST['ksw']);
$bld = $_REQUEST['build'];
if ($bld){
  $ksw= bld2ksw($bld);
}
if ($ksw){
  echo '<br clear="all"><br>';
  sgnOptions($ksw);
  stDisplay($ksw);
}

include 'footer.php'; 
?>
