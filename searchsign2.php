<?php
error_reporting(-1);
$rSL = 1;
include 'styleA.php';
include 'styleB.php';
//$subHead="Search by Signs";
$subHead=displayEntry(5,'t',"ui",0,1);

include 'header.php';
//echo "<center>";

$type = $_REQUEST['type']; 
$sid = explode(",",$_REQUEST['sid']); 
$search = $_REQUEST['search']; 
if (get_magic_quotes_gpc())
{
  $search = stripslashes($search);
}
$bldSearch = $_REQUEST['bldSearch'];
$keySearch = $_REQUEST['keySearch'];
if ($keySearch) {
  if (strlen($keySearch)<5) {
    $keySearch = substr($keySearch . '00',0,5);
  }
  $bldSearch = substr(key2id($keySearch,1),0,12) . ',0,0,125,125';
}

$qsearch='';
if ($bldSearch){
  $qsearch = 'Q';
  if ($bldSearch){
    $build = split(",",$bldSearch);
    $cnt = count($build);
    $cnt = $cnt - ($cnt%5);
    $syms = array();
    for ($i=0;$i<$cnt;$i++){
      $key = id2key($build[$i]);
      $base = substr($key,0,3);
      $i++;
      $fill=$build[$i];
      $i++;
      $rotate=$build[$i]; 
      $i++;
      $x=$build[$i]; 
      $i++;
      $y=$build[$i]; 
      
      $qsearch .= 'S' . $base;
      if ($fill) {
        $qsearch .= ($fill-1);
      } else {
        $qsearch .= 'u';
      }
      if ($rotate) {
        $qsearch .= dechex($rotate-1);
      } else {
        $qsearch .= 'u';
      }

      $qsearch .= ($x + 500-125) . 'x' . ($y + 500 -125);

    }
  }
}

if ($sid[0]){
//add additional search stuff here...
}


