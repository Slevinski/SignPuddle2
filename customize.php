<?php
set_time_limit(60);
$rSL = 1;
include 'styleA.php';
include 'image.php';

//failed, maybe suhosin?
//ini_set("memory_limit","12M");

//get variables
$Recreate  = $_REQUEST['Recreate'];
$PHP_SELF = $_SERVER['PHP_SELF'];
$size = @$_REQUEST['size'];
if (!$size) $size=1;
$pad=@$_REQUEST['pad'];
if (!$pad) $pad=0;
$bound=@$_REQUEST['bound'];
if (!$bound) $bound='t';
$color= $_REQUEST['color'];
$colorize = @$_REQUEST['colorize'];
$fill = @$_REQUEST['fill'];
$background = $_REQUEST['background'];
$transparent = $_REQUEST['transparent'];
if ($transparent){$background="-1";}

$font= $_REQUEST['font'];
if($font==""){
  $font='png1';
}

$ksw = @$_REQUEST['ksw'];
$text = @$_REQUEST['text'];
$display = @$_REQUEST['display'];
if (fswText($text)) {
  $ksw = fsw2ksw($text);
} else if (kswLayout($text)) {
  $ksw = $text;
}
if (kswPanel($display)) {
  $cluster = panel2cluster($display);
  $ksw = cluster2ksw($cluster);
}
$name= @$_REQUEST['name'];
//if(!$name){$name='glyphogram';}
if(!$name){$name=$ksw;}

$subHead=getSignTitle(139,"ui");
echo "<html><head><title>$subHead</title>";
?>
<LINK REL=STYLESHEET HREF="standard.css" TYPE="text/css">
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK REL=STYLESHEET HREF="columns.css" TYPE="text/css">
<SCRIPT LANGUAGE="Javascript" SRC="PopupWindow.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="AnchorPosition.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="ColorPicker2.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
var cp = new ColorPicker('window'); // Popup window
var cp2 = new ColorPicker(); // DIV style
function pickColor(color){
  color = color.substr(1,6)
  if (vColorFor=="sign") {
    options.color.value = color;
    options.colorize.checked = false;
    //vColorize=0
  } else if (vColorFor=="colorR") {
    options.colorR.value = color;
  } else if (vColorFor=="colorL") {
    options.colorL.value = color;
  } else {
    options.background.value = color;
    options.transparent.checked = false;
  }
}
</SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="rgbcolor.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="canvg.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="canvas2image.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="base64.js"></SCRIPT>

</head>
<body>

<?php
//now head the standard header
include "header.php";


  echo "<form name='options' method=post action=$PHP_SELF>";
  echo "<input type=\"hidden\" name=\"ksw\" value =\"$ksw\">";

  echo "<table border=1 cellpadding=14 border=0><tr><td>";



