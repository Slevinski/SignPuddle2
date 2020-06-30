<?php
error_reporting(E_ALL);
//ini_set('display_errors', 1);
include 'library/markdown/markdown.php';
include('library/xml/xmlFunc.php');

//setup email accounts and roots
$email = $_SERVER['SERVER_ADMIN'];
$emailRoot = strrchr($email,"@"); 

//setup secure
$isSecure = false;
$url_prefix = "http://";
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
  $isSecure = true;
  $url_prefix = "https://";
}

//setup signpuddle location
$puddle="";
$www = "data";
$icon = "library/icons/";
$data = getcwd() . "/" . $www;
$path = substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],"/")+1);
$host = $url_prefix . $_SERVER['HTTP_HOST'] . $path;

if(0){
  $swis_url = $url_prefix . "swis.wmflabs.org/";
  $swis_host = "";
} else {
  $swis_url = "";
  $swis_host = $host;
}
$swis_glyph = $swis_url . "glyph.php";
$swis_glyphogram = $swis_url . "glyphogram.php";

$sponline= $url_prefix . "www.signbank.org/signpuddle2.0/";
if (!isPP())  $sponline="";
function isPP(){
  return !file_exists('admin.php');
}

//setup local location
if ($_REQUEST['local']){
  $_SESSION['local']=urldecode($_REQUEST['local']);
}

//User Interface Items
$_SESSION['uiElements']=array();
$_SESSION['uiElements'][1]=array(); // 1 page title
$_SESSION['uiElements'][2]=array(); // 2 page detail
$_SESSION['uiElements'][3]=array(); // 3 header / side command / footer
$_SESSION['uiElements'][4]=array(); // 4 default
$_SESSION['uiElements'][5]=array(); // 5 end

$imgDir = $host . $www;
$PHP_SELF = $_SERVER['PHP_SELF'];
//default admin settings
include('default.php');
include('data/adm/usr.php');
$security=0;
$num = $_SESSION['num'];
//determine $sgn..
$sgn = $_REQUEST['sgn'];
//if ($sgn){if (!is_dir($data . "/sgn/" . $sgn)) {$sgn = "";}}
if ($sgn=="") {$sgn = $_SESSION['SGN'];}
if ($sgn=="") {$sgn=0;}
//now set session var
$_SESSION['SGN'] = $sgn;

//determine $ui..
$ui = $_REQUEST['ui'];
if ($ui=="") {$ui = $_SESSION['UI'];}
if ($ui===null) {
  if (is_dir($data . '/ui/1')){
    $ui=1;
  } else {
    $d = dir($data . '/ui');
    while ($Entry = $d->Read()) {
      if (($Entry == "..") or ($Entry == ".")) continue;
      if (is_dir($data . '/ui/' . $Entry)) {
        $ui = $Entry;
        break;
      }
    }
  }
}
if (!is_dir($data . '/ui/' . $ui)) {
  if(file_exists($data .'/spml/ui' . $ui . '.spml')){
    unpack_spml('ui',$ui);
  } else {
    $ui='';
  }
}

//now set session var
$_SESSION['UI'] = $ui;

//set style 
$xstyle = $_REQUEST['style'];
if ($xstyle){
  $_SESSION['style'] = $xstyle;
}

//set spcolor
$xspcolor = $_REQUEST['spcolor'];
if ($xspcolor){
  $_SESSION['spcolor'] = $xspcolor;
}
$glyph_line = '';
if ($_SESSION['spcolor']) $glyph_line = '&line=' . $_SESSION['spcolor'];

//set export_list export list
$xexport_list = $_REQUEST['export_list'];
if ($xexport_list){
  $xexport_list = explode(',',$xexport_list);
  $_SESSION['export_list'] = array_unique(array_merge($xexport_list,$_SESSION['export_list']));
}

setUISGN();
//illegal characters for filenames
$illegals = "~!@#$%^&*()+{}[]|:;<>,.?`=/\\\" ";

//valid image file ext
$imgExt = array("png","gif","jpeg","jpg");
//valid video file ext
$vidGeneralExt = array("mpg","mpeg");
$vidFlashExt = array("swf","dcr","dir");
$vidRealMediaExt = array("rm","ram","rpm","rv");
$vidQuickTimeExt = array("qt","mov","mp4");
$vidWindowsMediaExt = array("wmv","avi","asf","asx");
$vidExt = array_merge($vidGeneralExt,$vidFlashExt,$vidRealMediaExt,$vidQuickTimeExt, $vidWindowsMediaExt);

//$spView = ''; //ui or sgn followed by list or edit

function setUISGN(){
  global $sgn;
  global $sgndir;
  global $sgnwww;
  global $ui;
  global $uidir;
  global $uiwww;
  global $data;
  global $www;
  global $puddle;  
  global $spView;

  $spView = '';
  //setup the dir and www for sgn and ui
  if (($sgn==0)and($ui==0)){//ui list
    $spView = 'uilist';
    $puddle = "ui";
    $sgndir = $data . "/ui";
    $sgnwww = $www . "/ui";
    if (is_dir($data . '/ui/1')){
      $uidir = $data . "/ui/1";
      $uiwww = $www . "/ui/1";
      $upid = 1;
    } else {
      //check for packed
      if(file_exists($data .'/spml/ui1.spml')){
        unpack_spml('ui',1);
        $uidir = $data . "/ui/1";
        $uiwww = $www . "/ui/1";
        $upid = 1;
      } else {
        $d = dir($data . '/ui');
        while ($Entry = $d->Read()) {
          if (($Entry == "..") or ($Entry == ".")) continue;
          if (is_dir($data . '/ui/' . $Entry)) {
          $uidir = $data . "/ui/" . $Entry;
          $uiwww = $www . "/ui/" . $Entry;
          $upid = $Entry;
            break;
          }
        }
      }
    }
  } else if (($sgn==0)and($ui>0)){//sgn list
    $spView = 'sgnlist';
    $puddle = "sgn";
    $sgndir = $data . "/sgn";
    $sgnwww = $www . "/sgn";
    $uidir = $data . "/ui/" . $ui;
    $uiwww = $www . "/ui/" . $ui;
    $upid = $ui;
  } else if (($sgn>0)and($ui==0)){//ui edit
    $spView = 'uiedit';
    $puddle = "ui" . $sgn;
    $sgndir = $data . "/ui/" . $sgn;
    $sgnwww = $www . "/ui/" . $sgn;
    $uidir = $data . "/ui/" . $sgn;
    $uiwww = $www . "/ui/" . $sgn;
    $upid = $sgn;
  } else if (($sgn>0)and($ui>0)){//sgn edit
    $spView = 'sgnedit';
    $puddle = "sgn" . $sgn;
    $sgndir = $data . "/sgn/" . $sgn;
    $sgnwww = $www . "/sgn/" . $sgn;

    //unpack if packed
    If (!is_dir($sgndir)) {
      if(file_exists($data .'/spml/sgn' . $sgn . '.spml')){
        unpack_spml('sgn',$sgn);
      }
    }
    if(!file_exists($data .'/spml/sgn' . $sgn . '.spml')){
      pack_spml('sgn',$sgn);
    }

    $uidir = $data . "/ui/" . $ui;
    $uiwww = $www . "/ui/" . $ui;
    $upid = $ui;
  } else {//something wrong here!
    die("something is wrong!");
  }
  setSecurity();
}

