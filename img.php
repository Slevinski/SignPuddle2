<?php
// prepare environment
error_reporting(0);
include("styleA.php");
$build = $_REQUEST['build'];
$color = $_REQUEST['color'];
$background = $_REQUEST['background'];
$transparent = $_REQUEST['transparent'];
if ($transparent){$background="";}
$lefthandcolor = $_REQUEST['lefthandcolor'];
$righthandcolor = $_REQUEST['righthandcolor'];
$colorize = $_REQUEST['colorize'];
$pixels=$_REQUEST['pixels'];
$bounding=$_REQUEST['bounding']; //t=tight
if (!$bounding){$bounding='t';}
if ($_SESSION['spcolor']){$color=$_SESSION['spcolor'];}
$size = $_REQUEST['size'];
$name=$_REQUEST['sid'];

header("Content-type: image/png");
header('Content-Disposition: filename=sign.png');
require('library/sps/signclass.php');
$sid = new SIGN($build,$size,$background,$pixels,$bounding);
if ($color){
  $sid->SetColor($color);
}
if ($colorize){
  $sid->Colorize();
}
if ($lefthandcolor){
  $sid->SetColorLeftHand($lefthandcolor);
}
if ($righthandcolor){
  $sid->SetColorRightHand($righthandcolor);
}
$sid->Build();
imagepng($sid->im);
$sid->Close();
?>