//column 2
  echo "<h2>" . getSignTitle(130,"ui") . "</h2>";

  echo "<table cellpadding=4 border=0>";
  echo "<tr><td>" . getSignTitle(136,"ui") . "</td><td><select name='font'>";
  $opts = array();
  $opts['png1'] = 'PNG Standard';
  $opts['png2'] = 'PNG Inverse';
  $opts['png3'] = 'PNG Shadow';
  $opts['png4'] = 'PNG Colorize';
  $opts['svg0'] = 'SVG Refinement as PNG';
  $opts['svg1'] = 'SVG Refinement';
  $opts['svg2'] = 'SVG Line Trace';
  $opts['svg3'] = 'SVG Shadow Trace';
  $opts['svg4'] = 'SVG Smooth';
  $opts['svg5'] = 'SVG Angular';
  $opts['txt'] = 'ASCII';
  foreach($opts as $j=>$opt){
    echo "<option value='" . $j . "' ";
    if ($font==$j) {echo "selected";}
    echo ">" . $opt;
  }
  echo "</select></td></tr>";
  echo "<tr><td>" . getSignTitle(131,"ui") . "</td><td><select name='size'>";
  for ($s=1;$s<101;$s++){
    echo "<option value='" . $s/10 . "' ";
    if ($size==$s/10) {echo "selected";}
    echo ">" . $s/10;
  }
  echo "</select></td></tr>";

  echo "<tr><td>" . getSignTitle(122,"ui") . "</td><td><select name='pad'>";
  for ($s=0;$s<21;$s++){
    echo "<option value='" . $s . "' ";
    if ($pad==$s) {echo "selected";}
    echo ">" . $s;
  }
  echo "</select></td></tr>";

  echo '<tr><td>' . getSignTitle(132,"ui") . '</td><td><INPUT size=6 NAME="color" VALUE="'. $color . '"> ';
  echo "<input type=button onclick=\"vColorFor='sign';cp.show('pick');return false;\" name=\"pick\" id=\"pick\" value=\"Select\">";
  echo ' ' . getSignTitle(133,"ui") . ' <INPUT TYPE=CHECKBOX NAME="colorize" ';
  if ($colorize) {
    echo 'checked';
  }
  echo '></td></tr>';

  //Transparent
  if ($background==-1) {
    $tback = '';
  } else {
    $tback = $background;
  }
  echo '<tr><td>' . getSignTitle(134,"ui") . '</td><td><INPUT size=6 NAME="background" VALUE="'. $tback . '"> ';
  echo "<input type=button onclick=\"vColorFor='background';cp.show('pick');return false;\" name=\"pick\" id=\"pick\" value=\"Select\">";
  echo ' ' . getSignTitle(135,"ui") . ' <INPUT TYPE=CHECKBOX NAME="transparent" ';
  if ($transparent) {
    echo 'checked';
  }
  echo '></td></tr>';


  echo "<tr><td>" . getSignTitle(165,"ui") . "</td><td><select name='bound'>";
  $opts = array();
  $opts['t'] = getSignTitle(166,"ui");
  $opts['c'] = getSignTitle(167,"ui") ;
  $opts['v'] = getSignTitle(168,"ui") ;
  $opts['h'] = getSignTitle(169,"ui") ;
  foreach($opts as $j=>$opt){
    echo "<option value='" . $j . "' ";
    if ($bound==$j) {echo "selected";}
    echo ">" . $opt;
  }
  echo "</select></td></tr>";

  echo '</table>';
  echo '</td></tr>';

//end main table
  echo "</table>";


  echo "<br><hr><br>";

  echo "<input type=submit name=Recreate value=Recreate>";
  echo "<br><br><hr>";

//  if (!$transparent && !$background){$background="FFFFFF";}