function setSecurity(){
  global $sgndir;
  global $puddle;
  global $localusers;
  global $userlist;
  global $localusers;
  global $view;
  global $add;
  global $edit;
  global $copy;
  global $register;
  global $upload;
  global $security;
  global $video_width;
  global $video_height;
  $err = "";
  $istart = strpos($sgndir,'/data/');
  $admval = str_replace('/','',substr($sgndir,$istart+6));
  $admfile = substr($sgndir,0,$istart+6) . 'adm/' . $admval . '.adm.php';
  //$admfile = $sgndir . '.adm.php';
  if (!file_exists($admfile)){ 
    archiveAdmin();
  } else {
    include $admfile;
  }

  //check for old users...
  if (is_array($users)){
    //move users to userlist and localusers
    foreach ($users as $name => $user) { 
      //check if username already exists...
      if(array_key_exists($name,$userlist)){
        if ($userlist[$name]["security"] < $user["security"]){
          $localusers[$name]= array("security" => $user["security"]);
        }
      } else {
        $localusers[$name]= array("security" => $user["security"]);
        $user["security"]=0;
        $userlist[$name]=$user;
      }
    }
    //archive users and admin
    archiveAdmin();
    archiveUsers();    
  }

  //now check for removed users...
  foreach ($localusers as $name=>$user){
    if (!array_key_exists($name,$userlist)){
      unset($localusers[$name]);
      archiveAdmin();
    }
  }
  
  //now check session password
  $usr = $_SESSION['puddle_usr'];
  $psw = $_SESSION['puddle_psw'];
  $security = 0;
  //start with the lowest and move up
  if (array_key_exists($usr,$userlist)){
    if ($userlist[$usr]["password"]==$psw){
      $security = $userlist[$usr]["security"]; //main userlist
      if (array_key_exists($usr,$localusers)){
        $security = $localusers[$usr]["security"];//local user list
      }
    } else {
      // "invalid password";
      $err = displayEntry(94,'t',"ui",0,5);
    }  
  } else {
    //error message "User does not exist...";
    $err = displayEntry(93,'t',"ui",0,5);
  }

  //check for automatic promotions
  //start with security 0
  if ($security==0){
    if (!$view){
      $security=1;
    }
  }
  //check for security 1 promotion  
  if ($security==1){
    if (!$add){
      $security=2;
    }
  }
  //check for security 2 promotion  
  if ($security==2){
    if (!$edit){
      $security=3;
    }
  }

  if (isPP()) $security=3;

  return $err;
}

function nextID(){
  global $sgndir;
  //first check if there is an id file...
  $filename = $sgndir . ".id";
  if (file_exists($filename)) {
    $handle = fopen($filename, 'r+');
    $id = intval(fgets($handle));
    while (file_exists($sgndir . '/' . $id . '.xml')){
      $id++;
    }
    $next = 1 + $id ;
    rewind($handle);
    fwrite($handle, $next);
    fclose($handle);   
  } else {
    //write the file
    $id = 1;
    while (file_exists($sgndir . '/' . $id . '.xml')){
      $id++;
    }
    $next = 1 + $id ;
    $handle = fopen($filename, 'w');
    fwrite($handle, $next);
    fclose($handle);   
  }

  return $id;
}

function displayEntry($id, $style="a", $type="sgn", $puddle=0, $uilevel=4){ // style = t(ext, s(ign, b(both, i(mage, a(ll, 
  //if ($type == "sgn" && $id>0) die("Thought so! $id $style");//sgn=0 ui=0 1a dies
  if ($type=="ui") {
    if(!in_array($id,$_SESSION['uiElements'][5])) $_SESSION['uiElements'][$uilevel][] = $id;
  }
  global $sgnwww;
  global $sgndir;
  global $uiwww;
  global $uidir;
  global $data;
  global $www;
  global $imgExt;
  global $glyph_line;
  global $swis_glyphogram;
  if($puddle) {
    $dir = $data . '/' . $type . '/' . $puddle;
    $dirwww = $www . '/' . $type . '/' . $puddle;
  } else {
    $dir = $type . 'dir';
    $dir = $$dir;
    $dirwww = $type . 'www';
    $dirwww = $$dirwww;
  }
  //include sign...

  $sign = readSign($id, $type, $puddle);
  $button=array();
  if ($style=="a" or $style=="i"){
    //check for image
    $idpart = ".";
    if ($id){$idpart = '/' . $id;} else {$idpart = "";}
    foreach($imgExt as $ext){
      if (file_exists($dir . $idpart . "." . $ext)){
        $img = $dirwww . $idpart . "." . $ext;
      }
    }
    if($img) {
      $button[]='<img src="' . $img . '" border=0 title="' . $sign["trm"][0] . '">';
    } else {
      $style="b";
    }
  }  
  if ($style=="s" or $style=="b" or $style=="a"){
    //$build = trim($sign["bld"]);
    $ksw = trim($sign["ksw"]);
    if($ksw) {$button[]='<img src="' . $swis_glyphogram . '?text=' . $ksw . '&size=.5' . $glyph_line . '" border=0>'; }
  }

  if ($style=="b" or $style=="a"){
    $tterm = $sign["trm"][0];
    if($tterm!='') {
      $button[]="<font size=-1>" . $tterm. "</font>";
    }
  }
  if ($style=="t"){
    $term = $sign["trm"][0];
    if($term) {$button[]=$term; }
  }


  if ($style=="s" or $style=="i" or $style=="t"){
    $return = $button[0];
  } else {
    $return = "<table cellpadding=5><tr><td align=middle>" . trim(implode("<br>",$button)) . "</td></tr></table>";
  }
  return $return;
}

