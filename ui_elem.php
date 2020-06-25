<?php
if ($ui) {

  $uiguide = displayEntry(154,"i","ui",0,5);
  $uilang = displayEntry(155,"i","ui",0,5);

  $sid ='';
  $added = array();
  $elements = $_SESSION['uiElements'];
  foreach ($elements as $index =>$list){
    foreach ($list as $uid){
      if (!in_array($uid,$added)){
        $sid .= $uid . ',';
        $added[]=$uid;
      }
    }
  }

  if (!strpos($uiguide, "table")){
    echo "<a href='uimanual.php?ui=0&sgn=" . $ui . "&sid=" . $sid . "'>";
    echo $uiguide;
    echo "</a><br><br>";
  } else { //use a form for IE
    echo '<form method="post" action="uimanual.php">';
    echo "<input type='hidden' name='ui' value='0'>";
    echo "<input type='hidden' name='sgn' value='$ui'>";
    echo "<input type='hidden' name='sid' value='$sid'>";
    echo '<button type="submit">';
    echo $uiguide;
    echo '</button>';
    echo "</form>";
  }
    
  if (!strpos($uiguide, "table")){
    echo "<a href='index.php?ui=0&sgn=0'>";
    echo $uilang;
    echo "</a><br><br>";
  } else { //use a form for IE
    echo '<form method="post" action="index.php">';
    echo "<input type='hidden' name='ui' value='0'>";
    echo "<input type='hidden' name='sgn' value='0'>";
    echo '<button type="submit">';
    echo $uilang;
    echo '</button>';
    echo "</form>";
  }

//  echo '<br><a href="uimanual.php?ui=0&sgn=' . $ui . '&sid=' . $sid . '">' . $uiguide . '</a>';
//  echo '<br><br><a href="index.php?ui=0&sgn=0">' . $uilang . '</a>';
}
?>