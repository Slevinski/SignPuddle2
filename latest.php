<?php
/*
#@+leo-ver=4-thin
#@+node:slevin.20070120164946:@thin W:/www/latest.php
#@@first
#@@first
#@delims /* */ 
$rSL = 1;
include 'styleA.php';
include 'styleB.php';

include 'header.php';
$total = $_REQUEST['total'];

  echo "<table border cellpadding=2><tr>";
  echo "<th>" . getSignTitle(63,"ui") . ":</th>"; 
  echo "<td><a href='$PHP_SELF?total=9'>10</a></td>"; 
  echo "<td><a href='$PHP_SELF?total=19'>20</a></td>"; 
  echo "<td><a href='$PHP_SELF?total=49'>50</a></td>"; 
  echo "<td><a href='$PHP_SELF?total=99'>100</a></td>"; 
  echo "<td><a href='$PHP_SELF?total=199'>200</a></td>"; 
  echo "<td><a href='$PHP_SELF'>" . getSignTitle(64,"ui") . "</a></td>"; 
  echo "</tr></table>"; 

  echo "<hr><br>";
if ($total==''){
  echo "<center>";
    $files = latestEntries();
    $now = time();
    $countMonth = 0 ;
    $countWeek = 0 ;
    $countDay = 0 ;
    while (list($key, $val) = each($files)) {
      if ($val > ($now - 30*24*60*60)) {
        $countMonth++; 
        if ($val > ($now - 7*24*60*60)) {
          $countWeek++; 
          if ($val > ($now - 24*60*60)) {
            $countDay++; 
          }
        }
      }
    }
    echo "<b>" . countDict() . " " . getSignTitle(59,"ui") .  "</b><br><br>";
    echo "<a href='latest.php?total=" . ($countDay-1) . "'>" . $countDay . " " . getSignTitle(60,"ui") . "</a><br>";
    echo "<a href='latest.php?total=" . ($countWeek-1) . "'>" . $countWeek . " " . getSignTitle(61,"ui") . "</a><br>";
    echo "<a href='latest.php?total=". ($countMonth-1) . "'>" . $countMonth . " " . getSignTitle(62,"ui") . "</a><br>";
    echo "<br>";
//    echo "<a href='lost.php?ui=" . $ui . "&sgn=" . $sgn . "'>";
//    echo "Check for signs without terms</a><br>";
//    echo "<a href='lost2.php?ui=" . $ui . "&sgn=" . $sgn . "'>";
//    echo "Check for signs with images but without symbols</a><br>";
//    echo "<a href='lost3.php?ui=" . $ui . "&sgn=" . $sgn . "'>";
//    echo "Check for signs with spellings but without sequences</a><br>";
    echo "</center>";
} else {
  $files = latestEntries();
  $count=0;
  while (list($key, $val) = each($files)) {
    if ($count <= $total){
      displaySWFull($key);
      echo "<br><hr><br>";
      $count++;
    }
  }

}
include 'footer.php';
/*@@last*/
/*@-node:slevin.20070120164946:@thin W:/www/latest.php*/
/*@-leo*/
?>