function displayIcon($id, $type="ui", $font=-2, $uilevel=4){  
  if ($type=="ui") {
    if(!in_array($id,$_SESSION['uiElements'][5])) $_SESSION['uiElements'][$uilevel][] = $id;
  }
  global $sgnwww;
  global $sgndir;
  global $uiwww;
  global $uidir;
  global $imgExt;
  global $glyph_line;
  global $swis_glyphogram;

  $dir = $type . 'dir';
  $www = $type . 'www';

  //check for image
  $idpart = ".";
  if ($id){$idpart = '/' . $id;} else {$idpart = "";}
  foreach($imgExt as $ext){
    if (file_exists($$dir . $idpart . "." . $ext)){
      $img = $$www . $idpart . "." . $ext;
    }
  }

  if($img) {
    $return = '<img src="' . $img . '"';
//    if ($h) {$return.=' height=' . $h;}
//    if ($w) {$return.=' width=' . $w;}
    $return.=' border=0>';
  } else {
    $sign = readSign($id, $type);
    $ksw = $sign['ksw'];
    $title = $sign['trm'][0];
    $return = '<table border=1';
//    if ($h) {$return.=' height=' . $h;}
//    if ($w) {$return.=' width=' . $w;}
    $return.='><tr><td valign=center align=center>';
    if($ksw) {$return.='<img src="' . $swis_glyphogram . '?text=' . $ksw . '&size=.5' . $glyph_line . '" border=0>'; }
//    if($build) {$return.='<img src="img.php?build=' . $build . '&size=.5" border=0>'; }
    if($ksw<>"" and $title<>"") {$return.='<br>';}
    if($title) {$return.="<font size=" . $font . ">" . $title . '</font>'; }
    $return.='</td></tr></table>';
  }
  return $return;
}

