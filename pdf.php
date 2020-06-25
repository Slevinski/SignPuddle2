<?php
/*
#@+leo-ver=4-thin
#@+node:ses.20071108113109.1:@thin W:/www/pdf.php
#@@first
#@@first
#@delims /* */ 

include('library/xml/xmlFunc.php');
include('library/sps/columnclass.php');

//get variables
$build = $_REQUEST['list'];
if (!$build) {
  $build = $_REQUEST['build'];
}
$PDF = $_REQUEST['PDF'];
$PHP_SELF = $_SERVER['PHP_SELF'];
$source = $_REQUEST['source'];
$size= $_REQUEST['size'];
$docName= $_REQUEST['docName'];
$layout= $_REQUEST['layout'];
$format = $_REQUEST['format'];
$colorize= $_REQUEST['colorize'];
$collines = $_REQUEST['collines'];
$rowlines = $_REQUEST['rowlines'];
$laneOffset = $_REQUEST['laneOffset'];
$colPad = $_REQUEST['colPad'];
$spacing = $_REQUEST['spacing'];

$vert=5;
//default values if format isn't set
if ($format==""){
  $format="A4";
  $size=1;
  $laneOffset=75;
  $colPad=45;
  $spacing=10;
  $collines=1;
  $rowlines=1;
  $docName='SignText';
}

$build=str_replace("\r","",$build);
if ($source=="" && $build==""){
  $source = implode('',file('example.swml'));
}

if ($build<>"" and $source==""){
  //build source
  $source="<?xml version=\"1.0\"?>\n";
  $source.="<!DOCTYPE swml SYSTEM \"http://www.signpuddle.com/swml/swml-s.dtd\">\n";
  $source.="  <swml dialect=\"S\" version=\"1.1\" lang=\"sgn\" glosslang=\"\">\n";
  $builds=split("\n",$build);
  for($i=0;$i<count($builds);$i++){
    $sign=split(",",$builds[$i]);
    $cnt=count($sign);
    $cnt=$cnt - ($cnt%3);

    $gloss="sign-" . $i+1;
    if ($cnt<count($sign)){ $gloss=$sign[$cnt];}
    $lane = 0;
    if (($cnt+1)<count($sign)){ $lane=$sign[$cnt+1];}

    //output sign markup
    $source.="    <sign lane=\"$lane\">\n";
    $source.="      <gloss>$gloss</gloss>\n";

    for ($j=0;$j<$cnt;$j++){
      $symbol=$sign[$j];
      $j++;
      $x=$sign[$j];
      $j++;
      $y=$sign[$j];
      $source.="      <symbol x=\"$x\" y=\"$y\">$symbol</symbol>\n";
    }

    //output sign markup
    $source.="    </sign>\n";
  }
  $source.="  </swml>";
}

$source=stripslashes($source);

//get xml info array
//error_reporting(0);
$tree = GetXMLTree($source);
$signCount=count($tree['SWML'][0]['SIGN']);

