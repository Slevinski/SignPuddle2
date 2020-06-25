<?php
echo '<a href="index.php?ui=' . $ui . '&sgn=' . $sgn . '">';
if ($sgn){
  echo displayEntry(0,'a');
} else {
  if ($ui==0){
    echo "<table cellpadding=5 border=1><tr><td align=center>User Interface<br>Choices</td></tr></table>";
  } else {
    if (isPP()){
      echo displayEntry(162,"i","ui");
    } else {
      echo displayEntry(3,"i","ui");
    }
  }
}
echo '</a>';
?>