function displaySWFull($sid,$valid=0){
  global $sgnwww;
  global $sgndir;
  global $sgn;
  global $ui;
  global $security;
  global $upload;
  global $copy;
  global $imgExt;
  global $vidExt;
  global $vidGeneralExt;
  global $vidFlashExt;
  global $vidQuickTimeExt;
  global $vidRealMediaExt;
  global $vidWindowsMediaExt;
  global $glyph_line;
  global $swis_glyph;
  global $swis_glyphogram;

  $pos = strrpos($sid,".");
  if ($pos>1) {
    $sid=substr( $sid, 0, $pos); 
  }
   $tSign = readSign($sid);
  if ($valid){
    if(!$tSign['valid']) return;
  }
//Term - Unicode
  $trm = $tSign["trm"];
  if (is_array($trm)){
    $title = $trm[0];
  } else {
    $title = $trm;
  }

  echo '<table cellpadding="10"><tr>';
  echo '<td><b><font size=+1>' . $title . '</font></b>';

  if (count($trm)>1) {
    echo '<br>' . implode(array_slice($trm,1),', ');
  }
  
  echo '</td>';

  //next and previous buttons
  if ($tSign["prev"] or $tSign["top"] or $tSign["next"]){
    if ($tSign["prev"]){
      $nav .= '<td valign=top><a href="canvas.php?';
      $nav .= 'ui=' . $ui . '&sgn=' . $sgn . '&sid=' . $tSign["prev"] . '">';
      $nav .= '<img border=0 src="' . $swis_glyph . '?sss=02-03-001-02-02-03&color=CC0000"></a></td>';
    } else {
      $nav .= '<td valign=top><img src="' . $swis_glyph . '?sss=02-03-001-02-02-03&color=999999"></td>';
    }
    if ($tSign["top"]){
      $nav .= '<td valign=top><a href="canvas.php?';
      $nav .= 'ui=' . $ui . '&sgn=' . $sgn . '&sid=' . $tSign["top"] . '">';
      $nav .= '<img border=0 src="' . $swis_glyph . '?sss=02-03-001-01-03-01&color=CC0000"></a></td>';
    } else {
      $nav .= '<td valign=top><img src="' . $swis_glyph . '?sss=02-03-001-01-03-01&color=999999"></td>';
    }
    if ($tSign["next"]){
      $nav .= '<td valign=top><a href="canvas.php?';
      $nav .= 'ui=' . $ui . '&sgn=' . $sgn . '&sid=' . $tSign["next"] . '">';
      $nav .= '<img border=0 src="' . $swis_glyph . '?sss=02-03-001-02-01-07&color=CC0000"></a></td>';
    } else {
      $nav .= '<td valign=top><img src="' . $swis_glyph . '?sss=02-03-001-02-01-07&color=999999"></td>';
    }
  }
  echo $nav;

  echo '</tr></table>';

//sign and alt titles here...
  //term - SW
  $ksw = $tSign["ksw"];
  if ($ksw){
    //term - SW Sequence
    $seq = ksw2seq($ksw);
    $seq = str_replace("S",'',$seq);
  }
  if ($ksw){
    echo '<div style="float:left;padding:5">';
    echo '<img src="' . $swis_glyphogram . '?text=' . $ksw . '&pad=10' . $glyph_line . '&name=' . $tSign['trm'][0] .'">';
    echo '<br>';
    echo '</div>';
  }


  //now check for signtext
  $sgntxt =  $tSign['sgntxt'];
  if ($sgntxt){
    stDisplay($sgntxt);
  }

  //sign detail grid

  $definition = $tSign["txt"];

  $imgs = array();
  foreach($imgExt as $ext){
    if (file_exists($sgndir . "/" . $sid . "." . $ext)){
      $imgs[] = $sgnwww . "/" . $sid . "." . $ext;
    }
  }


  $video = $tSign["video"];

  if ((count($imgs)>0) || $seq || $definition || $video){
    echo '<table border cellpadding="5"><tr>';
  }

  //Images
  foreach($imgs as $img){
    echo '<td valign="top"><img src="' . $img . '" style="max-height:350px"></td>';
  }

  //videos
/*
  foreach($vidExt as $ext){
    if (file_exists($sgndir . "/" . $sid . "." . $ext)){
      echo "<td align=middle>";
      echo '<a href="embedded_media.php?ui=' . $ui . '&sgn=' . $sgn . '&sid=' . $sid . "&ext=" . $ext . '">';
      if (in_array($ext,$vidGeneralExt)){
        echo '<img src="library/icons/video.jpg">';
      } else if (in_array($ext,$vidFlashlExt)) {
        echo '<img src="library/icons/videoFlash.gif">';
      } else if (in_array($ext,$vidQuickTimeExt)) {
        echo '<img src="library/icons/videoQuickTime.gif">';
      } else if (in_array($ext,$vidRealMediaExt)) {
        echo '<img src="library/icons/videoRealMedia.gif">';
      } else if (in_array($ext,$vidWindowsMediaExt)) {
        echo '<img src="library/icons/videoWindowsMedia.gif">';
      } else {
        echo '<img alt="' .$ext . '" src="library/icons/video' . $ext . '.gif">';
      }
      echo '<br><br>Click here for video.</a><br>';
      if (editSign($tSign)){
        echo '<form method="post" action="canvas.php">';
        echo "<input type='hidden' name='ui' value='$ui'>";
        echo "<input type='hidden' name='sgn' value='$sgn'>";
        echo "<input type='hidden' name='sid' value='$sid'>";
        echo "<input type='hidden' name='ext' value='$ext'>";
        echo "<input type='hidden' name='action' value='Remove'>";
        echo '<button type="submit">';
        echo displayEntry(46,"i","ui");//remove
        echo '</button>';
        echo "</form>";
      }
      echo "</td>";
    }
  }
*/

  if ($video) echo '<td>' . $video . "</td>";

  if ($definition){
    echo '<td valign="top">' . markdown($definition) . '</td>';
  }

  if ($seq){
    $seq=str_split($seq,5);
    echo '<td><table style="{float:right}" cellpadding=5 border=1>';
    foreach ($seq as $sym){
      if ($sym){
        echo '<tr><td align=middle><img src="' . $swis_glyph . '?key=' . $sym;
        if ($glyph_line) {
          echo $glyph_line;
        } else {
          echo '&line=999999';
        }
        echo '&size=.7"></td></tr>';
      }
    }
    echo "</table></td>";
  }


  if ((count($imgs)>0) || $seq || $definition || $video){
    echo '</tr></table><br>';
  }

  if (!$sgntxt){
    echo '<br clear="all">';
  }
  
  echo '<font size=-1>';
  $ssource= $tSign["src"];
  if ($ssource) {
    echo "Source: ";
    echo $ssource . '<br>';
  }
  if ($ksw){
    $fsw=ksw2fsw($ksw);
    $cl = ksw2cluster($fsw);
    $cnt = count($cl);
    $qlesearch = 'Q';//location exact symbol
    $qlgsearch = 'Q';//location general symbol
    $qsesearch = 'Q';//exact symbol
    $qsgsearch = 'Q';//general symbol
    for ($i=1;$i<$cnt;$i++){
      $qlesearch .= $cl[$i][0] . $cl[$i][1];
//      $qlgsearch .= substr($cl[$i][0],0,4) . 'uu' . $cl[$i][1];
      $qsesearch .= $cl[$i][0];
      $qsgsearch .= substr($cl[$i][0],0,4) . 'uu';
    }

    $seq = ksw2seq($ksw);
    if ($seq) $seq ="A" . $seq;
    $aksw  = str_replace($seq,'',$ksw);
 
    echo 'Search location: <a href="signsearch.php?ksw=' . $aksw . '">exact</a> or ';

    echo '<a href="signsearch.php?qsearch=' . $qlesearch . '">approximate</a><br>';

    echo 'Search symbols: <a href="signsearch.php?qsearch=' . $qsesearch . '">exact</a> or ';

    echo '<a href="signsearch.php?qsearch=' . $qsgsearch . '">base</a><br>';

    echo 'SWU: <span style="font-family:SuttonSignWritingOneD">' . fsw2swu(ksw2fsw($ksw)) . '</span><br>';
    echo 'Sign data: <a href="dataformat.php?qview=FSW&sgntxt=' . $ksw .'">other formats</a><br>';
  }
  if ($sgntxt){
    echo 'SignText data: <a href="dataformat.php?qview=FSW&sgntxt=' . $sgntxt .'">FSW</a> ';
    echo 'or <a href="dataformat.php?qview=SWU&sgntxt=' . $sgntxt .'">SWU</a><br>';
  }
  echo getSignTitle(65,"ui",2) . ": " . date(getSignTitle(66,"ui",2), $tSign['mdt']) . " UTC";
  echo "<br>" . getSignTitle(193,"ui",2) . " " . $sid;
  echo "</font>";
  echo "<br><br>";

  if ($ksw){
    $attr = array();
    $attr['ksw'] = $ksw;
//    echo spOption('signopt.php', displayEntry(112,"i","ui"),$attr);
    echo spOption('customize.php', displayEntry(139,"i","ui"),$attr);
    echo spOption('signsave.php', displayEntry(42,"i","ui"),$attr);

  }

  if (editSign($tSign)){

    $attr = array();
    $attr['ui'] = $ui;
    $attr['sgn'] = $sgn;
    $attr['sid'] = $sid;
    $attr['ksw'] = $ksw;

    echo spOption('signmaker.php', displayEntry(40,"i","ui",0,2),$attr);
    if ($ksw) echo spOption('sequence.php', displayEntry(41,"i","ui",0,2),$attr);

    $attr['action'] = 'Edit';
    echo spOption('canvas.php', displayEntry(43,"i","ui",0,2),$attr);//rewrite words

  }
  if ($sgn==0){
    $attr = array();
    if ($ui==0){
      $attr['ui'] = 0;
      $attr['sgn'] = $sid;
    } else {
      $attr['ui'] = $ui;
      $attr['sgn'] = $sid;
    }
    echo spOption('index.php', displayEntry(48,"i","ui",0,2),$attr);
  }

  //append signtext buttons...
  if ($sgntxt){
    //switch from st to buttons
    if (editSign($tSign)){
      $attr = array();
      $attr['ui'] = $ui;
      $attr['sgn'] = $sgn;
      $attr['sid'] = $sid;
      $attr['sgntxt'] = $sgntxt;
      echo spOption('signtext.php', displayEntry(103,"i","ui",0,2),$attr);//rewrite signtext
    }

    $attr = array();
    $attr['sgntxt'] = $sgntxt;
//    echo spOption('signtextopt.php', "SignText Options",$attr);
    echo spOption('columnmaker.php', displayEntry(137,"i","ui",0,2),$attr);
    echo spOption('signmail.php', displayEntry(47,"i","ui",0,2),$attr);
    echo spOption('signtextsave.php', displayEntry(149,"i","ui",0,2),$attr);
    echo spOption('signsave.php', displayEntry(164,"i","ui",0,2),$attr);
   } else {
    if (editSign($tSign)){
      $attr = array();
      $attr['ui'] = $ui;
      $attr['sgn'] = $sgn;
      $attr['sid'] = $sid;
      echo spOption('signtext.php', displayEntry(50,"i","ui",0,2),$attr);//add signtext
    }
  }

//append signtext buttons...
    if ($sgntxt){
      if (editSign($tSign)){
        //delete signtext
        $attr = array();
        $attr['ui'] = $ui;
        $attr['sgn'] = $sgn;
        $attr['sid'] = $sid;
        $attr['action'] = 'DeleteST';
        echo spOption('canvas.php', displayEntry(104,"i","ui",0,2),$attr);//add signtext
      }
    }

  if (editSign($tSign)){
    if ($security>=$upload){
      //upload
      $attr = array();
      $attr['ui'] = $ui;
      $attr['sgn'] = $sgn;
      $attr['sid'] = $sid;
      $attr['action'] = 'Upload';
      echo spOption('canvas.php', displayEntry(44,"i","ui",0,2),$attr);//add signtext
    }
  }

  if (editSign($tSign)){
    //delete entry
    $attr = array();
    $attr['ui'] = $ui;
    $attr['sgn'] = $sgn;
    $attr['sid'] = $sid;
    $attr['action'] = 'Delete';
    echo spOption('canvas.php', displayEntry(45,"i","ui",0,2),$attr);//add signtext
  }

  foreach($imgExt as $ext){
    if (file_exists($sgndir . "/" . $sid . "." . $ext)){
      if (editSign($tSign)){
        $attr = array();
        $attr['ui'] = $ui;
        $attr['sgn'] = $sgn;
        $attr['sid'] = $sid;
        $attr['ext'] = $ext;
        $attr['action'] = 'Remove';
        $txt = 'Remove ' . $ext . ' Image';
        if ($txt == 'Remove png Image') {
          echo spOption('canvas.php', displayEntry(152,"i","ui",0,2),$attr);
        } else {
          echo spOption('canvas.php', 'Remove ' . $ext . ' Image', $attr);
        }
      }
    }
  }

  echo '<br clear="all">';

}

