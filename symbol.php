<?php
ini_set('session.save_path','data/tmp');
ini_set('session.gc_maxlifetime', 86400);
session_start();
include 'bsw.php';
include 'spl.php';
include 'image.php';
$sss = @$_REQUEST['sss'];
$color = @$_REQUEST['color'];
$size = @$_REQUEST['size'];
$colorize = @$_REQUEST['colorize'];
//if ($colorize==1){$color=-1;}
$ver = 1;
if (@$_SESSION['spcolor']) {
  $ver=2;
  $color='';
}
$key = id2key($sss);
header("Content-type: image/png");
header('Content-Disposition: filename=' . $sss . '.png');
ImagePNG(glyph_png($key,$ver,$size,$color, '', '', $colorize));//error_log
?>
