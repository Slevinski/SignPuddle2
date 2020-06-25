<?php
/*
#@+leo-ver=4-thin
#@+node:slevin.20070119160240:@thin W:/www/header.php
#@@first
#@@first
#@delims /* */ 

$i=0;

//connect sign puddle online link
if (isPP()){
  $icons[$i]['page'] = $sponline . 'index.php?ui=' . $ui . '&sgn=0&local=' . $host;
  //$icons[$i]['img'] = "<img src='library/icons/ConnectSignPuddle.png' border=0>";
  $icons[$i]['img'] = displayEntry(157,"i","ui",0,5);
  $icons[$i]['txt'] = "";
  $i++;
}

//connect sign puddle online link
if ($_SESSION['local']){
  $icons[$i]['page'] = $_SESSION['local'];
  //$icons[$i]['img'] = "<img src='library/icons/BackPersonalPuddle.png' border=0>";
  $icons[$i]['img'] = displayEntry(158,"i","ui",0,5);
  $icons[$i]['txt'] = "";
  $i++;
}

if ($security>0){
  //setup icons for top level
  $icons[$i]['page'] = "searchword.php?ui=$ui&sgn=$sgn";
  //$icons[$i]['img'] = $icon . "/SearchByWords.png";
  $icons[$i]['img'] = displayEntry(4,"i","ui",0,5);
  $icons[$i]['txt'] = "";
  $i++;

  if (substr($spView,-4)=='edit'){
    $icons[$i]['page'] = "searchsign.php?ui=$ui&sgn=$sgn";
    //$icons[$i]['img'] = $icon . "/SearchBySigns.png";
    $icons[$i]['img'] = displayEntry(5,"i","ui",0,5);
    $icons[$i]['txt'] = "";
    $i++;

    $icons[$i]['page'] = "searchsymbol.php?ui=$ui&sgn=$sgn";
    //$icons[$i]['img'] = $icon . "/SearchBySymbols.png";
    $icons[$i]['img'] = displayEntry(6,"i","ui",0,5);
    $icons[$i]['txt'] = "";
    $i++;

    $icons[$i]['page'] = "searchgroup.php?ui=$ui&sgn=$sgn";
    $icons[$i]['img'] = displayEntry(156,"i","ui",0,5);
    $icons[$i]['txt'] = "";
    $i++;

    $icons[$i]['page'] = "frequency.php?ui=$ui&sgn=$sgn";
    //$icons[$i]['img'] = $icon . "/SymbolFrequency.png";
    $icons[$i]['img'] = displayEntry(7,"i","ui",0,5);
    $icons[$i]['txt'] = "";
    $i++;
  }
}

if (substr($spView,-4)=='edit'){
  if ($security>0){
    $icons[$i]['page'] = "translate.php?ui=$ui&sgn=$sgn";
    //$icons[$i]['img'] = $icon . "/Translate.png";
    $icons[$i]['img'] = displayEntry(9,"i","ui",0,5);
    $icons[$i]['txt'] = "";
    $i++;
  }

  if ($security>1){
    $icons[$i]['page'] = "fingerspeller.php?ui=$ui&sgn=$sgn";
    //$icons[$i]['img'] = $icon . "/SignMaker.png";
    $icons[$i]['img'] = displayEntry(325,"i","ui",0,5);
    $icons[$i]['txt'] = "";
    $i++;

    $icons[$i]['page'] = "signmaker.php?ui=$ui&sgn=$sgn";
    //$icons[$i]['img'] = $icon . "/SignMaker.png";
    $icons[$i]['img'] = displayEntry(8,"i","ui",0,5);
    $icons[$i]['txt'] = "";
    $i++;

    $icons[$i]['page'] = "signtext.php?ui=$ui&sgn=$sgn";
    //$icons[$i]['img'] = $icon . "/SignText.png";
    $icons[$i]['img'] = displayEntry(10,"i","ui",0,5);
    $icons[$i]['txt'] = "";
    $i++;
  }
}

if ($security>2){
  $icons[$i]['page'] = "canvas.php?ui=$ui&sgn=$sgn&action=new";
  if ((!$ui && $sgn) || ($ui && $sgn)) {
    $icons[$i]['img'] = displayEntry(111,"i","ui",0,5);
  } else {
    $icons[$i]['img'] = displayEntry(192 ,"i","ui",0,5);
  }
  $icons[$i]['txt'] = "";
  $i++;
}

if ($security>1){
  if ($ui) {$dui=$ui;} else {$dui=1;}
  $icons[$i]['page'] = "import.php?ui=$dui&sgn=$sgn";
  $icons[$i]['img'] = displayEntry(76,"i","ui",0,5);
  $icons[$i]['txt'] = "";
  $i++;
}