function countDict() { 
  global $sgndir;

  $d = dir($sgndir); 

  while ($Entry = $d->Read()) { 
    if (!(($Entry == "..") || ($Entry == "."))) { 
      $ext=strtolower(substr($Entry,strrpos($Entry,".")+1)); 
      if ($ext=='php') { 
        $Count++; 
      } 
    } 
  } 
    return $Count; 
} 

function getIDList(){
  global $ui;
  global $sgn;
  global $sgndir;
  if ($ui and $sgn){
    $type='sgn';
    $id = $sgn;
  } else {
    $type='ui';
    if ($ui) {
      $id=$ui;
    } else if ($sgn) {
      $type='ui';
      $id=$sgn;
    } else {
      return;
    }
  }
  $spmlfile = $data . '/spml/' . $type . $id . '.spml';
  if (!file_exists($spmlfile)){
    $spmlfile = pack_spml($type,$id);
  }
  $xml = read_spml($type,$id);
  $ids=array();
  foreach($xml->children() as $entry) {
    $arr = $entry->attributes();
    $id = $arr['id'];
    if ($id) $ids[]=$id;
  }
  return $ids;
}

function latestEntries() {
  global $ui;
  global $sgn;
  global $sgndir;
  global $data;
  if ($ui and $sgn){
    $type='sgn';
    $id = $sgn;
  } else {
    $type='ui';
    if ($ui) {
      $id=$ui;
    } else if ($sgn) {
      $type='ui';
      $id=$sgn;
    } else {
      return;
    }
  }
  $spmlfile = $data . '/spml/' . $type . $id . '.spml';
  if (!file_exists($spmlfile)){
    $spmlfile = pack_spml($type,$id);
  }
  $xml = read_spml($type,$id);
  $ids=array();
  foreach($xml->children() as $entry) {
    $arr = $entry->attributes();
    $id = intval($arr['id']);
    $mdt = intval($arr['mdt']);
    if ($id) {
      $ids[$id] = $mdt;
    }
  }
  arsort($ids);
//  reset($ids);
  return $ids;
}

function readSign($sid, $type="sgn", $puddle=0) {
  global $sgndir;
  global $uidir;
  global $ui;
  global $data;
  if ($type=="ui") {
    if(!in_array($sid,$_SESSION['uiElements'][5])) $_SESSION['uiElements'][4][] = $sid;
  }
  if($puddle) {
    $dir = $data . '/' . $type . '/' . $puddle;
  } else {
    $dir = $type . 'dir';
    $dir = $$dir;
  }
  if ($sid) {  
    $filename = $dir . '/' . $sid . '.xml';
  } else {
    $filename = $dir . '.xml';
  }

  $xml = simplexml_load_file($filename);

  //init array...
  $sign = array();
  $sign["bld"] = '';
  $sign["seq"] = '';
  $sign["trm"] = array();
  $sign["txt"] = '';
  $sign["video"] = '';
  $sign["src"] = '';
  $sign["mod"] = array();
  //$sign["mod"][] = array("ip" => $_SESSION['ip'], "usr" => $_SESSION['puddle_usr']);
  $sign["cdt"] = time();
  $sign["mdt"] = time();


  if ($xml){
    $arr = $xml->attributes();
    $usr = $arr['usr'];
    $ip_bits = explode('.',$arr['usr']);
    if (count($ip_bits)==4) {
      $sign["mod"][]['ip']=$usr;
    } else {
      $sign["mod"] = array();
      $sign_user = array();
      $sign_user['usr'] = $usr;
      $sign['usr'] = $usr;
      $sign["mod"][] = $sign_user;
    }    

    $sign["cdt"] = intval($arr["cdt"]);    
    $sign["mdt"] = intval($arr["mdt"]);
    $sign["prev"] = $arr["prev"];
    $sign["top"] = $arr["top"];
    $sign["next"] = $arr["next"];

    foreach($xml->children() as $entry) {
      switch($entry->getName()){
      case "term":
        if (fswText($entry)){
          $ksw = fsw2ksw($entry);
          $sign['fsw']='' . $entry;
          $sign['ksw']='' . $ksw;
/*
          $seq = ksw2seq($entry);
          if($seq){
            $seq = str_replace('S','',$seq);
            $kseq = str_split($seq,5);
            $idseq = array_map(key2id,$kseq);
            $sign['seq']=implode($idseq,',');
          } else {
            $sign['seq']='';
          }
*/
        } else {
          $sign['trm'][]=''.$entry;
        }
        break;
      case "text":
        $text = trim($entry);
        if (fswText($text)){
          $sign['sgntxt']=fsw2ksw($text);
        } else {
          $sign['txt']=''.$entry;
        }
        break;
      case "video":
        $sign['video']=''.$entry;
        break;
      case "src":
        $sign['src']=''.$entry;
        break;
      case "png":
      case "svg":
        break;
      default :
        echo "ack ... " . $entry->getName();
        die();
      }
    }
    $sign['valid']=1;
  } else {
    $sign["mod"][] = array("ip" => $_SESSION['ip'], "usr" => $_SESSION['puddle_usr']);
    $sign['valid']=0;
  }
  return $sign;
}

