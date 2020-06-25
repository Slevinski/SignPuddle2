<?php
$rSL = 0;
include 'styleA.php';
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">';
echo '<HTML>';
echo '<HEAD>';
echo '<LINK REL=STYLESHEET HREF="standard.css" TYPE="text/css">';
echo '<META http-equiv=Content-Type content="text/html; charset=utf-8">';
echo '<title>SignPuddle 2.0 ' . $subHead . '</title>';

if ($_SESSION['sCopy']) {
  echo "<META\n";
  echo "     HTTP-EQUIV=\"Refresh\" \n";
  echo "     CONTENT=\"0; URL=signmaker.php?build=" . $_SESSION['sCopy'] . "\">\n";
  echo "</HEAD><body></body></html>";
  $_SESSION['sCopy'] = "";
  die();
}
if ($_REQUEST['local']){
  $_SESSION['local']=$_REQUEST['local'];
}

echo '</head>';
echo '<body>';
include 'header.php';
$flag_lines = getFlagLines();
//if ui=0 select front end
if ($ui==0 and $sgn==0) {
  //get ui directories
  $keySGN = array();
  $d = dir($data . '/ui');
  while (false !== ($entry = $d->read())) {
    if ($entry!="." && $entry!=".."){
      if (is_dir($data . "/ui/" . $entry)){
       $keySGN[]=$entry;
      }
    }
  }
  $d->close();
  sort($keySGN);

  //now display list with links
  echo "<table cellpadding=5>";
  foreach ($keySGN as $entry){
    echo '<tr>';
    echo '<td valign=middle>';
    echo '<form method=get action="index.php">';
    echo '<input type=hidden name=ui value=' . $entry . '>';
    echo '<input type=hidden name=sgn value=0>';
    echo '<button type=submit>';
    echo displayEntry($entry);
    echo "</button>";
    echo '</form>';
    echo "</td>";
    echo "</tr>";
  }
  echo "</table>";

} else if ($sgn ==0){

//connect sign puddle online link
//if (isPP()){
//  echo '<form method=get action="' . $sponline . 'index.php">';
//  echo '<input type=hidden name=ui value=' . $ui . '>';
//  echo '<input type=hidden name=sgn value=0>';
//  echo '<input type=hidden name=local value="' . $host .'">';
//  echo '<button type=submit>';
//  echo '<img src="library/icons/ConnectSignPuddle.png">';
//  echo "</button>";
//  echo "</form>";
//}
  echo "<table cellpadding=5>";
  foreach ($flag_lines as $line){
    echo '<tr>';
    foreach ($line as $entry){
      echo '<td valign=middle>';

      if ($entry){
        echo '<form method=get action="index.php">';
        echo '<input type=hidden name=ui value=' . $ui . '>';
        echo '<input type=hidden name=sgn value=' . $entry . '>';
        echo '<button type=submit>';
        echo displayEntry($entry);
        echo "</button>";
        echo "</form>";
      }
      echo "</td>";
    }
    echo "</tr>";
  }
  echo "</table>";
} else {
//display sign language stuff
}

if ($sgn){
  //display dictionary details
  $sign = readSign(0,"sgn",$sgn);
  $bld = $sign["ksw"];
  if ($ksw) {
    echo '<img src="' . $swis_glyphogram . '?ksw=' . $ksw . $glyph_line . '">';
  }
  $txt = $sign["text"];
  echo markdown($txt);
//  displaySignText(0);
  
  include $sgndir . ".trm.php";
  $cTerms = count($terms);
  $idlist = array();
  foreach($terms as $ids){
    foreach($ids as $id){
      $idlist[$id]=1;
    }
  }
  $input = puddle_spf();
  $pattern = '/\<entry/';
  $count = preg_match_all($pattern, $input, $matches);
  echo "<center>";
  echo $count . ' ' . getSignTitle(59,'ui') . "<br>";
  $output = query_counts('Q',$input);
  $words = $output[0];
  echo count($words) . ' ' . getSignTitle(177,'ui') . '<br>';
  echo $output[2] . ' ' . getSignTitle(178,'ui') . '<br><br>';
  echo '<a href="latest.php">' . getSignTitle(63, 'ui') . '</a>';
}
include 'footer.php';
/*@@last*/
/*@-node:slevin.20070119155615:@thin W:/www/index.php*/
/*@-leo*/
?>
