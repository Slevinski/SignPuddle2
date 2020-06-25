<?php
set_time_limit(60);
$rSL = 1;
include 'styleA.php';
include 'image.php';

//ini_set("memory_limit","12M");
//require_once('library/zip/zip.lib.php');
//include('library/sps/columnclass.php');

//get variables
$Recreate  = $_REQUEST['Recreate'];
$PHP_SELF = $_SERVER['PHP_SELF'];
$size= $_REQUEST['size'];
if (!$size)$size=1;
$colorize= $_REQUEST['colorize'];
$color= $_REQUEST['color'];
$colorR= $_REQUEST['colorR'];
$colorL= $_REQUEST['colorL'];
$offset = $_REQUEST['offset'];
$padding = $_REQUEST['padding'];
$signTop = $_REQUEST['signTop'];
$signBottom = $_REQUEST['signBottom'];
$puncBottom = $_REQUEST['puncBottom'];
$length = $_REQUEST['length'];
$width = $_REQUEST['width'];
//$colStyle = $_REQUEST['colStyle'];
$background = $_REQUEST['background'];
$transparent = $_REQUEST['transparent'];
$override = $_REQUEST['override'];
$justify= $_REQUEST['justify'];
if ($justify=='') $justify=0;
$form= $_REQUEST['form'];
$font= $_REQUEST['font'];
if($font==""){
  $font='png1';
}

$swformat= $_REQUEST['swformat'];
$sgntxt = $_REQUEST['sgntxt'];

if($swformat==""){
  $swformat=4;
}

if ($Recreate=="") SetSW($swformat);


function SetSW($num){
  $num--;
  $swkey = array('length', 'width', 'offset', 'padding', 'puncBottom');
  $swdef= array();
  $swdef[]= array(400, 150, 50, 40, 40);
  $swdef[] = array(600,160 ,50, 32, 32);
  $swdef[] = array(800, 200, 50, 24, 24);
  $swdef[] = array(1200, 230, 50, 20, 20);
  forEach ($swkey as $i=>$val){
    global $$val;
    $$val = $swdef[$num][$i];
  }
} 

if ($transparent){$background="-1";}
$params = array();
$params['width'] = intval($width/$size);
$params['signTop'] = $padding;
$params['signBottom'] = $padding;
$params['padding'] = $padding;
$params['puncBottom'] = $puncBottom;
$params['offset'] = $offset;
$params['justify'] = $justify;
if ($form=='1') $params['form'] = 'row';

$subHead=getSignTitle(137,"ui");

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