function getSignTitle($sid, $type="sgn",$uilevel=4) {
  if(!in_array($sid,$_SESSION['uiElements'][5])) $_SESSION['uiElements'][$uilevel][] = $sid;
  $sign = readSign($sid,$type);
  return $sign["trm"][0];
}

function getSignText($sid, $type="sgn",$uilevel=4) {
  if(!in_array($sid,$_SESSION['uiElements'][5])) $_SESSION['uiElements'][$uilevel][] = $sid;
  $sign = readSign($sid,$type);
  return $sign["txt"];
}

function getSetting($sid, $puddle, $type="ui") {
  global $data;
  if ($type=="ui") {
    $_SESSION['uiElements'][5][] = $sid;
  }
  $sign = readSign($sid,$type,$puddle);

  if (count($sign["trm"])>1) {
    $return = $sign["trm"][1];
  } else {
    $return="";
  }
  return $return;
}

function writeSign($sid, $wSign) {
  global $data;
  global $puddle;
  global $sgndir;
  global $imgExt;
  $sign = readSign($sid);
  if ($wSign['bld']) {
    $seq = ksw2seq($wSign['ksw']);
    if ($seq) $seq ="A" . $seq;
    $wSign['ksw'] = $seq . bld2ksw($wSign['bld']);
  } else if ($wSign['seq']) {
    $ksw = cluster2ksw(ksw2cluster($wSign['ksw']));
    $ids = explode(',',$wSign['seq']);
    $seq = '';
    foreach ($ids as $id){
      if(!$id) continue;
      $seq .= 'S' . id2key($id,1);
    }
    if ($seq) $seq ="A" . $seq;
    $wSign['ksw'] = $seq . cluster2ksw(ksw2cluster($wSign['ksw']));
  }

  $spml = '<entry id="' . $sid . '"';

  //top, next and prev
  $top = $wSign['top'];
  if ($top) $spml .= ' top="' . $top . '"'; 
  $next = $wSign['next'];
  if ($next) $spml .= ' next="' . $next . '"'; 
  $prev = $wSign['prev'];
  if ($prev) $spml .= ' prev="' . $prev. '"'; 

  $spml .= ' cdt="' . $wSign['cdt'] . '"'; 
  $spml .= ' mdt="' . $wSign['mdt'] . '"';

  $usr = str_replace('&','&amp;',$wSign['mod'][0]['usr']);
  if (!$usr) $usr = $wSign['mod'][0]['ip'];
  $spml .= ' usr="' . $usr . '"';
  $spml .= '>' . "\n";

  if ($wSign['ksw']){
    $spml .= '  <term>' . ksw2fsw($wSign['ksw']) . '</term>' . "\n";
  }
  if ($wSign['sgntxt']){
    $spml .= '  <text>' . ksw2fsw($wSign['sgntxt']) . '</text>' . "\n";
  }
  //voice language section
  //...
  $vl_text= trim($wSign['txt']);
  $vl_term = '';
  foreach ($wSign['trm'] as $trm){
    if ($trm) {
      if (fswText($trm)){
        $vl_term.= '  <term>' . $trm . '</term>' . "\n";
      } else {
        $vl_term.= '  <term><![CDATA[' . $trm . ']]></term>' . "\n";
      }
    }
  }
  if ($vl_term) $spml.= $vl_term;
  if ($vl_text) $spml .= '  <text><![CDATA[' . $vl_text . ']]></text>' . "\n";

  $vl_vid= trim($wSign['video']);
  if ($vl_vid) $spml .= '  <video><![CDATA[' . $vl_vid . ']]></video>' . "\n";

  if ($wSign['src']) $spml .= '  <src><![CDATA[' . $wSign['src'] . ']]></src>' . "\n";
  //end entry
  $spml .= '</entry>';
  $file = $sgndir . '/' . $sid . '.xml';
  file_put_contents($file, $spml);

//now the main file.
  $test = substr($puddle,0,2);
  $len = strlen($puddle);
  if ($test=='ui'){
    $type = 'ui';
    $id = substr($puddle,2,$len-2);
  } else {
    $type = substr($puddle,0,3);
    $id = substr($puddle,3,$len-3);
  }
  $filename = $data . '/spml/' . $puddle . '.spml';
  $xml = read_spml($type,$id); //should be type, pid
  $result = $xml->xpath('//entry[@id="' . $sid . '"]');
  if ($result) {
    $isnew = 0;
    $old = $result[0];
    $dom=dom_import_simplexml($old);
  } else {
    $isnew = 1;
    $dom=dom_import_simplexml($xml);
  }
  
  $dir = 'data/' . $type . '/' . $id;

  //foreach($imgExt as $ext){
  //  $imgfile = $dir . '/' . $sid . '.' . $ext;
  //  if (file_exists($imgfile)) {
  //    $img = base64_encode(file_get_contents($imgfile));
  //    $spml = str_replace('</entry>','  <' . $ext .'>' . $img . '</' . $ext . '>' . "\n" . '</entry>',$spml);
  //  }
  //}

  $entry= simplexml_load_string($spml); 
  if ($entry === false) {
    echo 'Error while parsing the document';
    exit;
  }
  $dom_sxe = dom_import_simplexml($entry);
  if (!$dom_sxe) {
      echo 'Error while converting XML';
      exit;
  }

  $new = $dom->ownerDocument->importNode($dom_sxe, true);
  if ($isnew){
    $dom->appendChild($new);
  } else {
    $dom->parentNode->replaceChild($new,$dom);
  }
  file_put_contents($filename,$xml->asXML());
}

