<?php
$rSL = 1;
include 'styleA.php';
include 'styleB.php';
//$subHead="Search by Signs";
$subHead=displayEntry(5,'t',"ui",0,1);

include 'header.php';
//echo "<center>";

$type = $_REQUEST['type']; 
$sid = explode(",",$_REQUEST['sid']); 
$sTrm = $_REQUEST['sTrm']; 
$sTxt = $_REQUEST['sTxt']; 
$sSrc = $_REQUEST['sSrc']; 
$pid = $_REQUEST['pid'];
if ($pid) $sid=explode(",",$pid); 
if (get_magic_quotes_gpc())
{
  $sTrm = stripslashes($sTrm);
  $sTxt = stripslashes($sTxt);
  $sSrc= stripslashes($sSrc);
}

$bldSearch = $_REQUEST['bldSearch'];
$keySearch = $_REQUEST['keySearch'];
if ($keySearch) {
  if (strlen($keySearch)<5) {
    $keySearch = substr($keySearch . '00',0,5);
  }
  $bldSearch = substr(key2id($keySearch,1),0,12) . ',0,0,0,0';
}

if ($sid[0]){
echo '<h3>' .  getSignTitle(175,"ui",1) . '<br>';
echo '<font color=999999 size=-1>' .  getSignTitle(195,"ui",2) . '</font></h3>';
echo '<form action="' . $PHP_SELF . '" method="POST">';
echo '<table cellpadding=5><tr><td>';
echo '<INPUT TYPE=hidden NAME="ui" VALUE="' . $ui . '">';
echo '<INPUT TYPE=hidden NAME="sgn" VALUE="' . $sgn . '">';
echo '<table border cellpadding=5>';
echo '<tr><td>';
echo getSignTitle(67,"ui",2);//terms and titles
echo '</td><td colspan=2>';
echo '<input size=50 name="sTrm" type="input" value="';
echo htmlspecialchars($sTrm);
echo '"/>';
echo '</td></tr>';
echo '<tr><td>';
echo getSignTitle(68,"ui",2);//text
echo '</td><td colspan=2>';
echo '<input size=50 name="sTxt" type="input" value="';
echo htmlspecialchars($sTxt);
echo '"/>';
echo '</td></tr>';
echo '<tr><td>';
echo getSignTitle(69,"ui",2);//source
echo '</td><td colspan=2>';
echo '<input size=50 name="sSrc" type="input" value="';
echo htmlspecialchars($sSrc);
echo '"/>';
echo '</td></tr>';
echo '<tr><td>';
echo getSignTitle(193,"ui",2);//puddle page
echo '</td><td colspan=3>';
echo '<input size=10 name="pid" type="input" value="' . $pid . '"/>';
echo '</td></tr>';
echo '<tr><td>';
echo '<a href="' . $PHP_SELF . '?ui=' . $ui . '&sgn=' . $sgn . '&sTrm=*">' .  getSignTitle(176
,"ui",1) . '</a>';
echo '</td><td colspan=3>&nbsp;</td></tr>';
echo '</table>';
echo '</td><td>';
echo '<button type="submit">';
echo displayEntry(16,"i","ui",0,2);
echo '</button>';
echo '</td></tr></table>';
echo '</form>';
}


foreach ($sid as $id){
  if(trim($id)){  
    echo displaySWFull($id);
    //check for export_list export
    if (is_array($_SESSION['export_list'])){
      echo '<p><form action="' . $PHP_SELF . '" method="POST">';
      echo '<input type=hidden name="sTrm" value="';
      echo htmlspecialchars($sTrm);
      echo '">';
      echo '<input type=hidden name="sTxt" value="';
      echo htmlspecialchars($sTxt);
      echo '">';
      echo '<input type=hidden name="sSrc" value="';
      echo htmlspecialchars($sSrc);
      echo '">';
      echo '<INPUT TYPE=hidden NAME="ui" VALUE="' . $ui . '">';
      echo '<INPUT TYPE=hidden NAME="sgn" VALUE="' . $sgn . '">';
      echo '<INPUT TYPE=hidden NAME="bldSearch" VALUE="' . $bldSearch . '">';
      echo '<INPUT TYPE=hidden NAME="export_list" VALUE="' . $id . '">';
      echo '<button type="submit">';
      echo displayEntry(106,"i","ui",0,2);
      echo '</button>';
      echo '</form>';
    }
    echo "<br><hr><br>";
  }
}

echo '<h3>' .  getSignTitle(175,"ui",1) . '<br>';
echo '<font color=999999 size=-1>' .  getSignTitle(195,"ui",2) . '</font></h3>';
echo '<form action="' . $PHP_SELF . '" method="POST">';
echo '<table cellpadding=5><tr><td>';
echo '<INPUT TYPE=hidden NAME="ui" VALUE="' . $ui . '">';
echo '<INPUT TYPE=hidden NAME="sgn" VALUE="' . $sgn . '">';
echo '<table border cellpadding=5>';
echo '<tr><td>';
echo getSignTitle(67,"ui",2);//terms and titles
echo '</td><td colspan=2>';
echo '<input size=50 name="sTrm" type="input" value="';
echo htmlspecialchars($sTrm);
echo '"/>';
echo '</td></tr>';
echo '<tr><td>';
echo getSignTitle(68,"ui",2);//text
echo '</td><td colspan=2>';
echo '<input size=50 name="sTxt" type="input" value="';
echo htmlspecialchars($sTxt);
echo '"/>';
echo '</td></tr>';
echo '<tr><td>';
echo getSignTitle(69,"ui",2);//source
echo '</td><td colspan=2>';
echo '<input size=50 name="sSrc" type="input" value="';
echo htmlspecialchars($sSrc);
echo '"/>';
echo '</td></tr>';
echo '<tr><td>';
echo getSignTitle(193,"ui",2);//puddle page
echo '</td><td colspan=3>';
echo '<input size=10 name="pid" type="input" value="' . $pid . '"/>';
echo '</td></tr>';
echo '<tr><td>';
echo '<a href="' . $PHP_SELF . '?ui=' . $ui . '&sgn=' . $sgn . '&sTrm=*">' .  getSignTitle(176
,"ui",1) . '</a>';
echo '</td><td colspan=3>&nbsp;</td></tr>';
echo '</table>';
echo '</td><td>';
echo '<button type="submit">';
echo displayEntry(16,"i","ui",0,2);
echo '</button>';
echo '</td></tr></table>';
echo '</form>';