//check for override
  $oBreak = array();
  $oSize = array();
  $oColor = array();
  if ($override){
    $oNum=0;
    $oLines = explode("\n",$override);
    foreach ($oLines as $oLine){
      if (trim($oLine)){
        $oItems = explode(",",$oLine);
        for ($o=0;$o<count($oItems);$o++){
          //get count
          $iNum = $oItems[$o];
          //get size
          $o++;
          $iSize=$oItems[$o];
          //get color
          $o++;
          $iColor=$oItems[$o];
          for ($k=0;$k<$iNum;$k++){
            $oSize[$oNum] = $iSize;
            $oColor[$oNum] = $iColor;
            $oNum++;
          }
        }
        //add line break
        $oBreak[$oNum-1]=1;
      }
    }  
  }

  if ($ksw){
    $display = explode(' ',ksw2panel($sgntxt,intval($length/$size),$params));
    $cnt = count($display);
    $fmt = substr($font,0,3);
    $ver = substr($font,3,1);
    switch ($fmt){
    case "png":
      $pre = '<div class="signtextcolumn"><img src="' . $swis_glyphogram . '?font=' . $font. '&size=' . $size;
      if ($color) $pre .= '&line=' . $color;
      if ($colorize) $pre .= '&colorize=1';
      if ($background) {
        $pre .= '&back=' . $background;
      }
      if ($pad) {
        $pre .= '&pad=' . $pad;
      }
      if ($bound) {
        $pre .= '&bound=' . $bound;
      }
      if ($color || $background) $font="png1";
      
      echo $pre . '&ksw=' . $ksw. '"></div>';

      break;
    case "txt":
      $pre = '<div class="signtextcolumn"><tt>';
      echo $pre . str_replace("\n","<br>",str_replace(' ','&nbsp;',glyphogram_txt($ksw)))  . '</tt></div>';
      break;
    case "svg":
      $cluster = ksw2cluster($ksw);
      $max = $cluster[0][1];
      $coord = str2koord($max);
      $xMax = $coord[0];
      $yMax = $coord[1];
      $min = cluster2min($cluster);
      $xMin = $min[0];
      $yMin = $min[1];

      if ($bound=="c" || $bound=="h"){
        if ((-$xMin) > ($xMax)) {
          $xMax = - $xMin;
        } else {
          $xMin = - $xMax;
        }
      }
      if ($bound=="c" || $bound=="v"){
        if ((-$yMin) > ($yMax)) {
          $yMax = - $yMin;
        } else {
          $yMin = - $yMax;
        }
      }
      $wsvg = ceil($xMax*$size) - floor($xMin*$size) + floor($pad*2*$size);
      $hsvg = ceil($yMax*$size) - floor($yMin*$size) + floor($pad*2*$size);

      if ($ver==0) {
        if ($ver==0){
          if ($background=='') $background ="FFFFFF";
          if ($background==-1) $background = '';
        }
        echo "\n" . '<canvas id="canvas" width="' . $wsvg . 'px" height="' . $hsvg . 'px"></canvas>' . "\n";
        $svg = glyphogram_svg($ksw, 1, $size, $pad, $bound, $color, '', $background, $colorize);
        echo '<script type="text/javascript">' . "\n";
        echo 'var oCanvas = document.getElementById("canvas");' . "\n";
        if ($background) {
          echo 'var context = oCanvas.getContext("2d");' . "\n";
          echo 'context.fillStyle = "#' . $background .'";' . "\n";
          echo 'context.fillRect (0,0,' . $wsvg . ',' . $hsvg . ');' . "\n";
        }
        echo "canvg(oCanvas, '" . str_replace("\n","",$svg) . "',{ ignoreDimensions: true,ignoreClear: true});\n";
        echo "var oImg = Canvas2Image.saveAsPNG(oCanvas, true);\n";
        echo 'oImg.id = "canvasimage";' . "\n";
        echo 'oImg.style.border = oCanvas.style.border;' . "\n";
        echo 'oCanvas.parentNode.replaceChild(oImg, oCanvas);' . "\n";
        echo '</script>' . "\n";
      } else {
//old way to insert SVG in div with plugin
        $pre = '<div class="signtextcolumn">';
        echo $pre . '<embed type="image/svg+xml" width="' . $wsvg . '" ';
        echo 'height="' . $hsvg . '" src="' . $swis_host . $swis_glyphogram . '?font=' . $font. '&'; 
        if ($size!=1) echo 'size=' . $size . '&';
        if ($color) echo 'line=' . $color . '&';
        if ($colorize) echo 'colorize=1&';
        if ($background) echo 'back=' . $background . '&';
        if ($pad) echo 'pad=' . $pad . '&';
        if ($bound) echo 'bound=' . $bound . '&';
        echo 'text=' . $ksw . '" ';
        echo 'pluginspage="http://www.adobe.com/svg/viewer/install/" style="overflow:hidden">';
        echo '</embed></div>';
      }
 
      break;
    }
    echo '<br clear="all">';
  
  }


  //end form
  echo "</form>";


  echo '</body>';
  echo '</html>';
?>
