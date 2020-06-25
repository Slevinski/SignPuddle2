<?php
$rSL = 1;
include 'styleA.php';
include 'styleB.php';
//$subHead=displayEntry(9,'t',"ui");
$subHead='Search for Sign in Puddle';
include 'header.php';

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

if (fswQuery($qsearch)) {
  echo query2table($qsearch);
}

$ksw = $_REQUEST['ksw'];
if ($ksw){ // exact search
  echo "<table cellpadding=15 border=1><tr>";
  echo "<th>Exact Match</th></tr>";
  echo '<tr><td valign=top><img src="' . $swis_glyphogram . '?ksw=' . $ksw . '"></td>';
  echo "</tr></table><br><br>";  
}

if ($qsearch) $var = 'qsearch';
if ($ksw) $var = 'ksw';
if ($qsearch || $ksw){
  echo '<h2>' . getSignTitle(198,"ui") . '</h2>';

  if (isPP()){
    $here = getSignTitle(162,"ui",2);
    $there = getSignTitle(159,"ui",2);
  } else {
    $here = getSignTitle(159,"ui",2);
    $there = getSignTitle(162,"ui",2);
  }

  if ($sgn){
    echo '<h3>' . getSignTitle(196,"ui",2) . ' ' . $here . '</h3>';

    echo "<table cellpadding=5><tr><td>";
    echo '<form method=get action="searchquery.php">';
    echo '<input type=hidden name=ui value=' . $ui . '>';
    echo '<input type=hidden name=sgn value=' . $sgn . '>';
    echo '<input type=hidden name=' . $var . ' value="' . $$var . '">';
    echo '<button type=submit>';
    if ($ui) {
      echo displayEntry(0,"a","sgn",$sgn);
    } else {
      echo displayEntry(0,"a","ui",$sgn);
    }
    echo "</button>";
    echo "</form></td></tr></table><br>";
  }


  echo '<h3>' . getSignTitle(197,"ui",2) . ' ' . $here . '</h3>';
  $display = "<table cellpadding=5>";
  $flag_lines = getFlagLines();
  foreach ($flag_lines as $line){
    $display .="<tr>";
    foreach ($line as $entry){
      $display .= '<td valign=middle>';

      if ($entry && ($sgn!=$entry || !$ui)){


        $display .=  '<form method=get action="searchquery.php">';
        if (!$ui) {
          $display .=  '<input type=hidden name=ui value=' . $sgn . '>';
        } else {
          $display .=  '<input type=hidden name=ui value=' . $ui . '>';
        }
        $display .=  '<input type=hidden name=sgn value=' . $entry . '>';
        $display .= '<input type=hidden name=' . $var . ' value="' . $$var . '">';
        $display .=  '<button type=submit>';
        $display .=  displayEntry(0,"a","sgn",$entry);
        $display .=  "</button>";
        $display .=  "</form>";

      }
      $display .= "</td>";
    }
    $display .= "</tr>";
  }
  $display .= "</table>";

  echo $display;


  if (strtolower($host)<>$sponline){
    if (isPP()){
      echo '<h3>' . getSignTitle(159,"ui",2) . '</h3>';

      echo "<table cellpadding=5><tr><td>";
      echo '<form method=get action="' . $sponline . 'signsearch.php">';
      echo '<input type=hidden name=ui value=' . $dui . '>';
      echo '<input type=hidden name=sgn value="">';
      echo '<input type=hidden name=' . $var . ' value="' . $$var . '">';
      echo '<input type=hidden name=local value="' . $host . '">';
      echo '<button type=submit>';
      echo displayEntry(159,"i","ui",0,5);
      echo "</button>";
      echo "</form><br>";
      echo "</td></tr></table>";
  
      echo "<br>";
    }
  }

  if ($_SESSION['local']){
      echo '<h3>' . getSignTitle(162,"ui",2) . '</h3>';
      echo "<table cellpadding=5><tr><td>";
      echo '<form method="POST" action="' . $_SESSION['local'] . 'signsearch.php">';
      echo "<input type='hidden' name='ui' value='$dui'>";
      echo "<input type='hidden' name='sgn' value=''>";
      echo "<input type='hidden' name='" . $var . "' value='" . $$var . "'>";
      echo '<button type="submit">';
      //echo '<img src="library/icons/CopyLocalSignPuddle.png">';
      echo displayEntry(162,"i","ui",0,5);
      echo '</button>';
      echo "</form>";
      echo "</td></tr></table>";
  }

  if($ksw) {
    echo "<hr><h2>Other sign options</h2>";
    sgnOptions($ksw);
  }

}

include 'footer.php'; 
?>
