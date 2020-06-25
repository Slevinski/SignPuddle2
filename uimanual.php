<?php
/*
#@+leo-ver=4-thin
#@+node:slevin.20070120113315:@thin W:/www/searchword.php
#@@first
#@@first
#@delims /* */ 
$rSL = 1;
include 'styleA.php';
include 'styleB.php';
$subHead="User Interface Guide";
//$subHead=displayEntry(4,'t',"ui");

include 'header.php';
//echo "<center>";

/*@<<process input>>*/
/*@+node:ses.20070131142921:<<process input>>*/
$type = $_REQUEST['type']; 
$sid = explode(",",$_REQUEST['sid']); 


$search = $_REQUEST['search']; 
if (get_magic_quotes_gpc())
{
  $search = stripslashes($search);
}

//if ($type=="") {$search="*";}
/*@-node:ses.20070131142921:<<process input>>*/
/*@nl*/


foreach ($sid as $id){
  if(trim($id)){  
    $out = displaySWFull($id,1);
    if ($out) {
      echo $out;
      echo "<br><hr><br>";
    }
    //export_list export
    if (is_array($_SESSION['export_list'])){
      echo '<p><form action="' . $PHP_SELF . '" method="POST">';
      echo '<input type=hidden name="search" value="';
      echo htmlspecialchars($search);
      echo '">';
      echo '<INPUT TYPE=hidden NAME="ui" VALUE="' . $ui . '">';
      echo '<INPUT TYPE=hidden NAME="sgn" VALUE="' . $sgn . '">';
      echo '<INPUT TYPE=hidden NAME="type" VALUE="' . $type . '">';
      echo '<INPUT TYPE=hidden NAME="export_list" VALUE="' . $id . '">';
      echo '<button type="submit">';
      echo displayEntry(106,"i","ui");
      echo '</button>';
      echo '</form>';
    }
  }
}

/*@<<input form>>*/
/*@+node:ses.20070131141622:<<input form>>*/
echo '<form action="' . $PHP_SELF . '" method="POST">';
echo '<table cellpadding=5><tr><td>';
echo '<INPUT TYPE=hidden NAME="ui" VALUE="' . $ui . '">';
echo '<INPUT TYPE=hidden NAME="sgn" VALUE="' . $sgn . '">';
echo '<table border><tr><td colspan=4>';
echo getSignTitle(12,"ui",2);
echo ':<input size=50 name="search" type="input" value="';
echo htmlspecialchars($search);
echo '"/>';
echo '</td></tr>';
echo '<tr>';
echo '<td><INPUT TYPE=RADIO NAME="type" VALUE="any"';
if (($type=="any") or (($type!="any")and($type!="exact"))) echo "CHECKED"; 
echo '>';
echo getSignTitle(13,"ui",2);
echo '</td>';
echo '<td><INPUT TYPE=RADIO NAME="type" VALUE="start"';
if ($type=="start") echo "CHECKED";
echo '>';
echo getSignTitle(14,"ui",2);
echo '</td>';
echo '<td><INPUT TYPE=RADIO NAME="type" VALUE="exact" ';
if ($type=="exact") echo "CHECKED";
echo '>';
echo getSignTitle(15,"ui",2);
echo '</td>';
echo '<td><INPUT TYPE=RADIO NAME="type" VALUE="all" ';
if ($type=="all") echo "CHECKED";
echo ' onclick="var nms = document.getElementsByName(' . "'search'" . ');nms[0].value=' . "'*'" . ';">';
echo getSignTitle(138,"ui",2);
echo '</td>';echo '</tr>';
echo '</table>';
echo '</td><td>';
echo '<button type="submit">';
echo displayEntry(16,"i","ui");
echo '</button>';
echo '</td></tr></table>';
echo '</form>';
/*@nonl*/
/*@-node:ses.20070131141622:<<input form>>*/
/*@nl*/
if (trim($search)!=""){

  include $sgndir . ".trm.php";

  foreach($terms as $word => $ids){
    $pos=strpos(mb_strtolower($word), mb_strtolower($search));

    if ((($pos===0) AND ($type=="start")) or (($pos!==FALSE)AND($type=="any")) or ($search=="*") or (($type=="exact")and (mb_strtolower($word)==mb_strtolower($search)))){
       $array[strval($word)]=$ids;
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
  // echo "[" . $key . "] = " . $val . "\n";
  if ($Count==1) { echo"<td valign=top>";}
  echo "<a href='$PHP_SELF?ui=" . $ui . "&sgn=" . $sgn . "&sid=" . implode(',',$val) . "&search=" . urlencode($search) . "&type=$type'>$key</a><br>";
  if ($Count>$rows) {
    $Count=0;
    echo "</td>"; 
  }
  $Count++;
 }
 echo "</td></tr></table>";
} else {
 if (trim($search)!="") {echo "<p>" . getSignTitle(75,"ui");}
}

if (is_array($_SESSION['export_list'])){
  $idlist = array();
  foreach ($array as $ids){
    $idlist = array_unique(array_merge($ids,$idlist));
  }
  $idlink = implode(",",$idlist);
  if ($idlist){
    echo '<p><form action="' . $PHP_SELF . '" method="POST">';
    echo '<input type=hidden name="search" value="';
    echo htmlspecialchars($search);
    echo '">';
    echo '<INPUT TYPE=hidden NAME="ui" VALUE="' . $ui . '">';
    echo '<INPUT TYPE=hidden NAME="sgn" VALUE="' . $sgn . '">';
    echo '<INPUT TYPE=hidden NAME="type" VALUE="' . $type . '">';
    echo '<INPUT TYPE=hidden NAME="export_list" VALUE="' . $idlink . '">';
    echo '<button type="submit">';
    echo displayEntry(106,"i","ui");
    echo '</button>';
    echo '</form>';
  }
}

include 'footer.php';
/*@@last*/
/*@nonl*/
/*@-node:slevin.20070120113315:@thin W:/www/searchword.php*/
/*@-leo*/
?>