$sArray = array();
$bArray = array();

if (trim($sTrm)!="" || trim($sTxt)!="" || trim($sSrc)!="" ){
  $input = puddle_spf();

  $dom = new DomDocument();
  $dom->loadXml($input,LIBXML_PARSEHUGE);
  $xpath = new DomXpath($dom);

  if ($sTrm=="*" && (trim($sTxt)!="" || trim($sSrc)!="") ){
    $sTrm = '';
  }

  if ($sTrm != "*") {

    $query = '//entry';
    $qarr = array();

    if ($sTrm){
      $aTrm = explode(' ',$sTrm);
      foreach ($aTrm as $sword){
        $qarr[] = '/term[contains(., "' . $sword . '")]/..';
      }
    }

    if ($sTxt){
      $aTxt = explode(' ',$sTxt);
      foreach ($aTxt as $sword){
        $qarr[] = '/text[contains(., "' . $sword . '")]/..';
      }
    }

    if ($sSrc){
      $aSrc = explode(' ',$sSrc);
      foreach ($aSrc as $sword){
        $qarr[] = '/src[contains(., "' . $sword . '")]/..';
      }
    }
    
    $query .= implode($qarr,'');
    $sArray = array();
    $matchingNodes = $xpath->query($query);
    foreach ($matchingNodes as $match){
      $id = $match->getAttribute('id');

      foreach ($match->getElementsByTagName('term') as $node){
        $term = $node->nodeValue;
        if (fswText($term)){
          if (!in_array($id,$sArray[$term])) $sArray[$term][]=$id;
        }
      }
    }
  } else {
    $query = '//entry';
    $qarr = array();

    $sArray = array();
    $matchingNodes = $xpath->query($query);
    foreach ($matchingNodes as $match){

      $id = $match->getAttribute('id');
      foreach ($match->getElementsByTagName('term') as $node){
        $term = $node->nodeValue;
        if (fswText($term)){
          if (!in_array($id,$sArray[$term])) $sArray[$term][]=$id;
        }
      }
  
    }
  }
}

$arrayM = $sArray;

//display signs
$Count = count($arrayM);
if ($Count){
  $rows=($Count/5)-.2;
  $Count=1;
  echo "<table border=1 cellpadding=5>";
  foreach ($arrayM as $fsw => $val){
    $val = implode(',',$val);
    $ksw = fsw2ksw($fsw);
    if ($Count==1) { echo"<td valign=top><center>";}
    echo "<a href='$PHP_SELF?ui=" . $ui . "&sgn=" . $sgn . "&sid=" . $val . "&sTrm=" . urlencode($sTrm) . "&sTxt=" . urlencode($sTxt) . "&sSrc=" . urlencode($sSrc) . "&&type=$type&bldSearch=$bldSearch'>";
    echo "<img src='" . $swis_glyphogram . "?text=" . $ksw . "&size=.5" . $glyph_line . "' alt=$val border=0></a><br><br>";
    if ($Count>$rows) {
      $Count=0;
      echo "</td>"; 
    }
    $Count++;
  }
  echo "</td></tr></table>";

  $list = array();
  foreach ($arrayM as $ids){
    $list = array_merge ($list,$ids);
  }
  $outlist = array_unique($list);
  echo "<hr>";
  $link = 'http://signpuddle.net/print?puddle=' . $puddle . '&ids=' . implode(',',$outlist) . '&showing=sign';
  if ($ui == 4) {
    $link .= "&ui=fr";
  }
  echo '<a href="' . $link . '"><button type="button">Print Search Above to PDF</button></a>';

} else {
  if (trim($sTrm)!="" || trim($sTxt)!="" || trim($sSrc)!="" ) {    
    echo "<p>" . getSignTitle(75,"ui",2);
    echo "<h3>" . getSignText(194,"ui",2) . "</h3>";
  }
}

if (is_array($_SESSION['export_list'])){
  if ($count){
    echo '<p><form action="' . $PHP_SELF . '" method="POST">';
    echo '<input type=hidden name="sTrm" value="';
    echo htmlspecialchars($sTrm);
    echo '">';
    echo '<input type=hidden name="sTxt" value="';
    echo htmlspecialchars($sTxt);
    echo '">';
    echo '<input type=hidden name="sSrc" value="';
    echo htmlspecialchars($sSrc);
    echo '">';
    echo '<INPUT TYPE=hidden NAME="ui" VALUE="' . $ui . '">';
    echo '<INPUT TYPE=hidden NAME="sgn" VALUE="' . $sgn . '">';
    echo '<INPUT TYPE=hidden NAME="bldSearch" VALUE="' . $bldSearch . '">';
    echo '<INPUT TYPE=hidden NAME="export_list" VALUE="' . $idlink . '">';
    echo '<button type="submit">';
    echo displayEntry(106,"i","ui",0,2);
    echo '</button>';
    echo '</form>';
  }
}
include 'footer.php';
?>
