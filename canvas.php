<?php
$rSL = 1;
include 'styleA.php';
include('library/sps/columnclass.php');
$dir = 'data/tmp/';
$sid = urldecode($_REQUEST['sid']);
$newname = $_REQUEST['newname'];
$name = $_REQUEST['name'];
$trm = $_REQUEST['trm'];
$txt = $_REQUEST['txt'];
$sgntxt = $_REQUEST['sgntxt'];
$video = $_REQUEST['video'];
$src = $_REQUEST['src'];
$top = $_REQUEST['top'];
$prev = $_REQUEST['prev'];
$next = $_REQUEST['next'];
$extRemove = $_REQUEST['ext'];
$build= $_REQUEST['build'];
$sequence = $_REQUEST['sequence'];
$action= $_REQUEST['action'];

//get variables
$source = $_REQUEST['source'];
$list = $_REQUEST['list'];
//$list = str_replace("\n","%0D%0A" ,$list);
//$list = str_replace("%0D%0A%0D%0A","%0D%0A" ,$list);
$list=str_replace("\r","",$list);

$imageBaseName= $_REQUEST['imageBaseName'];
//default values if format isn't set
if ($imageBaseName==""){
  $size=1;
  $imageBaseName = "tmp-" . time();
  $height=500;
}
$colStyle="absolute";
$laneOffset=75 * $size;
$colPad=45 * $size;
$spacing=10 * $size;