if ($security>0){
  $icons[$i]['page'] = "export.php?ui=$ui&sgn=$sgn";
  //$icons[$i]['img'] = $icon . "/Export.png";
  $icons[$i]['img'] = displayEntry(11,"i","ui",0,5);
  $icons[$i]['txt'] = "";
  $i++;
}

/* this section is no longer needed
//remove previous icons if ui puddles or signpuddle home
if ($security>0){
  //connect sign puddle online link
  if (!$sgn && !isPP()){
    $i=0;
    $icons = array();
    //connect sign puddle online link
    //if (isPP()){
    //  $icons[$i]['page'] = $sponline . 'index.php?ui=' . $ui . '&sgn=0&local=' . $host;
    //  //$icons[$i]['img'] = "<img src='library/icons/ConnectSignPuddle.png' border=0>";
    //  $icons[$i]['img'] = displayEntry(157,"i","ui",0,5);
    //  $icons[$i]['txt'] = "";
    //  $i++;
    //}

    //connect sign puddle online link
    if ($_SESSION['local']){
      $icons[$i]['page'] = $_SESSION['local'];
      //$icons[$i]['img'] = "<img src='library/icons/BackPersonalPuddle.png' border=0>";
      $icons[$i]['img'] = displayEntry(158,"i","ui",0,5);
      $icons[$i]['txt'] = "";
      $i++;
    }

    $icons[$i]['page'] = "searchword.php?ui=$ui&sgn=$sgn";
    $icons[$i]['img'] = displayEntry(4,"i","ui",0,5);
    $icons[$i]['txt'] = "";
    $i++;
  }
}
*/

if ($security==5){
  $icons[$i]['page'] = "admin.php?ui=$ui&sgn=$sgn";
  $icons[$i]['img'] = displayEntry(91,"i","ui",0,5);
  $icons[$i]['txt'] = "";
  $i++;
}

if ($_SESSION["puddle_psw"]){
  $icons[$i]['page'] = "logout.php";
  $icons[$i]['img'] = displayEntry(81,"i","ui",0,5);
  $icons[$i]['txt'] = "";
  $i++;
} else {
  if (!isPP()){
    if ($register){
      $icons[$i]['page'] = "register.php";
      $icons[$i]['img'] = displayEntry(101,"i","ui",0,5);
      $icons[$i]['txt'] = "";
      $i++;
    }
    $icons[$i]['page'] = "login.php";
    $icons[$i]['img'] = displayEntry(80,"i","ui",0,5);
    $icons[$i]['txt'] = "";
    $i++;
  }
}

//output top level icons
echo "<table rules=cols frame=void width=100% cellpadding=6 border=1><tr><th valign=top align=middle width=150>";

  foreach ($icons as $value) {
    if (!strpos($value['img'], "table")){
      echo "<a href='" . $value['page'] . "'>";
      echo $value['img'];
      if ($value['txt']) echo "<br>" .$value['txt'];
      echo "</a><br><br>";
    } else { //use a form for IE
      echo '<form method="post" action="' . $value['page'] . '">';
      echo "<input type='hidden' name='ui' value='$ui'>";
      echo "<input type='hidden' name='sgn' value='$sgn'>";
      echo '<button type="submit">';
      echo $value['img'];
      echo '</button>';
      echo "</form>";
    }
  }
echo "</th><td valign=top>";
//echo '<font color="#0000cc" size="4" face="Arial, Helvetica, sans-serif"><strong>';
//echo 'Feb 7th: Server move near complete.  Testing in progress.';
//echo "</strong></font>";
echo '<table width=700 border=0>';
echo '<tr>';
echo '<td rowspan=2>';
//red umbrella link?
//links to UI link set
$sphp = getSetting(3,$ui);
if (trim($sphp)!="" && !isPP()){
  echo '<a href="' . $sphp . '">';
  
} else {
  echo '<a href="index.php?ui=';
  if ((substr($PHP_SELF,-9,9)=='index.php') and ($sgn==0)){
    echo '0';
  } else {
    echo $ui; 
  }
  echo '&sgn=0">';
}
if (isPP()){
  echo displayEntry(163,"i","ui",0,4);
} else {
  echo displayEntry(151,"i","ui",0,4);
}
?>

      </a>
    </td>
    <td align=left>
      <font size="6" face="Arial, Helvetica, sans-serif">
<?php
if (isPP()){
  echo displayEntry(170,"t","ui",0,5);
} else {
  echo displayEntry(109,"t","ui",0,5);
}
?>
      </font>
    </td>
    <td rowspan=2 align=center>
      <?php include "stamp.php";?>
    </td>
  </tr>
  <tr>
    <td align=middle valign=top>
      <font color="#CC3300" size="3" face="Arial, Helvetica, sans-serif"><strong>
      <?php if ($subHead) {echo $subHead;}?>
      </strong></font>
    </td>
  </tr>
</table>
<hr><br>