function SetSW(){
  swkey = new Array('length', 'width', 'offset', 'padding', 'puncBottom');
  swdef= new Array();
  swdef[0]= new Array(400, 150, 50, 40, 40);
  swdef[1] = new Array(600,160 ,50, 32, 32);
  swdef[2] = new Array(800, 200, 50, 24, 24);
  swdef[3] = new Array(1200, 230, 50, 20, 20);
  for (i=0; i<document.options.swformat.length; i++) {
    if (document.options.swformat[i].checked==true) {
      num = document.options.swformat[i].value-1;
    }
  }
  document.options.length.value = swdef[num][0];
  document.options.width.value = swdef[num][1];
  document.options.offset.value = swdef[num][2];
  document.options.padding.value = swdef[num][3];
  document.options.puncBottom.value = swdef[num][4];
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
  echo "<input type=\"hidden\" name=\"sgntxt\" value =\"$sgntxt\">";

  echo "<table border=1 cellpadding=14 border=0><tr><td>";
  echo "<h2>" . getSignTitle(119,"ui") ."</h2>";
  echo "<table cellpadding=4 border=0>";
  echo "<tr>";

 for ($i=1;$i<5;$i++){
    echo "<td>";
    echo 'SW' . $i . ' <input type="radio" name="swformat"';
    echo ' value="' . $i . '" ';
    if ($swformat==$i){
      echo 'checked ';
    }
    echo 'onClick="SetSW()">';
    echo "</td>";
  }
  
 echo "</tr></table>";

  echo "<table cellpadding=4 border=0>";
  echo "<tr><td>" . getSignTitle(120,"ui") . "</td>";
  echo '<td><INPUT size=5 NAME="length" VALUE="'. $length . '"></td></tr>';
  echo "<tr><td>" . getSignTitle(121,"ui") . "</td>";
  echo '<td><INPUT size=5 NAME="width" VALUE="'. $width . '"></td></tr>';
  echo "<tr><td>" . getSignTitle(122,"ui") . "</td>";
  echo '<td><INPUT size=5 NAME="padding" VALUE="'. $padding . '"></td></tr>';
  echo "<tr><td>" . getSignTitle(123,"ui") . "</td>";
  echo '<td><INPUT size=5 NAME="puncBottom" VALUE="'. $puncBottom . '"></td></tr>';

  echo "<tr><td></td>";
  echo '<td></td></tr>';
  echo "<tr><td>" . getSignTitle(124,"ui") . "</td>";
  echo '<td><INPUT size=5 NAME="offset" VALUE="'. $offset . '"></td></tr>';

  echo "<tr><td>" . getSignTitle(125,"ui") . "</td><td><select name='justify'>";
  $opts = array(getSignTitle(126,"ui"),getSignTitle(127,"ui"),getSignTitle(128,"ui"),getSignTitle(129,"ui"));
  foreach($opts as $j=>$opt){
    echo "<option value='" . $j . "' ";
    if ($justify==$j) {echo "selected";}
    echo ">" . $opt;
  }
  echo "</select></td></tr>";
  
  echo "<tr><td>" . getSignTitle(171,"ui") . "</td><td><select name='form'>";
  $opts = array(getSignTitle(173,"ui"),getSignTitle(172,"ui"));
  foreach($opts as $j=>$opt){
    echo "<option value='" . $j . "' ";
    if ($form==$j) {echo "selected";}
    echo ">" . $opt;
  }
  echo "</select></td></tr>";
  
  //end first column
  echo '</table>';
//  echo "<tr><td>Column Size</td><td><select name='colStyle'>";
//  echo "<option value='absolute' ";
//  if ($colStyle=="absolute") {echo "selected";}
//  echo ">Absolute Height";
//  echo "<option value='relative' ";
//  if ($colStyle=="relative") {echo "selected";}
//  echo ">Width Relative";
//  echo "</select></td></tr>";


//column 2
  echo "</td><td valign=top>";
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
//  echo "<tr><td>Color Right Hand</td>";
//  echo '<td><INPUT size=6 NAME="colorR" VALUE="'. $colorR . '"> ';
//  echo "<input type=button onclick=\"vColorFor='colorR';cp.show('pick');return false;\" name=\"pick\" id=\"pick\" value=\"Select\">";
//  echo '</td></tr>';
//  echo "<tr><td>Color Left Hand</td>";
//  echo '<td><INPUT size=6 NAME="colorL" VALUE="'. $colorL . '"> ';
//  echo "<input type=button onclick=\"vColorFor='colorL';cp.show('pick');return false;\" name=\"pick\" id=\"pick\" value=\"Select\">";
//  echo '</td></tr>';
  
  //end second column
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

  if ($sgntxt){
    $display = explode(' ',ksw2panel($sgntxt,intval($length/$size),$params));
    $cnt = count($display);
    $fmt = substr($font,0,3);
    $ver = substr($font,3,1);
    switch ($fmt){
    case "png":
      if ($form==1){
        $pre = '<div class="signtextrow">';
      } else {
        $pre = '<div class="signtextcolumn">';
      }
      $pre .= '<img src="' . $swis_glyphogram . '?font=' . $font. '&size=' . $size;
      if ($color) $pre .= '&line=' . $color;
      if ($colorize) $pre .= '&colorize=1';
      if ($background) {
        $pre .= '&back=' . $background;
//        $pre .= '&fill=' . $background;
      }
      if ($color || $background) $font="png1";
/*
 * Here!
 */
      forEach($display as $col){
        if (!$length){
          $col = panelTrim($col);
          echo $pre . '&ksw=' . $col . '"></div>';
        } else {
          echo $pre . '&panel=' . $col . '"></div>';
        }
      }

//      forEach($display as $col){
//        echo $pre . '&panel=' . $col . '"></div>';
//      }
      break;
    case "txt":
      if ($form==1){
        $pre = '<div class="signtextrow">';
      } else {
        $pre = '<div class="signtextcolumn">';
      }
      $pre .= '<tt>';
      forEach($display as $col){
        $cluster = panel2cluster($col);
        $ksw = cluster2ksw($cluster);
        echo $pre . str_replace("\n","<br>",str_replace(' ','&nbsp;',glyphogram_txt($ksw)))  . '</tt></div>';
      }
      break;
    case "svg":
      if ($form==1){
        $pre = '<div class="signtextrow">';
      } else {
        $pre = '<div class="signtextcolumn">';
      }
      $i = 0;
      if ($ver==0){
        if ($background=='') $background ="FFFFFF";
        if ($background==-1) $background = '';
      }
      forEach($display as $col){
        $i++;
        $cluster = panel2cluster($col);
        $ksw = cluster2ksw($cluster);
        $max = $cluster[0][1];
        $coord = str2koord($max);
        $wsvg = ceil($coord[0]*$size);
        $hsvg = ceil($coord[1]*$size);


        if ($ver==0){
          echo "\n" . $pre . '<canvas id="canvas' . $i . '" width="' . $wsvg . 'px" height="' . $hsvg . 'px"></canvas></div>' . "\n";
          $svg = glyphogram_svg($ksw, 1, $size, $pad, $bound, $color, '', $background, $colorize);
          echo '<script type="text/javascript">' . "\n";

          echo 'var oCanvas = document.getElementById("canvas' . $i . '");' . "\n";
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
          echo $pre . '<embed type="image/svg+xml" width="' . $wsvg . '" '; 
          echo 'height="' . $hsvg . '" src="' . $swis_host . $swis_glyphogram . '?font=' . $font. '&';
          if ($size!=1) echo 'size=' . $size . '&';
          if ($color) echo 'line=' . $color . '&';
          if ($colorize) echo 'colorize=1&';
          if ($background) echo 'back=' . $background . '&';
          echo 'text=' . $ksw . '" ';
          echo 'pluginspage="http://www.adobe.com/svg/viewer/install/" style="overflow:hidden">';
          echo '</embed></div>';
        }
      }
      break;
    }
    echo '<br clear="all">';
  
  }

//  echo "Override<br><textarea name=override cols=40 rows=5>" . $override . "</textarea>";
  echo "<br><br><input type=submit name=Recreate value=Recreate>";

  //end form
  echo "</form>";


include 'footer.php';
?>
