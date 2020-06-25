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
$Send  = $_REQUEST['Send'];
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
if ($justify=='') $justify=3;
$form= $_REQUEST['form'];
$font= $_REQUEST['font'];
if($font==""){
  $font='png1';
}
if (($font=="png2" || $font=="png4") && $color) $font="png1";
if ($colorize) {
  $font="png4";
  $color="";
}
  



$comments = stripslashes($_REQUEST['comments']);
$sgntxt = stripslashes($_REQUEST['sgntxt']);
$ksw = $_REQUEST['ksw'];
$from = $_REQUEST['from'];
$to = $_POST['to'];
if ($to==""){$to='slevin@signpuddle.net';}
$subject  = $_REQUEST['subject'];

if ($Recreate=="" && $Send=="") {
  $length=400;
  $width=150;
  $size=0.7;
  $offset=50;
  $padding=12;
  $puncBottom=12;
  $imageBaseName = "SignMail-" . time();
}

if ($ksw) {
  $sgntxt = $ksw;
  $length='';
  $width='';
}

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
  $size=1;
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
$stcnt = 0;
if ($sgntxt){
  $display = explode(' ',ksw2panel($sgntxt,intval($length/$size),$params));
  $stcnt = count($display);
  $fmt = substr($font,0,3);
}



?>
<html>
<head><title>SignMail</title>
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
  document.options.size.value = 1;
} 
</SCRIPT>
</head>
<body>
<?php
$subHead="SignMail";
//now head the standard header
include "header.php";

$check= $_REQUEST['check'];
$checking= $_SESSION['check'];
if ($checking==""){$checking="xxx";}
if ($Send == 'Send'){
if ($check == $checking){
//src="col/
  $displayPre="<HTML><HEAD><TITLE>$subject</TITLE>  <META http-equiv=Content-Type content='text/html; \n\tcharset=utf-8'>  <META content='SignWriting email generated from SignText output' name=PUDDLE></HEAD>  <BODY>";
  if (strpos($comments,"<")===false) {
    $displayEnd = "<p><p>". str_replace("\n","\n<p>",$comments) . "<br><br><hr>";
  } else {
    $displayEnd = "<p><p>$comments<br><br><hr>";
  }
  if (!$sgn) {
    $sgnt = 35;
  } else {
    $sgnt = $sgn;
  }
$displayEnd .= "\n<a href=\"http://www.signbank.org/signpuddle2.0/signtextopt.php?ui=$ui&sgn=$sgnt&sgntxt=" . str_replace(" ", " \n", $sgntxt) . "\">SignText Options</a>";
  $displayEnd .= "<p><p>Courtesy of <a href=\"http://www.signbank.org\">SignBank.org</a>";
  $displayEnd .= "</BODY></HTML>";
  //edit the from field.
  $from=str_replace(" ", "_", $from);
  if ($from) {
  // make sure of the @
    if (!strpos($from,"@")) { $from.="signtext@signbank.org";}
  } else {
    $from=$email;
  }
  $unique_sep1 = md5(uniqid(time()));
  $unique_sep2 = md5(uniqid(microtime()));
  $headers = "From: $from\n";
  $headers .= "Reply-To: $from\n";
  $headers .= "Return-Path: $from\n";
  $headers .= "MIME-Version: 1.0\n";
  $headers .= "Content-Type:"." multipart/related;\n\tboundary=\"$unique_sep2\"\n\n";
  $headers .= "--$unique_sep2\n";

  $headers .= "Content-Type: text/html; \n\tcharset=\"utf-8\"\n";
  $headers .= "Content-Transfer-Encoding: 7bit\n\n";

  $emailText = '<table border=1><tr>' . "\n";
  for ($i=0;$i<$stcnt;$i++){
    $emailText .= '<td valign=top><image src="cid:' . $imageBaseName . '-' . ($i+1) . '.png"></td>' . "\n";
    if ($form==1) $emailText .='</tr><tr>';
  }
  $emailText .= '</tr></table>';
  $headers .= $displayPre .  $emailText . $displayEnd . "\n\n";

  //attach images
  for ($i=0;$i<$stcnt;$i++){
    $headers .= "--$unique_sep2\n";
    $headers .= "Content-Type: image/png; \n\t";
    $headers .= "name= \"" . $imageBaseName . "-" . ($i+1) . ".png\"\n";
    $headers .= "Content-Transfer-Encoding: base64\n";
    $headers .= "Content-ID: <" . $imageBaseName . "-" . ($i+1) . ".png>\n\n";
    $ver = substr($font,3,1);
    if (!$length){
      $ksw = panelTrim($display[$i]);
    } else {
      $ksw = cluster2ksw(panel2cluster($display[$i]));
    }
    ob_start();
    ImagePNG(glyphogram_png($ksw, $ver,$size, '','',$color, '', $background, $colorize));
    $imgdata = ob_get_contents();
    ob_end_clean();
    
    $headers .= chunk_split(base64_encode($imgdata));
    $headers .= "\n";
  }
  $headers .= "--$unique_sep2--\n\n";

  if (mail($to, $subject, "", $headers)) {
    mail('slevin@signpuddle.net', $subject, "", $headers);
    echo "<b>The email has been sent!</b>";
  } else {
    echo "<b>The email has failed!</b>";
  }
}
}

  echo "<h1>SignMail is currently offline!</h1><br><hr><br>";


/*
  echo "<form name='options' method=post action=$PHP_SELF>";
  echo "<input type=\"hidden\" name=\"sgntxt\" value =\"$sgntxt\">";
  $check = md5(uniqid(time()));
  $_SESSION['check'] = $check;
  echo '<input type=hidden name=check value="' . $check . '">';
  echo "<input type=\"hidden\" name=\"list\" value =\"$list\">";
  echo '<INPUT TYPE="hidden" NAME="imageBaseName" VALUE="'. $imageBaseName. '">';
  echo "<table cellpadding=5 border=1>";
  echo "<tr><th>From</th><td colspan=5 align=left><input name='from' size=30 value='$from'></td></tr>";
  echo "<tr><th>To</th><td colspan=5 align=left><input name='to' size=30 value='" . $_REQUEST['to'] . "'></td></tr>";
  echo "<tr><th>Subject</th><td colspan=5 align=left><input name='subject' size=50 value='$subject'></td></tr>";
  echo "<tr><th>Comments</th><td colspan=5>";
  echo "<TEXTAREA NAME='comments' COLS=50 ROWS=8>$comments</TEXTAREA>";
  echo "</td></tr>";
  echo "</table>";

  echo '<br><input type=submit name=Send value=Send><br>';
 */
 if ($sgntxt){
    echo "<br>";
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

      forEach($display as $col){
        if (!$length){
          $col = panelTrim($col);
          echo $pre . '&ksw=' . $col . '"></div>';
        } else {
          echo $pre . '&panel=' . $col . '"></div>';
        }
      }
    }
    echo '<br clear="all"><hr><br>';
  
  }

  echo "<table border=1 cellpadding=14 border=0><tr><td>";
  echo "<h2>" . getSignTitle(119,"ui") . "</h2>";
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
  foreach($opts as $j=>$opt){
    echo "<option value='" . $j . "' ";
    if ($font==$j) {echo "selected";}
    echo ">" . $opt;
  }
  echo "</select></td></tr>";
  echo "<tr><td>" . getSignTitle(131,"ui") . "</td><td><select name='size'>";
  for ($s=1;$s<21;$s++){
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

  //end form
  echo "</form>";

include 'footer.php';
?>