if ($sid=="" && $action==""){
  $action = "new";
}
if ($sid=="" && $action=="Save"){
  $action = "new";
}
$display="";
switch ($action){
case "new":

  if ($security<2) {break;}
  $display.= '<form method="POST" action="' . $PHP_SELF . '">';
  $display.= '<INPUT TYPE=hidden NAME="ui" VALUE="' . $ui . '">';
  $display.= '<INPUT TYPE=hidden NAME="sgn" VALUE="' . $sgn . '">';
  if ($build){
    $ksw = bld2ksw($build);
    $display.= '<input type=hidden name="build" value="' . $build . '">';
    $display.= "<img src='" . $swis_glyphogram . "?text=$ksw" . $glyph_line;
    $display.= "' border=1><br><br>";
  }
  $display.= '<table border>';
  $display.= '<tr><td>' . getSignTitle(67,"ui") . ':</td><td>';
  
  for ($r=0;$r<3;$r++){
    for ($c=0;$c<4;$c++){
      $display.= '<input size=20 name="trm[]" type="input" />';
    }
    $display.= '<br>';
  }

  if($list){
    $sgntxt = lst2ksw($list);
  } else {
    $sgntxt = $_REQUEST['sgntxt'];
  }
  $display.= '</td></tr>';
  $display.= '<tr><td>' . getSignTitle(68,"ui") . ':</td><td><textarea cols=70 rows=7 name="txt"></textarea></td></tr>';
  $display.= '<tr><td>' . getSignTitle(113,"ui") . ':</td><td><textarea cols=70 rows=7 name="video"></textarea></td></tr>';
  $display.= '<tr><td>' . getSignTitle(69,"ui") . ':</td><td><input size=80 name="src" type="input" /></td></tr>';
  $display.= '<tr><td>' . getSignTitle(114,"ui") . ':</td><td><input size=8 name="top" type="input" /></td></tr>';
  $display.= '<tr><td>' . getSignTitle(115,"ui") . ':</td><td><input size=8 name="prev" type="input" /></td></tr>';
  $display.= '<tr><td>' . getSignTitle(116,"ui") . ':</td><td><input size=8 name="next" type="input" /></td></tr>';
  $display.= '<tr><td></td><td align=center>';
  $display.= '<button type="submit">';
  $display.= displayEntry(49,"i","ui");//add
  $display.= '</button>';
  $display.= '<input type="hidden" name="action" value="Add" /></td>';
  $display.= '</tr></table><br><br>';

  if ($sgntxt){
    $display.= '<input type=hidden name="sgntxt" value="' . urlencode($sgntxt) . '">';
    $panels = explode(' ',ksw2panel($sgntxt,intval(400/.7),$params));
    $pre = '<div class="signtextcolumn"><img src="' . $swis_glyphogram . '?size=.7' . $glyph_line;
    if ($cnt>1) $pre .= '&height=' . $length;
    forEach($panels as $col){
      $display .= $pre . '&panel=' . $col . '"></div>';
    }
//    echo '<br clear="all">';
  }
  
  $display.= '</form>';

  break;
case "Edit":
  $sign  = readSign($sid);
  if (!editSign($sign)){break;}
  $trm = $sign["trm"];
  $txt = htmlspecialchars($sign["txt"]);
  $video = $sign["video"];
  $src = htmlspecialchars($sign["src"]);
  $top = $sign["top"];
  $prev = $sign["prev"];
  $next = $sign["next"];
  if ($top==0)$top="";
  if ($prev==0)$prev="";
  if ($next==0)$next="";
  if ($sign["ksw"]){
    $display.= "<img src='" . $swis_glyphogram . "?text=" . $sign["ksw"] . $glyph_line;
    $display.= "' border=0><br><br>";
  }
  $display.= '<form method="POST" action="' . $PHP_SELF . '">';
  $display.= '<input type=hidden name="ui" value="' . $ui . '">';
  $display.= '<input type=hidden name="sgn" value="' . $sgn . '">';
  $display.= '<input type=hidden name="sid" value="' . $sid . '">';
  $display.= '<table border>';
  $display.= '<tr><td>' . getSignTitle(67,"ui") . ':</td><td>';

  $i = 0;
  for ($r=0;$r<3;$r++){
    for ($c=0;$c<4;$c++){
      $display.= '<input size=20 name="trm[]" type="input" value="' . htmlspecialchars($trm[$i]) . '"/>';
      $i++;
    }
    $display.= '<br>';
  }
  
  $display.= '</td></tr>';
  $display.= '<tr><td>' . getSignTitle(68,"ui") . ':</td><td><textarea cols=70 rows=7 name="txt">' . $txt . '</textarea></td></tr>';
  $display.= '<tr><td>' . getSignTitle(113,"ui") . ':</td><td><textarea cols=70 rows=7 name="video">' . $video . '</textarea></td></tr>';
  $display.= '<tr><td>' . getSignTitle(69,"ui") . ':</td><td><input size=80 name="src" type="input" value="' . $src . '" /></td></tr>';
  $display.= '<tr><td>' . getSignTitle(114,"ui") . ':</td><td><input size=8 name="top" type="input" value="' . $top . '" /></td></tr>';
  $display.= '<tr><td>' . getSignTitle(115,"ui") . ':</td><td><input size=8 name="prev" type="input" value="' . $prev . '" /></td></tr>';
  $display.= '<tr><td>' . getSignTitle(116,"ui") . ':</td><td><input size=8 name="next" type="input" value="' . $next . '" /></td></tr>';
  $display.= '<tr><td></td><td align=center><input type="hidden" name="action" value="Update" />';
  $display.= '<button type="submit">';
  $display.= displayEntry(52,"i","ui");//update
  $display.= '</button>';
  $display.='</td>';
  $display.= '</tr></table>';
  $display.= '</form>';
  $sid="";
  break;
case "Update":
  $sign=readSign($sid);
  if (!editSign($sign)){break;}
  $sign["trm"] = $trm;
  $sign["txt"] = $txt;
  $sign["video"] = stripslashes($video);
  $sign["src"] = $src;
  $sign["top"] = $top;
  $sign["prev"] = $prev;
  $sign["next"] = $next;
  $sign["mod"][] = array("ip" => $_SESSION["ip"], "usr" => $_SESSION["puddle_usr"], "mdt" => time());
  $sign["mdt"] = time();
  writeSign($sid,$sign);
  break;
case "Copy":
  if ($security<1) {break;}
  $sign = readSign($sid);  
  $build = ksw2bld($sign["ksw"],1);
  $_SESSION['sCopy']=$build;
  $display.= "<h2>" . getSignTitle(58,"ui") . "</h2>";

$flag_lines= getFlagLines();

  $display .= "<table cellpadding=5>";

  foreach ($flag_lines as $line){
    $display .= '<tr>';
    foreach ($line as $entry){
      $display .= '<td valign=middle>';

      if ($entry){
        $display .= '<a href="index.php?ui=' . $dui . '&sgn=' . $entry . '">';
        $display .= displayEntry(0,"i","sgn",$entry);  
        $display .= "</a>";
      }
      $display .= "</td>";
    }
    $display .= "</tr>";
  }
  $display .= "</table>";
$sid="";
  break;
case "Delete":
  if ($security<2) {break;} // delete checks for editSign rights...
  if ($name){
    $msg = deleteSign($name);
    if ($msg) {
      $display.= "<b><i>$msg</i></b>";
    } else {
      $display.= "<b><i>" . getSignTitle(118,"ui") . "</i></b>";
      $sid = "";
    }
  } else {
    $display.= '<h1>' . displayEntry(45,"t","ui") . '?</h1>';
    $display.= '<table border>';
    $display.= '<tr><td colspan=2>' . $sid . '</td></tr>';
    $display.= '<tr><td align=center>';
    $display.= '<form method="POST" action="' . $PHP_SELF . '">';
    $display.= '<input type=hidden name="ui" value="' . $ui . '">';
    $display.= '<input type=hidden name="sgn" value="' . $sgn . '">';
    $display.= '<input type=hidden name="sid" value="' . $sid . '">';
    $display.= '<input type=hidden name="name" value="' . $sid . '">';
    $display.= '<input type=hidden name="action" value="Delete">';
    $display.= '<button type="submit">';
    $display.= displayEntry(45,"i","ui");//delete
    $display.= '</button>';
    $display.= '</form>';
    $display.= '</td><td align=center>';
    $display.= '<form method="POST" action="' . $PHP_SELF . '">';
    $display.= '<input type=hidden name="ui" value="' . $ui . '">';
    $display.= '<input type=hidden name="sgn" value="' . $sgn . '">';
    $display.= '<input type=hidden name="sid" value="' . $sid . '">';
    $display.= '<button type="submit">';
    $display.= displayEntry(51,"i","ui");//cancel
    $display.= '</button>';
    $display.= '</form>';
    $display.= '</td>';
    $display.= '</tr></table>';
    $display.= '<hr><br><hr><br>';
  }
  break;
case "DeleteST":
  if ($security<2) {break;} // delete checks for editSign rights...
  if ($name){
    $msg = deleteSignText($name);
    if ($msg) {
      $display.= "<b><i>$msg</i></b>";
    } else {
      $display.= "<b><i>" . getSignTitle(117,"ui") . "</i></b><hr>";
    }
  } else {
    $display.= '<h1>' . displayEntry(104,"t","ui") . '?</h1>';
    $display.= '<table border>';
    $display.= '<tr><td colspan=2>' . $sid . '</td></tr>';
    $display.= '<tr><td align=center>';
    $display.= '<form method="POST" action="' . $PHP_SELF . '">';
    $display.= '<input type=hidden name="ui" value="' . $ui . '">';
    $display.= '<input type=hidden name="sgn" value="' . $sgn . '">';
    $display.= '<input type=hidden name="sid" value="' . $sid . '">';
    $display.= '<input type=hidden name="name" value="' . $sid . '">';
    $display.= '<input type=hidden name="action" value="DeleteST">';
    $display.= '<button type="submit">';
    $display.= displayEntry(104,"i","ui");//delete
    $display.= '</button>';
    $display.= '</form>';
    $display.= '</td><td align=center>';
    $display.= '<form method="POST" action="' . $PHP_SELF . '">';
    $display.= '<input type=hidden name="ui" value="' . $ui . '">';
    $display.= '<input type=hidden name="sgn" value="' . $sgn . '">';
    $display.= '<input type=hidden name="sid" value="' . $sid . '">';
    $display.= '<input type=hidden name="name" value="' . $sid . '">';
    $display.= '<button type="submit" name="action" value="Cancel">';
    $display.= displayEntry(51,"i","ui");//cancel
    $display.= '</button>';
    $display.= '</form>';
    $display.= '</td>';
    $display.= '</tr></table>';
    $display.= '<hr><br><hr><br>';
  }
  break;
case "rewrite":
  $sign=readSign($sid);
  if (!editSign($sign)){break;}
  $sign["bld"] = $build;
  $sign["mod"][] = array("ip" => $_SESSION["ip"], "usr" => $_SESSION["puddle_usr"], "mdt" => time());
  $sign["mdt"] = time();
  writeSign($sid,$sign);

  break;
case "Add":
  if ($security<2) {break;}


  $uploaddir = $sgndir . "/";
  $sid = nextID();
  $sign=array();
  $sign["bld"] = "";
  $sign["seq"] = "";
  $sign["trm"] = $trm;
  $sign["txt"] = $txt;
  $sign["video"] = stripslashes($video);
  $sign["src"] = $src;
  $sign["top"] = $top;
  $sign["prev"] = $prev;
  $sign["next"] = $next;
  $sign["mod"] = array();
  $sign["mod"][] = array("ip" => $_SESSION["ip"], "usr" => $_SESSION["puddle_usr"], "mdt" => time());
  $sign["cdt"] = time();
  $sign["mdt"] = time();

  if ($build){
    $sign["bld"] = $build;
    $sign["ksw"] = bld2ksw($build);
  }

  if ($sgntxt){
    $sign["sgntxt"] = str_replace("+",' ',$sgntxt);
  }
  if ($security<3) {
    if ($sign["ksw"]=="" && $sign['sgntxt']=="") break;
  }
  writeSign($sid,$sign);
  //create directory for $sgn=0
  if ($sgn==0){
    mkdir($sgndir . '/' . $sid);
    if ($ui==0){
      $filename = $sgndir . '/1.id';
      $filecopy = str_replace('/ui/1.id','/ui/' . $sid . '.id' ,$filename);
      copy ($filename, $filecopy);
      foreach (glob($sgndir . '/1/*') as $filename) {
        $filecopy = str_replace('/ui/1/','/ui/' . $sid . '/' . '/' ,$filename);
        copy ($filename, $filecopy);
      }
      //copy ui spml file
      $filename = $sgndir . '/1.spml';
      $spmlfile = str_replace('/ui/1','/ui/' . $sid ,$filename);
      $spml = file_get_contents($filename);
      $spml = str_replace('puddle="1"','puddle="' . $sid . '"',$spml);
      file_put_contents($spmlfile,$spml);
    } else {
      //write main spml file
      $filename ='default.spml';
      $spml = file_get_contents($filename);
      $spml = str_replace('type=""','type="sgn"',$spml);
      $spml = str_replace('puddle=""','puddle="' . $sid . '"',$spml);
      $stmp = time();
      $spml = str_replace('cdt=""','cdt="' . $stmp . '"',$spml);
      $spml = str_replace('mdt=""','mdt="' . $stmp . '"',$spml);
      $spmlfile = $sgndir . '/' . $sid . '.spml';
      file_put_contents($spmlfile,$spml);
    }
  }
  break;
case "Save":
  if ($security<2) {break;}
  if ($list){
    $sign=readSign($sid);
    if (!editSign($sign)){break;}
    $sign["sgntxt"] = lst2ksw($list);
    $sign["mod"][] = array("ip" => $_SESSION["ip"], "usr" => $_SESSION["puddle_usr"], "mdt" => time());
    $sign["mdt"] = time();
    writeSign($sid,$sign);
  }
  break;
case "sequence":
  $sign=readSign($sid);
  if (!editSign($sign)){break;}
  $sign["seq"] = $sequence;
  $sign["mod"][] = array("ip" => $_SESSION["ip"], "usr" => $_SESSION["puddle_usr"], "mdt" => time());
  $sign["mdt"] = time();
  writeSign($sid,$sign);
  break;
case "Remove":
  $sign = readSign($sid);
  if (!editSign($sign)){break;}
  $filename = $sgndir . "/" . $sid . "." . $extRemove;
  if (file_exists($filename)){
    unlink($filename);
    writeSign($sid,$sign);
  }
  break;
case "Upload":
  $sign = readSign($sid);
  if (!editSign($sign)){break;}
  if ($security<$upload){break;}

  if ($_FILES['userfile']['name']){
    $path_parts = pathinfo($_FILES['userfile']['name']);
    $ext = strtolower($path_parts['extension']);
    $uploadto = $sgndir . '/' . $sid . '.' . $ext;
    if (in_array($ext,$imgExt) or in_array($ext,$vidExt)){
      move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadto);
      writeSign($sid,$sign);
    }
  } else {
    $display.= '<h2>' . displayEntry(77,"t","ui"). '</h2>';
    $display.= '<form enctype="multipart/form-data" action="canvas.php" method="POST">';
    $display.= displayEntry(78,"t","ui") . ': <input name="userfile" type="file"><br>';
    $display.= '<input type=hidden name="ui" value="' . $ui . '">';
    $display.= '<input type=hidden name="sgn" value="' . $sgn . '">';
    $display.= '<input type=hidden name="sid" value="' . $sid . '">';
    $display.= '<input type="hidden" name="action" value="Upload">';
    $display.= '<button type="submit">';
    $display.= displayEntry(44,"i","ui");//upload
    $display.= '</button>';
    $display.= '</form>';
  }
  break;
default:
  $display=" ";

}
//forward is the display didn't change
if ($display==""){
  header("Location: " . $PHP_SELF . "?ui=" . $ui . "&sgn=" . $sgn . "&sid=" . $sid); /* Redirect browser */
  exit;
} 

include 'styleB.php';
include 'header.php';
echo $display;
//display sign
if ($sid){
  displaySWFull($sid);
}

include 'footer.php'; 
?>
