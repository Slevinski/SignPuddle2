<?php
$rSL = 1;
include 'styleA.php';
include 'styleB.php';
//$subHead="Search by Words";
$subHead=displayEntry(4,'t',"ui",0,1);

include 'header.php';

$type = $_REQUEST['type'];
$sid = explode(",",$_REQUEST['sid']); 

$sTrm = trim($_REQUEST['sTrm']); 
$sTxt = trim($_REQUEST['sTxt']); 
$sSrc = trim($_REQUEST['sSrc']);
$pid = $_REQUEST['pid']; 
if ($pid) $sid=explode(",",$pid); 
if (get_magic_quotes_gpc())
{
  $sTrm = stripslashes($sTrm);
  $sTxt = stripslashes($sTxt);
  $sSrc= stripslashes($sSrc);
}

if ($sid[0]){
echo '<h3>' .  getSignTitle(174,"ui",1) . '<br>';
echo '<font color=999999 size=-1>' .  getSignTitle(195,"ui",2) . '</font></h3>';
echo '<form action="' . $PHP_SELF . '" method="POST">';
echo '<table cellpadding=5><tr><td>';
echo '<INPUT TYPE=hidden NAME="ui" VALUE="' . $ui . '">';
echo '<INPUT TYPE=hidden NAME="sgn" VALUE="' . $sgn . '">';
echo '<table border cellpadding=5>';
echo '<tr><td rowspan=2>';
echo getSignTitle(67,"ui",2);//terms and titles
echo '</td><td colspan=3>';
echo '<input size=50 name="sTrm" type="input" value="';
echo htmlspecialchars($sTrm);
echo '"/>';
echo '</td></tr>';
echo '<tr>';
echo '<td><INPUT TYPE=RADIO NAME="type" VALUE="any"';
if (($type=="any") or (($type!="start")and($type!="exact"))) echo " CHECKED"; 
echo '>';
echo getSignTitle(13,"ui",2);
echo '</td>';
echo '<td><INPUT TYPE=RADIO NAME="type" VALUE="start"';
if ($type=="start") echo " CHECKED";
echo '>';
echo getSignTitle(14,"ui",2);
echo '</td>';
echo '<td><INPUT TYPE=RADIO NAME="type" VALUE="exact" ';
if ($type=="exact") echo " CHECKED";
echo '>';
echo getSignTitle(15,"ui",2);
echo '</td>';
echo '</tr>';
echo '<tr><td>';
echo getSignTitle(68,"ui",2);//text
echo '</td><td colspan=3>';
echo '<input size=50 name="sTxt" type="input" value="';
echo htmlspecialchars($sTxt);
echo '"/>';
echo '</td></tr>';
echo '<tr><td>';
echo getSignTitle(69,"ui",2);//source
echo '</td><td colspan=3>';
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
echo '<a href="' . $PHP_SELF . '?ui=' . $ui . '&sgn=' . $sgn . '&sTrm=*">' .  getSignTitle(176,"ui",1) . '</a>';
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
    //export_list export
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
      echo '<INPUT TYPE=hidden NAME="export_list" VALUE="' . $id . '">';
      echo '<button type="submit">';
      echo displayEntry(106,"i","ui",0,2);
      echo '</button>';
      echo '</form>';
    }
    echo "<br><hr><br>";
  }
}
if (count($sid) && $sid[0]){
  $link = 'http://signpuddle.net/print?puddle=' . $puddle . '&ids=' . implode(',',$sid);
  if ($ui == 4) {
    $link .= "&ui=fr";
  }
  echo '<a href="' . $link . '"><button type="button">Print Search Above to PDF</button></a>';
  echo "<br><br><hr><br>";
}