if ($PDF<>'PDF'){
  echo '<html>';
  echo '<head><title>Print to PDF</title>';
  echo '<script language="Javascript" src="SavePage.js">';
  echo '</script>';
  echo '</head>';
  echo '<body>';

  $subHeader="Print to PDF";
  //now head the standard header
  include "header.php";

  //now start the page
  $source=stripslashes($source);
  echo "<form method=post action=$PHP_SELF>";
  echo "<input type=\"hidden\" name=\"list\" value =\"$list\">";
  echo "<table cellpadding=4 border=0>";
  echo "<tr><td>Document Name</td>";
  echo '<td><INPUT size=10 NAME="docName" VALUE="'. $docName. '">.pdf</td></tr>';
  echo "<tr><td>Page format</td><td><select name='format'>";
  echo "<option value='A3' ";
  if ($format=="A3") {echo "selected";}
  echo ">A3";
  echo "<option value='A4' ";
  if ($format=="A4") {echo "selected";}
  echo ">A4";
  echo "<option value='A5' ";
  if ($format=="A5") {echo "selected";}
  echo ">A5";
  echo "<option value='Letter' ";
  if ($format=="Letter") {echo "selected";}
  echo ">US Letter";
  echo "<option value='Legal' ";
  if ($format=="Legal") {echo "selected";}
  echo ">US Legal";
  echo "</select></td></tr>";
  echo "<tr><td>Page layout</td><td><select name='layout'>";
  echo "<option value='P' ";
  if ($layout=="P") {echo "selected";}
  echo ">Portrait";
  echo "<option value='L' ";
  if ($size==1) {echo "selected";}
  echo ">Landscape";
  echo "</select></td></tr>";
  echo "<tr><td>Sign size</td><td><select name='size'>";
  echo "<option value='.5' ";
  if ($size==.5) {echo "selected";}
  echo ">Small";
  echo "<option value='1' ";
  if ($size==1) {echo "selected";}
  echo ">Normal";
  echo "</select></td></tr>";
  echo "<tr><td>Colorize</td>";
  echo '<td><INPUT TYPE=CHECKBOX NAME="colorize"></td></tr>';
  echo "<tr><td>Column lines</td>";
  echo '<td><INPUT TYPE=CHECKBOX NAME="collines" ';
  if ($collines) {echo 'CHECKED';}
  echo '></td></tr>';
  echo "<tr><td>Horizontal lines</td>";
  echo '<td><INPUT TYPE=CHECKBOX NAME="rowlines" ';
  if ($rowlines) {echo 'CHECKED';}
  echo '></td></tr>';

  echo "<tr><td>Lane Offset</td>";
  echo '<td><INPUT size=5 NAME="laneOffset" VALUE="'. $laneOffset . '"></td></tr>';
  echo "<tr><td>Column Padding</td>";
  echo '<td><INPUT size=5 NAME="colPad" VALUE="'. $colPad . '"></td></tr>';
  echo "<tr><td>Sign Spacing</td>";
  echo '<td><INPUT size=5 NAME="spacing" VALUE="'. $spacing . '"></td></tr>';
  echo "<tr>";
  echo "<td></td>";
  echo "<td><input type=submit name=PDF value=PDF></td>";
  echo "</td></tr></table>\n";
  echo "<br><hr><br>";

  $output="";

  $oddCount = $signCount % $vert;
  $colCount = (($signCount-$oddCount)/$vert)+1; 
  $output = "<table border=1 cellpadding=4><tr valign=top>\n";

  //add each sign to output
  for ($i=0;$i<$signCount;){
    $output .= "<td><table><tr>";
    $output .= "<td width=20%></td>";
    $output .= "<td width=20%></td>";
    $output .= "<td width=20%></td>";
    $output .= "<td width=20%></td>";
    $output .= "<td width=20%></td>";
    $output .= "</tr>\n";
    for ($j=0;$j<$vert;$i++,$j++){
      $output .= "<tr>";
      $sign=$tree['SWML'][0]['SIGN'][$i];
      $lane = $sign[ATTRIBUTES][LANE];

      $build = "";
      foreach ($sign['SYMBOL'] as $symbols) {
        $symbol = $symbols[VALUE];
        $build .= $symbol;
        $build .= ",";
        $build .= $symbols[ATTRIBUTES][X];
        $build .= ",";
        $build .= $symbols[ATTRIBUTES][Y];
        $build .= ",";
      }
      $imgLnk = "<img src='sign.php?build=$build&size=$size' alt='$gloss'><br><br>";
      $imgLnk .= "<img src='sign.php?size=$size' alt='spacer'><br>";
      switch ($lane){
      case -1:
        $output .= "<td align=middle colspan=3>$imgLnk</td><td colspan=2></td>";
        break;
      case 0:
        $output .= "<td></td><td align=middle colspan=3>$imgLnk</td><td></td>";
        break;
      case 1:
        $output .= "<td colspan=2></td><td align=middle colspan=3>$imgLnk</td>";

      }
      $output .= "</tr>";
    }
    $output .= "</table></td>";
  }
  $output .= "</tr></table>";
  echo "</center>";
   
  echo $output;
  echo '<br><hr><br>';
  echo "<textarea name=source cols=80 rows=16>" . $source . "</textarea><br><br>";
  echo "</form>";
}

