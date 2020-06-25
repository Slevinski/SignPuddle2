<?php
$rSL = 1;
include 'styleA.php';
include 'styleB.php';

//$subHead="Symbol Frequency";
$subHead=displayEntry(7,'t',"ui");
$cat = $_REQUEST['cat'];
$sg = $_REQUEST['sg'];
$bs = $_REQUEST['bs'];

if ($cat==3 && !$sg) $sg='2f7';

$sgntxt = $_REQUEST['sgntxt'];
if ($sgntxt) {
  $input = $sgntxt;
} else {
  $input = puddle_spf();
}

include("header.php");

$re = query2regex('Q');
$pattern = $re[0];
$count = preg_match_all($pattern, $input, $matches);

echo sprintf(getSignTitle(189,"ui",2), $count);
//display categories 
echo "<h2>1) " . getSignTitle(79,"ui",2) . "</h2>";

$cats = array();
for ($i=1;$i<8;$i++){
  $cats[$i]=id2key('0' . $i . '-01-001-01');
}

echo "<table cellpadding=10><tr>";
for ($i=1;$i<6;$i++){
  $tCat = $cats[$i];
  echo '<td valign=bottom align=middle><a href="' . $PHP_SELF . '?ui=' . $ui . '&sgn=' . $sgn . '&cat=' . $i . '">';
  echo '<img border=0 src="glyph.php?key=' . $tCat . $glyph_line . '">';
  echo '<br>';
  //echo '<font size=-1>' . iswaName('0' . $i,'en') . "</font>";
  echo '</a></td>';
}
echo '</tr></table>';

if ($cat){

  $sBase = substr($cats[$cat],0,3);
  $eBase = substr($cats[$cat+1],0,3);
  $eBase = dechex(hexdec($eBase)-1);

  //need symbol group list...
  if ($cat!=3){
    echo "<hr><br>";
    echo "<h2>2) " . getSignTitle(190,"ui",2) . "</h2>";
    echo "<table cellpadding=0><tr>";
    foreach ($sg_list as $symgrp){
      if (hexdec($symgrp)>= hexdec($sBase) && hexdec($symgrp)<= hexdec($eBase)){
        echo '<td valign=bottom align=middle><a href="' . $PHP_SELF . '?ui=' . $ui . '&sgn=' . $sgn . '&cat=' . $cat . '&sg=' . $symgrp . '">';
        echo '<img border=0 src="glyph.php?key=' . base2view($symgrp) . '">';
        echo '</a></td><td>&nbsp;&nbsp;</td>';
      }
    }
    echo "</tr><tr>";
    foreach ($sg_list as $symgrp){
      if (hexdec($symgrp)>= hexdec($sBase) && hexdec($symgrp)<= hexdec($eBase)){
        echo '<td valign=top align=middle><a href="' . $PHP_SELF . '?ui=' . $ui . '&sgn=' . $sgn . '&cat=' . $cat . '&sg=' . $symgrp . '">';
        $id = key2id($symgrp . '00',1);
        //echo '<font size=-1>' . iswaName(substr($id,0,5),'en') . '</font>';
        echo '</a></td><td></td>';
      }
    }
    echo '</tr></table>';
  }

  if ($sg){
    $key = array_search($sg, $sg_list);
    $sBase = $sg_list[$key];
    $eBase = $sg_list[$key+1];
    $eBase = dechex(hexdec($eBase)-1);

    echo "<hr><br>";
    echo "<h2>3) " . getSignTitle(191,"ui",2) . "</h2>";
    $qsearch = 'QR' . $sBase . 't' . $eBase;
    //need the range;
    $re = query2regex($qsearch);
    $pattern = $re[0];
    $count = preg_match_all($pattern, $input, $matches);
    $output = implode(array_unique($matches[0]),' ');
    $cnt = (hexdec($eBase) - hexdec($sBase) +1);
    $remain = (10 - $cnt%10)%10;
    echo "<table cellpadding=0>";
    $dBase = hexdec($sBase);
    for ($r=0;$r<=intval($cnt/10);$r++){
      echo "<tr>";
      for ($c=0;$c<10;$c++){
        echo "<td valign=bottom align=middle>";
        $i = $r*10 + $c;
        $tBase = $dBase + $i;
        if ($tBase <= hexdec($eBase)){
          $pattern = '/S' . dechex($tBase) . '[0-5][0-9a-f]/';
          $count = preg_match_all($pattern, $input, $matches);
          if ($count){
            echo '<a href="' . $PHP_SELF . '?ui=' . $ui . '&sgn=' . $sgn . '&cat=' . $cat . '&sg=' . $sg . '&bs=' . dechex($tBase) . '">';
            echo '<img border=0 src="glyph.php?key=' . base2view(dechex($tBase)) . '">';
            echo "</a>";
          } else {
            echo '<img border=0 src="glyph.php?key=' . base2view(dechex($tBase)) . '&line=aaaaaa">';
          }
        }
        echo "</td><td>&nbsp;&nbsp;</td>";
      }
      echo "</tr><tr>";
      for ($c=0;$c<10;$c++){
        echo "<td valign=top align=middle>";
        $i = $r*10 + $c;
        $tBase = $dBase + $i;
        if ($tBase <= hexdec($eBase)){
          $pattern = '/S' . dechex($tBase) . '[0-5][0-9a-f]/';
          $count = preg_match_all($pattern, $input, $matches);
          if ($count){
            echo '<a href="' . $PHP_SELF . '?ui=' . $ui . '&sgn=' . $sgn . '&cat=' . $cat . '&sg=' . $sg . '&bs=' . dechex($tBase) . '">';
            $id = key2id(dechex($tBase) . '00',1);
            //echo '<font size=-1>' . iswaName(substr($id,0,12),'en') . "</font>";
            echo "<br>(" . $count . ")";
            echo "</a>";
          } else {
            $id = key2id(dechex($tBase) . '00',1);
            //echo '<font color="#aaaaaa" size=-1>' . iswaName(substr($id,0,12),'en') . "</font>";
          }
        }
        echo "</td><td></td>";
      }
      echo "</tr><tr>";
      echo "<td colspan=20>&nbsp;</td>";
      echo "</tr>";
    }
    echo "</table>";
    
    if ($bs){
      echo "<hr><br>";
      echo "<h2>" . getSignTitle(7,"ui",2) . "</h2>";
      echo "<table cellpadding=10>";
      $dBase = hexdec($sBase);
      for ($r=0;$r<16;$r++){
        echo "<tr>";
        for ($c=0;$c<6;$c++){
          echo "<td valign=bottom align=middle>";
          $i = $r*10 + $c;
          $tBase = $bs . dechex($c) . dechex($r);
          if (validKey($tBase)){
            $pattern = '/S' . $tBase . '/';
            $count = preg_match_all($pattern, $input, $matches);
            if ($count){
              echo '<a href="searchquery.php?ui=' . $ui . '&sgn=' . $sgn . '&qsearch=QS' . $tBase . '">';
              echo '<img border=0 src="glyph.php?key=' . $tBase . '">';
              echo "<br>(" . $count . ")";
              echo "</a>";
            } else {
              echo '<img border=0 src="glyph.php?key=' . $tBase . '&line=aaaaaa"><br><br>';
            }
          }
          echo "</td>";
        }
        echo "</tr>";
      }
      echo "</table>";
    }
  }

}

include 'footer.php';
?>
