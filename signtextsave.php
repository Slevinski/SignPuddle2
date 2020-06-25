<?php
$rSL = 0;
include 'styleA.php';
include 'styleB.php';
//$subHead=displayEntry(9,'t',"ui");
$subHead='Save SignText to Puddle';
include 'header.php';

$sgntxt = $_REQUEST['sgntxt'];
$list = $_REQUEST['list'];
//$list = str_replace("\n","%0D%0A" ,$list);
//$list = str_replace("%0D%0A%0D%0A","%0D%0A" ,$list);
$list=str_replace("\r","",$list);
if ($list){
  $sgntxt= lst2ksw($list);
}
if (fswText($sgntxt)) $sgntxt =fsw2ksw($sgntxt);
if (!kswLayout($sgntxt)) die("Data error sgntxt: " . $sgntxt);

/**
 * Part 2, display the KSW with options...
 */
if ($sgntxt){

  stDisplay($sgntxt);


  echo '<h2>' . getSignTitle(58,"ui") . '</h2>';

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
    echo '<form method=get action="canvas.php">';
    echo '<input type=hidden name=ui value=' . $ui . '>';
    echo '<input type=hidden name=sgn value=' . $sgn . '>';
    echo '<input type=hidden name=sgntxt value="' . $sgntxt . '">';
    echo '<input type=hidden name=action value="Save">';
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


        $display .=  '<form method=get action="canvas.php">';
        if (!$ui) {
          $display .=  '<input type=hidden name=ui value=' . $sgn . '>';
        } else {
          $display .=  '<input type=hidden name=ui value=' . $ui . '>';
        }
        $display .=  '<input type=hidden name=sgn value=' . $entry . '>';
        $display .=  '<input type=hidden name=sgntxt value="' . $sgntxt . '">';
        $display .=  '<input type=hidden name=action value="Save">';
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
      echo '<form method=get action="' . $sponline . 'signtextsave.php">';
      echo '<input type=hidden name=ui value=' . $dui . '>';
      echo '<input type=hidden name=sgn value="">';
      echo '<input type=hidden name=sgntxt value="' . $sgntxt . '">';
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
      echo '<form method="POST" action="' . $_SESSION['local'] . 'signtextsave.php">';
      echo "<input type='hidden' name='ui' value='$dui'>";
      echo "<input type='hidden' name='sgn' value='0'>";
      echo "<input type='hidden' name='sgntxt' value='" . $sgntxt . "'>";
      echo '<button type="submit">';
      //echo '<img src="library/icons/CopyLocalSignPuddle.png">';
      echo displayEntry(160,"i","ui",0,5);
      echo '</button>';
      echo "</form>";
      echo "</td></tr></table>";
  }

  echo '<hr><h2>Other sign text options</h2>';
  stOptions($sgntxt);
}

include 'footer.php'; 
?>