foreach ($sid as $id){
  if(trim($id)){  
    echo displaySWFull($id);
    //check for export_list export
    if (is_array($_SESSION['export_list'])){
      echo '<p><form action="' . $PHP_SELF . '" method="POST">';
      echo '<input type=hidden name="search" value="';
      echo htmlspecialchars($search);
      echo '">';
      echo '<INPUT TYPE=hidden NAME="ui" VALUE="' . $ui . '">';
      echo '<INPUT TYPE=hidden NAME="sgn" VALUE="' . $sgn . '">';
      echo '<INPUT TYPE=hidden NAME="type" VALUE="' . $type . '">';
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
echo ' onclick="var nms = document.getElementsByName(' . "'search'" . ');nms[0].value=' . "'*'" . ';nms[1].value=' . "'*'" . ';">';
echo getSignTitle(138,"ui",2);
echo '</td>';
echo '</tr>';

echo '</table>';

if (fswQuery($qsearch)) {
  $re = query2regex($qsearch);
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
    }
  }
  
  $input = get_spml($type,$id);
  
  foreach ($re as $pattern){
    echo "<p>" . $pattern;
    preg_match_all($pattern, $input, $matches);
    $input = implode(array_unique($matches[0]),' ');
  }
$words = explode(' ',$input);
foreach ($words as $word){
  echo '<p><img src="' . $swis_glyphogram . '?text=' . fsw2ksw($word) . '">' . $word . '<br>' . fsw2ksw($word);
}
  

die();
  echo '<p>' . $re[0];
  echo '<p>' . $re[1];
  die();
  $qsearch = str_replace('Q','',$qsearch);
  $parts = str_split($qsearch,8);
  foreach ($parts as $part){
    $base = substr($part,1,3);
    $fill = substr($part,4,1);
    $rotate = substr($part,5,1);
    $x = substr($part,6,1);
    $y = substr($part,7,1);
    
    //now build query...
    echo "<p>$part should equal $base$fill$rotate$x$y";
/*
    if ($fill>0 && $rotate>0) {
      $view = $key;
      $type = 'exact';
    } else if ($fill>0 && $rotate==0) {
      $view = $base . ($fill-1) . '0';
      $type = 'fill';
    } else if ($fill==0 && $rotate>0) {
      $view = base2view($base);
      $view = substr($view,0,4) . dechex($rotate-1);
      $type = 'rotation';
    } else {
      $view = base2view($base);
      $type = 'any';
    }    
    $syms[]=array($view, $type, $base,$fill,$rotate,$x,$y);
*/
  }
  
  //now the symbol table
  echo '<p><table cellpadding=3 width=100% border=1><tr>';
  echo '<th colspan=2>' . getSignTitle(70,"ui",2) . '</th>';
  echo '</tr>';
  foreach($syms as $sym){
    echo '<tr>';
    echo '<td><img src="' . $swis_glyph . '?key=' . $sym[0] . '">';
    echo '<td valign=top>' . $sym[1] . '</td><td>';
    echo '</td></tr>';
  }
  echo '</table>';
  echo '<input type=hidden name=bldSearch value="' . $bldSearch . '">';
}
echo '</td><td>';
echo '<button type="submit">';
echo displayEntry(16,"i","ui",0,2);
echo '</button>';
echo '</td></tr></table>';
echo '</form>';


$sArray = array();
$bArray = array();
/*@<<text search>>*/
/*@+node:ses.20070127213102.1:<<text search>>*/
if (trim($search)!=""){
//include terms archived array
  include $sgndir . ".trm.php";
  foreach($terms as $word => $ids){
    $pos=strpos(mb_strtolower($word), mb_strtolower($search));
    if ((($pos===0) AND ($type=="start")) or (($pos!==FALSE)AND($type=="any")) or ($search=="*") or (($type=="exact")and (mb_strtolower($word)==mb_strtolower($search)))){
      $sArray = array_unique(array_merge($sArray,$ids));
    }
  }
}


/*@-node:ses.20070127213102.1:<<text search>>*/
/*@nl*/
/*@<<symbol search>>*/
/*@+node:ses.20070127213102.2:<<symbol search>>*/
if($bldSearch){

  // split build variable into symbol,x,y
  $sbld = split(",",$bldSearch);
  $cnt = count($sbld);
  $cnt = $cnt - ($cnt%5);
  $base=array();
  $rot=array();
  $fill=array();
  $x = array();
  $y = array();
  for ($i=0;$i<$cnt;$i++){
    $key = id2key($sbld[$i]);
    $base[] = substr($key,0,3);
    $i++;
    $fill[]=$sbld[$i];
    $i++;
    $rot[]=$sbld[$i];
    $i++;
    $x[]=$sbld[$i];
    $i++;
    $y[]=$sbld[$i];
  }

include $sgndir . ".sym.php";

  //cycle through search stuff
  $found=0;
  $searchCount= count($base);
  $exact_bsw = array();
  for ($s=0;$s<$searchCount;$s++){
    $m = "m" . $s;  //$s arrays of bases
    $$m = array();
    //need to match bases
    $$m = $symfreq[$base[$s]];
    $ubase = $base[$s];
    $ufill = $fill[$s];
    $urot = $rot[$s];
    if ($ufill>0 && $urot>0) {
      //exact
      $ifill = intval($ufill) -1;
      $hrot = dechex(intval($urot)-1);
      $key = $ubase . $ifill . $hrot;
      $exact_bsw[] = key2bsw($key);
    }
  }


  $bArray=$m0;

  for ($s=1;$s<$searchCount;$s++){
    $m = "m" . $s;
    $bArray=array_intersect($bArray,$$m); 
  }

}
//merge results...
if (trim($search)!="" && $bldSearch){
  $arrayM = array_intersect($sArray,$bArray);
} else if (trim($search)!="") {
  $arrayM = $sArray;
} else {
  $arrayM = $bArray;
}

//display signs
$Count = count($arrayM);
if ($Count){
  $array = array();
  foreach ($arrayM as $key => $val){
    $sign = readSign($val);
    //not check BSW for exact matches
    $bok = true;
//    foreach($exact_bsw as $ubsw){
//      $pos = strpos($sign['bsw'], $ubsw);
//      if ($pos === false) {
//        $bok=false;
//      }
//    }
    if ($bok){
      $ksw = $sign["ksw"];
      if ($ksw) $array[$ksw]=$val;
    }
  }
  ksort($array);
  reset($array);
  $rows=($Count/5)-.2;
  $Count=1;
  echo "<table border=1 cellpadding=5>";
  foreach ($array as $ksw => $val){
  if ($Count==1) { echo"<td valign=top><center>";}
  echo "<a href='$PHP_SELF?ui=" . $ui . "&sgn=" . $sgn . "&sid=" . $val . "&search=" . htmlspecialchars($search) . "&type=$type&bldSearch=$bldSearch'>";
  echo "<img src='" . $swis_glyphogram . "?text=" . $ksw . "&size=.5" . $glyph_line . "' alt=$val border=0></a><br><br>";
  if ($Count>$rows) {
    $Count=0;
    echo "</td>"; 
  }
  $Count++;
 }
 echo "</td></tr></table>";
} else {
 if ($search || $bldSearch) {echo "<p>" . getSignTitle(75,"ui",2);}
}

if (is_array($_SESSION['export_list'])){
  if ($count){
    echo '<p><form action="' . $PHP_SELF . '" method="POST">';
    echo '<input type=hidden name="search" value="';
    echo htmlspecialchars($search);
    echo '">';
    echo '<INPUT TYPE=hidden NAME="ui" VALUE="' . $ui . '">';
    echo '<INPUT TYPE=hidden NAME="sgn" VALUE="' . $sgn . '">';
    echo '<INPUT TYPE=hidden NAME="type" VALUE="' . $type . '">';
    echo '<INPUT TYPE=hidden NAME="bldSearch" VALUE="' . $bldSearch . '">';
    echo '<INPUT TYPE=hidden NAME="export_list" VALUE="' . $idlink . '">';
    echo '<button type="submit">';
    echo displayEntry(106,"i","ui",0,2);
    echo '</button>';
    echo '</form>';
  }
}
include 'footer.php';
/*@@last*/
/*@nonl*/
/*@-node:slevin.20070120113758:@thin W:/www/searchsign.php*/
/*@-leo*/
?>
