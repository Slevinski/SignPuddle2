<?php
error_reporting(-1);
$rSL = 1;
include 'styleA.php';
include 'styleB.php';
$subHead="Search for Signs";
//$subHead=displayEntry(5,'t',"ui",0,1);

include 'header.php';
//echo "<center>";

$type = $_REQUEST['type']; 
$sid = explode(",",$_REQUEST['sid']); 
$fuzz = $_REQUEST['fuzz'];
if ($fuzz=='') $fuzz=20;

$bldSearch = $_REQUEST['bldSearch'];
$qsearch = $_REQUEST['qsearch'];

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
//display options
$minX = 999;
$minY = 999;

// section that creates ksw, should happen on query load
if (fswQuery($qsearch)) {
  echo query2table($qsearch);
}

$ksw = $_REQUEST['ksw'];

//  exact search results or query search results
if ($ksw){ // exact search
  echo "<table cellpadding=15 border=1><tr>";
  echo "<th>Exact Match</th></tr>";
  echo '<tr><td valign=top><img src="glyphogram.php?font=svg&ksw=' . $ksw . '"></td>';
  echo "</tr></table><br><br>";  
  $detail = substr(ksw2fsw($ksw),1);
  $sgntxt = $_REQUEST['sgntxt'];
  if ($sgntxt) {
    $input = $sgntxt;
  } else {
    $input = puddle_spf();
  }

  $len = strlen($input);
  $offset = -1;
  $sid = array();
  while(1){
    $offset = strpos($input,$detail,$offset+1);
    if ($offset===false) break;
    $entrypos = strrpos($input,'<entry ',$offset-$len);
    $idpos = strpos($input,' id="',$entrypos);
    $idend = strpos($input,'"', $idpos+5);
//    echo "<p>search for $detail at position " . $offset . " with entry starting at " . $entrypos;
    $id=substr($input,$idpos+5,($idend-$idpos-5));
//    echo " with id of " . $id;
    $sid[]=$id;
  }
  $sid = array_unique($sid);
  echo "<hr><br>";
  if (count($sid)){
    foreach ($sid as $id){
      if(trim($id)){  
        echo displaySWFull($id);
        echo "<br><hr><br>";
      }
    }
  } else {
   echo "<p>" . getSignTitle(75,"ui",2);
  }
  
} else if (fswQuery($qsearch)){ //query search results
  $re = query2regex($qsearch,$fuzz);
  $sgntxt = $_REQUEST['sgntxt'];
  if ($sgntxt) {
    $input = $sgntxt;
  } else {
    $input = puddle_spf();
  }
  
  $cnt = '';
  foreach ($re as $pattern){
    $count = preg_match_all($pattern, $input, $matches);
    // this gets word counts for the first match only match!
    // following searches are subset
    if (!is_array($cnt)){
      $cnt = array();
      foreach ($matches[0] as $match){
        $match[0]='M';
        $cnt[$match]++;
      }
    }
    $input = implode(array_unique($matches[0]),' ');
  }
  $input = str_replace('L','M',$input);
  $input = str_replace('R','M',$input);
  $words = array_unique(explode(' ',$input));
//display signs
  $wcount = count($words);
  if (!trim($input)) $wcount=0;
  if ($wcount){
    $rows=($wcount/5)-.2;
    $Count=1;
    echo "<table border=1 cellpadding=5>";
    foreach ($words as $word){
      $word[0]='M';
      if ($Count==1) { echo"<td valign=top><center>";}
      echo '<a href="' . $PHP_SELF . '?ui=' . $ui . '&sgn=' . $sgn . '&ksw=' . fsw2ksw($word) . '&qsearch=' . $qsearch . '">';
      echo '<img src="glyphogram.php?text=' . fsw2ksw($word) . '&size=1.5&font=svg' . $glyph_line . '" border=0>';
      if ($cnt[$word]>1) echo '<br>(' . $cnt[$word] . ')</a>';
      echo '<br><br>';
      if ($Count>$rows) {
        $Count=0;
        echo "</td>"; 
      }
      $Count++;
     }
   echo "</td></tr></table>";
  } else {
   echo "<p>" . getSignTitle(75,"ui",2);
  }
}
include 'footer.php';
?>