function deleteSign($sid) {
  global $data;
  global $sgndir;
  global $puddle;
  global $imgExt;
  global $vidExt;
  $msg = "";

  $test = substr($puddle,0,2);
  $len = strlen($puddle);
  if ($test=='ui'){
    $type = 'ui';
    $id = substr($puddle,2,$len-2);
  } else {
    $type = substr($puddle,0,3);
    $id = substr($puddle,3,$len-3);
  }

  $filename = $sgndir . '/' . $sid . '.xml';

  //check to make sure file sign exists
  if (file_exists($filename)) {
    $sidOK = true; 
    $sign = readSign($sid);
  } else { 
    $sidOK = false;
    $msg = "Sign does not exist";
  }

  if (!editSign($sign)){return "invalid security";}

  if ($sidOK) {

    //delete php file
    $ext = "xml"; 
    $filename = $sgndir . '/' . $sid . '.' . $ext;
    if (file_exists($filename)) {
      unlink($filename);
    }  

    //delete images
    foreach($imgExt as $ext){
      $filename = $sgndir . '/' . $sid . '.' . $ext;
      if (file_exists($filename)) {
        unlink($filename);
      }  
    }

    //delete video
    foreach($vidExt as $ext){
      $filename = $sgndir . '/' . $sid . '.' . $ext;
      if (file_exists($filename)) {
        unlink($filename);
      }  
    }
    //now check if it is a directory
    if (is_dir($sgndir . '/' . $sid)){
      // delete  main sgn or ui spml file
      $filename = $data . '/spml/' . $type . $sid . '.spml';
      unlink($filename);
      $filename = $data . '/spml/' . $type . '.spml';
      unlink($filename);

      //first clean out the directory
      foreach (glob($sgndir . '/' . $sid . "/*") as $filename) {
        unlink($filename);
      }
      //now remove the directory and secondary spml
      rmdir($sgndir . '/' . $sid);
      unlink($sgndir . '/' . $sid . '.spml');
//  write main file too slow
    } else {
      $filename = $data . '/spml/' . $puddle . '.spml';
      $xml = read_spml($type,$id);//should be type, id
      $result = $xml->xpath('//entry[@id="' . $sid . '"]');
      $dom=dom_import_simplexml($result[0]);
      $dom->parentNode->removeChild($dom);
      file_put_contents($filename,$xml->asXML());
    }

  }
  return $msg;
}

function deleteSignText($sid) {
//simply load sign, remove signtext, then writeSign
  $sign=readSign($sid);
  if (!editSign($sign)){break;}
  $sign["sgntxt"] = "";
  $sign["mod"][] = array("ip" => $_SESSION["ip"], "usr" => $_SESSION["puddle_usr"], "mdt" => time());
  $sign["mdt"] = time();
  writeSign($sid,$sign);
  return;
}

function editSign($sign) {
  global $security;
  global $puddle;
  //now check puddle user against mod user list
  //then ip
  if ($security>2) {return true;}
  if ($security==2){
    $usr = $sign["mod"][0]["usr"];
    if ($usr){
      if ($usr == $_SESSION['puddle_usr']) {
        return true;
      } else {
        return false;
      }
    } else {
      $ip = $sign["mod"][0]["ip"];
      if ($ip == $_SESSION["ip"]) { 
        return true;
      } else {
        return false;
      }
    }
  } else {
    return false;
  }
}

function stDisplay($sgntxt,$length,$size){

  global $glyph_line;
  global $swis_glyphogram;
  $sgntxt = trim($sgntxt);
  if (!$size) $size=.7;
  if (!$length) $length=400/$size;
  $ddisplay = explode(' ',ksw2panel($sgntxt,intval($length)));
  $cnt = count($ddisplay);
  $pre = '<div class="signtextcolumn"><img src="' . $swis_glyphogram . '?size=' . $size . $glyph_line;
  if (count($ddisplay)==1){
    $col = $ddisplay[0];
    $col = panelTrim($ddisplay[0]);
    echo $pre . '&ksw=' . $col . '"></div>';
  } else {
    forEach($ddisplay as $col){
      if (!$length){
        $col = panelTrim($col);
        echo $pre . '&ksw=' . $col . '"></div>';
      } else {
        echo $pre . '&panel=' . $col . '"></div>';
      }
    }
  }
  echo '<br clear="all">';
}

function spOption($page, $button, $attr){
  $output = '<div class="option">';
  $output .= '<form method="POST" action="' . $page . '">';
  foreach ($attr as $name=>$val){
    $output .= '<input type="hidden" name="' . $name .'" value="' . $val . '">';
  }
  $output .='<button type="submit">';
  if (strpos($button,"img")){
    $output .= $button;
  }  else {
    $output .= '<div style="{width:100;height:73}"><br>' . $button . '</div>';
  
  }
  $output .= '</button>';
  $output .= '</form>';
  $output .= '</div>';
  return $output;
}

function stOptions($sgntxt){

  $attr = array();
  $attr['sgntxt'] = $sgntxt;

  echo spOption('signtext.php', displayEntry(103,"i","ui",0,2),$attr);
  echo spOption('columnmaker.php', displayEntry(137,"i","ui",0,2),$attr);
  echo spOption('signmail.php', displayEntry(47,"i","ui",0,2),$attr);
  echo spOption('signtextsave.php', displayEntry(153,"i","ui",0,2),$attr);
  echo spOption('signsave.php', displayEntry(164,"i","ui",0,2),$attr);
//  echo spOption('extract.php', 'SignPuddle Reader',$attr);
  echo spOption('dataformat.php', displayEntry(140,"i","ui",0,2),$attr);

  echo '<br clear="all">';

}

function sgnOptions($ksw){

  $attr = array();
  $attr['ksw'] = $ksw;

  echo spOption('signmaker.php', displayEntry(150,"i","ui",0,2),$attr);
  echo spOption('customize.php', displayEntry(139,"i","ui",0,2),$attr);

  echo spOption('signmail.php', displayEntry(47,"i","ui",0,2),$attr);
  echo spOption('signsave.php', displayEntry(326,"i","ui",0,2),$attr);
  echo spOption('dataformat.php', displayEntry(140,"i","ui",0,2),$attr);

  echo '<br clear="all">';

}

