<?php
/*
#@+leo-ver=4-thin
#@+node:slevin.20070120112345:@thin W:/www/flags.php
#@@first
#@@first
#@delims /* */ 

echo "<br><br><hr><br>";

//output flags 
echo "<center>";

$flag_lines = getFlagLines();
//red umbrella link
$sphp = getSetting(3,$ui);
if (trim($sphp)!=""){
  $UmHome= '<a href="' . $sphp . '">';
  
} else {
  $UmHome =  '<a href="index.php?ui=';
  if ((substr($PHP_SELF,-9,9)=='index.php') and ($sgn==0)){
    $UmHome .= '0';
  } else {
    $UmHome .= $ui; 
  }
  $UmHome .= '&sgn=0">';
}
$UmHome .= displayEntry(151,"i","ui",0,2);
$UmHome .= "</a>";

echo "<table cellpadding=5>";

foreach ($flag_lines as $line){
  echo '<tr>';
  foreach($line as $entry){
    echo '<td valign=middle>';

    if ($entry==="0"){
      echo $UmHome;
    } else {
      if ($entry){
        echo '<a href="index.php?';
        if ($sgn>0 or $ui>0) {
          if ($ui==0){ 
            echo 'ui=' . $sgn . '&';
          } else {
            echo 'ui=' . $ui . '&';
          }
        } else {
          echo 'ui=1&';
        }
        echo 'sgn=' . $entry . '">';
        echo displayEntry(0,"i","sgn",$entry);
        echo "</a>";
      }
    }
    echo "</td>";
  }
  echo "</tr>";
}
echo "</table>";

echo "</center>";
/*@@last*/

/*@-node:slevin.20070120112345:@thin W:/www/flags.php*/
/*@-leo*/
?>
