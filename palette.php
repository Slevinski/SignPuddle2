<?php
$base = $_REQUEST['base'];
if (!$base) $base='100';
$prev = dechex(hexdec($base)-1);
$next = dechex(hexdec($base)+1);
$font= $_REQUEST['font'];
if (!$font) $font='png';
$size = $_REQUEST['size'];
if (!$size) $size=40;

echo '<p><a href="palette.php?base=' . $prev . '&font=' . $font. '&size=' . $size .'">Previous</a>';
echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="palette.php?base=' . $next . '&font=' . $font. '&size=' . $size .'">Next</a>';
echo '<p>';

$ksw = 'M' . ($size*6) . 'x' . ($size*16);
for ($f=0;$f<6;$f++){
  for ($r=0;$r<16;$r++){
    $key = $base . $f . dechex($r);
    $ksw.='S' . $key . ($size*$f) . 'x' . ($size*$r);
  }
}
if ($font=='png'){
  echo '<img src="glyphogram.php?font=' . $font. '&ksw=' . $ksw . '">';
} else if ($font=='svg'){
  echo $pre . '<embed type="image/svg+xml" width="' . $wsvg . '" ';
  echo 'height="' . $hsvg . '" src="glyphogram.php?font=' . $font. '&';
  echo 'text=' . $ksw . '" ';
  echo 'pluginspage="http://www.adobe.com/svg/viewer/install/" style="overflow:hidden">';
  echo '</embed>';
}
?>