if ($PDF=='PDF'){
  require('library/fpdf/mem_image.php');
  $pdf = new MEM_IMAGE($layout,'pt',$format);
  $pdf->SetDrawColor(50,50,50);

  $pdf->AddPage();
  $xp = $pdf->lMargin;
  $colPad = $colPad * $size;
  if ($collines) {
    $pdf->line($xp,$pdf->tMargin,$xp,$pdf->h - $pdf->tMargin);
    $xp += $colPad;
  }
  
  if ($rowlines) {
    $tMargin = $pdf->tMargin + $colPad;
    $bMargin = $pdf->bMargin;// + $colPad;
  } else {
    $tMargin = $pdf->tMargin;
    $bMargin = $pdf->bMargin;
  }
  
  
  $height = $pdf->h - $bMargin - $tMargin;
  $width = $pdf->w - $pdf->lMargin - $pdf->rMargin;
  
  $column = new COLUMN($height, $spacing * $size, $laneOffset * $size);
  if ($rowlines){
    $column->AddSpace($colPad);
  }
  //add each sign to column
  for ($i=0;$i<$signCount;$i++){
    $sign=$tree['SWML'][0]['SIGN'][$i];
    $lane = $sign[ATTRIBUTES][LANE];

    $build = "";
    foreach ($sign['SYMBOL'] as $symbols) {
      $symbol = $symbols[VALUE];
      $build .= $symbol;
      $build .= ",";
      $build .= $symbols[ATTRIBUTES][X];
      $build .= ",";
      $build .= $symbols[ATTRIBUTES][Y];
      $build .= ",";
    }
    //now I have the lane and build.
    //create sign
    $sign = new SIGN($build,$size);
    if ($colorize){
      $sign->Colorize();
    }
    $sign->Build();

    
    //ready to add to column
    if ($column->AddSign($sign,$lane)) {
      //sign added OK
    } else {
      //sign not added
      $column->SetWidth();
      
      //check if column will fit
      if ($width > ($xp + $column->width)){

        $column->AddSigns($pdf,$xp);
        $xp += $column->width + $colPad*2;
        $column->Close();
        $column = new COLUMN($height, $spacing * $size, $laneOffset * $size);

        if ($rowlines){
          $column->AddSpace($colPad);
          $pdf->line($pdf->rMargin,$pdf->tMargin,$xp-$colPad,$pdf->tMargin);
          $pdf->line($pdf->rMargin,$pdf->h - $pdf->tMargin,$xp-$colPad,$pdf->h - $pdf->tMargin);
        }
      } else {  //need new page
        $pdf->AddPage();
        $xp = $pdf->lMargin;
        if ($collines) {
          $pdf->line($xp,$pdf->tMargin,$xp,$pdf->h - $pdf->tMargin);
          $xp += $colPad;
        }

        $column->AddSigns($pdf,$xp);
        $xp += $column->width + $colPad*2;
        $column->Close();
        $column = new COLUMN($height, $spacing * $size, $laneOffset * $size);

        if ($rowlines){
          $column->AddSpace($colPad);
          $pdf->line($pdf->rMargin,$pdf->tMargin,$xp-$colPad,$pdf->tMargin);
          $pdf->line($pdf->rMargin,$pdf->h - $pdf->tMargin,$xp-$colPad,$pdf->h - $pdf->tMargin);
        }

      }
      if ($collines) {
        $pdf->line($xp-$colPad,$pdf->tMargin,$xp-$colPad,$pdf->h - $pdf->tMargin);
      }
      //now add the sign to the new column or page
      $column->AddSign($sign,$lane);
    }
  }
  
  //need to clear out the last column
      $column->SetWidth();
      
      //check if column will fit
      if ($width > ($xp + $column->width)){

        $column->AddSigns($pdf,$xp);
        $xp += $column->width + $colPad*2;
        $column->Close();
        $column = new COLUMN($height, $spacing * $size, $laneOffset * $size);

        if ($rowlines){
          $column->AddSpace($colPad);
          $pdf->line($pdf->rMargin,$pdf->tMargin,$xp-$colPad,$pdf->tMargin);
          $pdf->line($pdf->rMargin,$pdf->h - $pdf->tMargin,$xp-$colPad,$pdf->h - $pdf->tMargin);
        }
      } else {  //need new page
        $pdf->AddPage();
        $xp = $pdf->lMargin;
        if ($collines) {
          $pdf->line($xp,$pdf->tMargin,$xp,$pdf->h - $pdf->tMargin);
          $xp += $colPad;
        }

        $column->AddSigns($pdf,$xp);
        $xp += $column->width + $colPad*2;
        $column->Close();
        $column = new COLUMN($height, $spacing * $size, $laneOffset * $size);

        if ($rowlines){
          $column->AddSpace($colPad);
          $pdf->line($pdf->rMargin,$pdf->tMargin,$xp-$colPad,$pdf->tMargin);
          $pdf->line($pdf->rMargin,$pdf->h - $pdf->tMargin,$xp-$colPad,$pdf->h - $pdf->tMargin);
        }

      }
      if ($collines) {
        $pdf->line($xp-$colPad,$pdf->tMargin,$xp-$colPad,$pdf->h - $pdf->tMargin);
      }

  //finish PDF
  $pdf->Output($docName . '.pdf','I');
  $pdf->Close();
}

if ($PDF<>'PDF'){
  echo '</body>';
  echo '</html>';
}
/*@@last*/
/*@-node:ses.20071108113109.1:@thin W:/www/pdf.php*/
/*@-leo*/
?>