function getFlagLines(){
  global $data;
  $keySGN = array();
  $d = dir($data . '/sgn');
  while (false !== ($entry = $d->read())) {
    if ($entry!="." && $entry!=".."){
      if (is_dir($data . "/sgn/" . $entry)){
       $keySGN[]=$entry;
      }
    } 
  }
  $d->close();
  sort($keySGN);
  $flag_order =file_get_contents("flag_order.txt");
  if (!$flag_order){
    $flag_order = "0," . implode(",",$keySGN);
  }
  $flag_lines= explode("\n",str_replace("\r","",$flag_order));
  foreach ($flag_lines as $i=>$line){
    $flag_lines[$i] = explode(",",$line);
    $cnt = count($flag_lines[$i]);
    $max = max($max,$cnt);
  }
  foreach ($flag_lines as $i=>$line){
    $flag_lines[$i] = array_pad($flag_lines[$i],$max,'');
  }
  foreach ($flag_lines as $i=>$line){
    foreach ($line as $j=>$entry){
      if (!is_dir($data . '/sgn/' . $entry)){
        unset ($flag_lines[$i][$j]);
      }
    }
  }
  return $flag_lines;
}


function archiveAdmin(){
global $sgndir;
global $localusers;
global $view;
global $add;
global $edit;
global $copy;
global $register;
global $upload;
global $video_width;
global $video_height;
 
  if (count($localusers)==0) return;

  //now I need to output the array!
  $istart = strpos($sgndir,'/data/');
  $admval = str_replace('/','',substr($sgndir,$istart+6));
  $filename = substr($sgndir,0,$istart+6) . 'adm/' . $admval . '.adm.php';
  //$filename = $sgndir . ".adm.php";
  $out = fopen($filename, "w");
  $text = '<?php' . "\n";
  $text .= '$localusers = array();' . "\n";
  foreach ($localusers as $name => $user) { 
    $text .= '$localusers["' . trim(str_replace('"','\"',$name)) . '"]= array(';
    $text .= '"security" => ' . $user["security"] . ');' . "\n";
  }

  $text .= '$view = ' . $view . ';' . "\n";
  $text .= '$add = ' . $add . ';' . "\n";
  $text .= '$edit = ' . $edit . ';' . "\n";

  $text .= '$copy = ' . $copy . ';' . "\n";
  $text .= '$register = ' . $register . ';' . "\n";
  $text .= '$upload = ' . $upload . ';' . "\n";

  $text .= '$video_width = ' . $video_width . ';' . "\n";
  $text .= '$video_height = ' . $video_height . ';' . "\n";

  $text .= "?>";
  fwrite($out, $text);
  fclose($out);
}

function archiveUsers(){
global $userlist;

  //now I need to output the array!
  $filename = "data/adm/usr.php";
  $out = fopen($filename, "w");
  $text = '<?php' . "\n";
  $text .= '$userlist = array();' . "\n";
  foreach ($userlist as $name => $user) { 
    $text .= '$userlist["' . trim(str_replace('"','\"',$name)) . '"]= array(';
    $text .= '"security" => ' . $user["security"] . ',';
    $text .= '"display" => "' . trim(str_replace('"','\"',$user["display"])) . '",';
    $text .= '"email" => "' . trim(str_replace('"','\"',$user["email"])) . '",';
    $text .= '"password" => "' . trim(str_replace('"','\"',$user["password"])) . '",';
    $text .= '"temp" => "' . trim(str_replace('"','\"',$user["temp"])) . '");' . "\n";
  }

  $text .= "?>";
  fwrite($out, $text);
  fclose($out);
}

function query2table($qsearch){
  global $swis_glyph;
  global $swis_glyphogram;
  if (!$qsearch || $qsearch == "Q") return;
  echo "<h2>" . getSignTitle(179,"ui",2) . "</h2>";
  $grid = array();
  $col = array();
  $col[0] = getSignTitle(180,"ui",2);
  $ksw = query2ksw($qsearch);
  if ($ksw){
    $cluster = ksw2cluster($ksw);
    $real = cluster2min($cluster,false);
    $adj=array($real[0],$real[1]);
    $ksw = raw2ksw($ksw);
    $ksw = crosshairs($ksw,10,$adj);
    $val = '<img src="' . $swis_glyphogram . '?ksw=' . $ksw . '">';
    $symsearch = query2anywhere($qsearch);
    //this should point to this page not searchquery always
    $val .= '<hr><p><a href="searchquery.php?qsearch=' . $symsearch .'">' . getSignTitle(181,"ui",2) . '</a>';
    $col[1]=$val;
    $grid[]=$col;
  }
  $col=array();
  $col[0] = getSignTitle(182,"ui",2);
  $iswa = query2syms($qsearch);
  $syms=array();
  if ($iswa) $syms = str_split($iswa,6);
  $iswa = '';
  foreach ($syms as $part){
    $base = substr($part,1,3);
    $fill = substr($part,4,1);
    $rotate = substr($part,5,1);
    if ($fill=='u'){
      if ($rotate=='u'){
        $match = getSignTitle(34,"ui");//any
      } else {
        $match = getSignTitle(36,"ui");//rotate
      }
    } else {
      if ($rotate=='u'){
        $match = getSignTitle(35,"ui");//fill
      } else {
        $match = getSignTitle(33,"ui");//exact
      }
    }

    if ($fill=='u') {
      $temp = base2view($base);
      $fill = substr($temp,3,1);
    }
    if ($rotate=='u') {
      $rotate=0;
    }
    $iswa .= '<p><img src="' . $swis_glyph . '?key=' . $base . $fill . $rotate . '"> - ' . $match;
  }
  if($iswa){
    $col[1] = $iswa;
    $grid[]=$col;
  }

  //now check for ranges
  $col=array();
  $col[0] = getSignTitle(183,"ui",2);
  $base_range = '';
  $ranges = query2ranges($qsearch);
  $base_range = '';
  if ($ranges) $ranges = str_split($ranges,8);
  foreach ($ranges as $range){
    $base_range .= '<p>';
    $from = substr($range,1,3);
    $to = substr($range,5,3);
    $base_range.= '<img src="' . $swis_glyph . '?key=' . base2view($from) . '"> - ';
    $base_range.= '<img src="' . $swis_glyph . '?key=' . base2view($to) . '">';
  }
  if ($base_range){
    $col[1] = $base_range;
    $grid[]=$col;
  }
  

  $return = "<table cellpadding=15 border=1><tr>";
  foreach ($grid as $col){
    $return.= '<th>' . $col[0] . '</th>';
  }
  $return .= '</tr><tr>';
  foreach ($grid as $col){
    $return .= '<td valign=top>';
    $return .=  $col[1];
    $return .= '</td>';
  }
  $return .= '</tr></table>';
  if ($return == '<table><tr></tr></table>') echo "All signs";
  $return .= '<br><hr>';

  return $return;
}
?>
