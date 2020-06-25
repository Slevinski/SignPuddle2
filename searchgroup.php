<?php
$rSL = 1;
include 'styleA.php';
include 'styleB.php';

$subHead=displayEntry(156,'t',"ui");
$qsearch = $_REQUEST['qsearch'];
if (!$qsearch) $qsearch="Q";
$grp = $_REQUEST['grp'];

include("header.php");

$trange = $_REQUEST['trange']; //temporary symbol group range
$tquery = str_replace("Q", "Q" . $trange,$qsearch);
if (fswQuery($tquery)) {
  echo query2table($tquery);
}


$sgntxt = $_REQUEST['sgntxt'];
if ($sgntxt) {
  $input = $sgntxt;
} else {
  $input = puddle_spf();
}
  
$cnt = '';

$output = query_counts(str_replace("Q", "Q" . $trange,$qsearch),$input);
$words = $output[0];

echo sprintf(getSignTitle(184,"ui",2),count($words));
//with ' . $output[2] . ' occurance(s)';
echo ' <a href="searchquery.php?ui=' . $ui . '&sgn=' . $sgn . '&qsearch=' . $tquery . '">';
echo getSignTitle(185,"ui",2) . '</a> ' . getSignTitle(186,"ui",2); 
if ($trange){
  $input = implode(' ',$words);
  $trange = str_replace("R",'',$trange);
  $trange = str_replace("t",'',$trange);
  $len = strlen($trange);
  if ($len!=6) break;
  $min = hexdec(substr($trange,0,3));
  $max = hexdec(substr($trange,3,6));


  $bsinfo = array();
  for ($i=$min;$i<=$max;$i++){
    $qsym = 'S' . dechex($i) . 'uu';
    if (strpos($qrange,'V')){
      $qnew = str_replace("V",$qsym . "V",$qsearch);
    } else {
      $qnew = $qsearch . $qsym;
    }
    $output = query_counts("Q" . $qsym,$input);
    $words = $output[0];

    $info = array();
    $info[]=base2view(dechex($i));
    $info[]=count($words);
    $info[]=$qnew;
    $bsinfo[]=$info;
  }

  $count = $max-$min +1;
  $cols = intval(($count-1)/10) +1;
  //need a grid, top level...
  echo "<h2>" . getSignTitle(188,"ui",2) . "</h2>";
  echo "<p><table cellpadding=4 border=1>";
  for ($row=0;$row<10;$row++){
    echo "<tr>";
    for ($col=0;$col<$cols;$col++){
      echo "<td align=middle width=70>";
      $i = $row + $col*10;
      if ($i<$count) {
        echo '<a href="searchgroup.php?qsearch=' . $bsinfo[$i][2] . '">';
        if ($bsinfo[$i][1]>0) {
          echo '<img border=0 src="' . $swis_glyph . '?key=' . $bsinfo[$i][0] . '"><br>';
          echo '(' . $bsinfo[$i][1] . ')';
        } else {
          echo '<img border=0 src="' . $swis_glyph . '?key=' . $bsinfo[$i][0] . '&line=aaaaaa"><br>';
        }
        echo "</a>";
      }
      echo "</td>";
    }
    echo "</tr>";
  }
  echo "</table>";
}
if (!$trange) {
  $input = implode(' ',$words);
  $count = count($sg_list);  // should always be 30
  $sginfo = array();
  foreach ($sg_list as $i=>$sg){
    $qrange = 'R' . $sg . 't';
    $n = $i +1;
    if ($n<$count) {
      $qrange .= dechex(hexdec($sg_list[$n])-1);
    } else {
      $qrange .= '38b';
    }
    $qnew = str_replace("Q","Q" . $qrange,$qsearch);
    $output = query_counts($qnew,$input);
    $words = $output[0];

    $info = array();
    $info[]=$sg . '00';
    $info[]=count($words);
    $info[]=$qrange;
    $sginfo[]=$info;
  }

  //need a grid, top level...
  if (!$qsearch) $qsearch="Q";
  echo "<h2>" . getSignTitle(187,"ui",2) . "</h2>";
  echo "<p><table cellpadding=4 border=1>";
  for ($row=0;$row<10;$row++){
    echo "<tr>";
    for ($col=0;$col<3;$col++){
      echo "<td align=middle width=70>";
      $i = $row + $col*10;
      echo '<a href="searchgroup.php?qsearch=' . $qsearch . '&trange=' . $sginfo[$i][2] . '">';
      if ($sginfo[$i][1]>0) {
        echo '<img border=0 src="' . $swis_glyph . '?key=' . $sginfo[$i][0] . '"><br>';
        echo '(' . $sginfo[$i][1] . ')';
      } else {
        echo '<img border=0 src="' . $swis_glyph . '?key=' . $sginfo[$i][0] . '&line=999999"><br>';
      }
      echo "</a></td>";
    }
    echo "</tr>";
  }
  echo "</table>";
}

include 'footer.php';
?>