echo '<h3>' .  getSignTitle(174,"ui",1) . '<br>';
echo '<font color=999999 size=-1>' .  getSignTitle(195,"ui",2) . '</font></h3>';
echo '<form action="' . $PHP_SELF . '" method="POST">';
echo '<table cellpadding=5><tr><td>';
echo '<INPUT TYPE=hidden NAME="ui" VALUE="' . $ui . '">';
echo '<INPUT TYPE=hidden NAME="sgn" VALUE="' . $sgn . '">';
echo '<table border cellpadding=5>';
echo '<tr><td rowspan=2>';
echo getSignTitle(67,"ui",2);//terms and titles
echo '</td><td colspan=3>';
echo '<input size=50 name="sTrm" type="input" value="';
echo htmlspecialchars($sTrm);
echo '"/>';
echo '</td></tr>';
echo '<tr>';
echo '<td><INPUT TYPE=RADIO NAME="type" VALUE="any"';
if (($type=="any") or (($type!="start")and($type!="exact"))) echo " CHECKED";
echo '>';
echo getSignTitle(13,"ui",2);
echo '</td>';
echo '<td><INPUT TYPE=RADIO NAME="type" VALUE="start"';
if ($type=="start") echo " CHECKED";
echo '>';
echo getSignTitle(14,"ui",2);
echo '</td>';
echo '<td><INPUT TYPE=RADIO NAME="type" VALUE="exact" ';
if ($type=="exact") echo " CHECKED";
echo '>';
echo getSignTitle(15,"ui",2);
echo '</td>';
echo '</tr>';
echo '<tr><td>';
echo getSignTitle(68,"ui",2);//text
echo '</td><td colspan=3>';
echo '<input size=50 name="sTxt" type="input" value="';
echo htmlspecialchars($sTxt);
echo '"/>';
echo '</td></tr>';
echo '<tr><td>';
echo getSignTitle(69,"ui",2);//source
echo '</td><td colspan=3>';
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
    $array = array();
    $matchingNodes = $xpath->query($query);
    foreach ($matchingNodes as $match){

      $id = $match->getAttribute('id');
      foreach ($match->getElementsByTagName('term') as $node){
        $term = $node->nodeValue;
        if (!fswText($term)){
          $found=0;
          $count = count($aTrm);
          foreach ($aTrm as $sword){ //does it contain one of the words?
            if (strpos($term,$sword)!==false) $found++;
          }
          if ($found>=$count) {
            switch($type){
              case "exact":
                if ($sTrm) if ($term!=$sTrm) break;
              case "start":
                if ($sTrm) if (strpos($term,$sTrm)!==0) break;
              default:
                if (!in_array($id,$array[$term])) $array[$term][]=$id;
            }
          }
        }
      }
    }
  } else {
    $query = '//entry';
    $qarr = array();

    $array = array();
    $matchingNodes = $xpath->query($query);
    foreach ($matchingNodes as $match){

      $id = $match->getAttribute('id');
      foreach ($match->getElementsByTagName('term') as $node){
        $term = $node->nodeValue;
        if (!fswText($term)){
          if (!in_array($id,$array[$term])) $array[$term][]=$id;
        }
      }
  
    }
  }

}


if (count($array)){
// ksort($array);
  uksort($array, 'strcasecmp');
  reset($array);
  $rows=(count($array)/5)-.2;
  $Count=1;
  echo "<table border=1 cellpadding=5>";
  while (list($key, $val) = each($array)) {
//   echo "[" . $key . "] = " . $val . "\n";
   if ($Count==1) { echo"<td valign=top>";}
   echo "<a href='$PHP_SELF?ui=" . $ui . "&sgn=" . $sgn . "&sid=" . implode(',',$val) . "&sTrm=" . urlencode($sTrm) . "&type=" . $type . "&sTxt=" . urlencode($sTxt) . "&sSrc=" . urlencode($sSrc) . "&'>$key</a><br>";
   if ($Count>$rows) {
     $Count=0;
     echo "</td>"; 
   }
   $Count++;
  }
  echo "</td></tr></table>";

  $list = array();
  foreach ($array as $ids){
    $list = array_merge ($list,$ids);
  }
  $outlist = array_unique($list);
  echo "<br>";
  $link = 'http://signpuddle.net/print?puddle=' . $puddle . '&ids=' . implode(',',$outlist);
  if ($ui == 4) {
    $link .= "&ui=fr";
  }
  echo '<a href="' . $link . '"><button type="button">Print Search Above to PDF</button></a>';
  echo "<br><br><hr><br>";

} else {
  if (trim($sTrm)!="" || trim($sTxt)!="" || trim($sSrc)!="" ) {
    echo "<p>" . getSignTitle(75,"ui",2);
    echo "<h3>" . getSignText(194,"ui",2) . "</h3>";
  }
}

if (is_array($_SESSION['export_list'])){
  $idlist = array();
  foreach ($array as $ids){
    $idlist = array_unique(array_merge($ids,$idlist));
  }
  $idlink = implode(",",$idlist);
  if ($idlist){
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
    echo '<INPUT TYPE=hidden NAME="export_list" VALUE="' . $idlink . '">';
    echo '<button type="submit">';
    echo displayEntry(106,"i","ui",0,2);
    echo '</button>';
    echo '</form>';
  }
}

include 'footer.php';
?>
