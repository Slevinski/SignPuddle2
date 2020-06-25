<?php
$rSL = 1;
include 'styleA.php';
$export_list_override = $_REQUEST['export_list_override'];
if ($export_list_override){
  $_SESSION['export_list'] = explode(',',$export_list_override);
}

$action = $_REQUEST['action'];
switch ($action){
case "Start":
  $_SESSION['export_list'] = array();
  break;
case "Cancel":
  unset($_SESSION['export_list']);
  break;
case "Download";
case "View";
  $ex_source = $_REQUEST['ex_source'];
  
  //here we go!
  switch($ex_source){
    case "All";
      if ($ui and $sgn){
        $type='sgn';
        $pid = $sgn;
      } else {
        $type='ui';
        if ($ui) {
          $pid=$ui;
        } else if ($sgn) {
          $type='ui';
          $pid=$sgn;
        }
      }
      $spml_file = 'data/spml/' . $type . $pid . '.spml';
      break;
    case "SignTexts";
      if ($ui and $sgn){
        $type='sgn';
        $id = $sgn;
      } else {
        $type='ui';
        if ($ui) {
          $id=$ui;
        } else if ($sgn) {
          $type='ui';
          $id=$sgn;
        } else {
          return;
        }
      }
      $export=array();
      $xml = read_spml($type,$id);
      foreach($xml->children() as $entry) {
        $arr = $entry->attributes();
        $id = $arr['id'];
        foreach($entry->children() as $item) {
          if ($item->getName() == "text"){
            if (fswText($item)){
              $export[]=$id;
            }
          }
        }
      }
      break;
    case "Terms";
      if ($ui and $sgn){
        $type='sgn';
        $id = $sgn;
      } else {
        $type='ui';
        if ($ui) {
          $id=$ui;
        } else if ($sgn) {
          $type='ui';
          $id=$sgn;
        }
      }
      $export=array();
      $xml = read_spml($type,$id);
      foreach($xml->children() as $entry) {
        $arr = $entry->attributes();
        $id = $arr['id'];
        foreach($entry->children() as $item) {
          if ($item->getName() == "term"){
            if (fswText($item)){
              $seq = ksw2seq(csw2ksw($item));
              if ($seq!=''){
                $export[]=$id;
              }
            }
          }
        }
      }
      break;
    case "Selected";
      $export = $_SESSION['export_list'];
      break;
    case "UI";
      $type='ui';
      if ($ui){
        $pid = $ui;
      } else if ($sgn){
        $pid=$sgn;
      }
      $spml_file = 'data/spml/' . $type . $pid . '.spml';
      break;
  }
}

if (count($export)>0){

  if ($ui and $sgn){
    $type='sgn';
    $pid = $sgn;
  } else {
    $type='ui';
    if ($ui) {
      $pid=$ui;
    } else if ($sgn) {
      $type='ui';
      $pid=$sgn;
    }
  }

  $dir = 'data/' . $type . '/' . $pid;
  $time = time();
  $offset = $time - 1301508584;
  $tmpfile = $data . '/tmp/ex_' . $offset . '.spml';
  $handle = fopen($tmpfile, "w");
  //prefix spml
  $spml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
  $spml .= '<!DOCTYPE spml SYSTEM "http://www.signpuddle.net/spml_1.6.dtd">' . "\n";
  $xroot = $xarr['root'];

  $spml .= '<spml';
  if ($xroot) {
    $spml .= ' root="' . $xroot . '"';
  }
  $spml .= ' type="ext" puddle="' . $offset . '" cdt="';
  $spml .= $time . '" mdt="' . $time . '"';

  //Determin cdt and mdt values
//  $nextid = $xarr['nextid'];
//  if ($nextid) $spml .= ' nextid="' . $nextid . '"';
  $spml .= '>' . "\n";

  //now add items...
  $filexml = $dir . '.xml';
  $xml = simplexml_load_file($filexml);
  $items = $xml->children();
  foreach ($items as $item) $spml .= '  ' . $item->asXml() . "\n";
  $png = base64_encode(@file_get_contents($dir .  '.png'));
  if ($png) $spml .=  '  <png>' . $png . '</png>' . "\n";

  fwrite($handle, $spml . "\n");

  //now cycle through entires, then documents
  foreach ($export as $eid){
    $entry = file_get_contents($dir . '/' . $eid . '.xml');
    if (in_array($eid,$pngs)) { 
      $png = base64_encode(file_get_contents($dir . '/' . $eid . '.png'));
      $entry = str_replace('</entry>','  <png>' . $png . '</png>' . "\n" . '</entry>',$entry);
    }
    fwrite($handle, $entry . "\n");
  }

  $spml = '</spml>' . "\n";
  fwrite($handle, $spml);
  fclose($handle);
  $filename = substr($tmpfile, strrpos($tmpfile, '/') + 1);
  if ($action=="View"){
    header('Content-Type: application/xml');
    readfile($tmpfile);
    unlink($tmpfile);
    die();
  } else if ($action=="Download"){
    header("Content-Length: " . filesize($tmpfile));
    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename=' . $filename);
    readfile($tmpfile);
    unlink($tmpfile);
    die();
  }
  
}

if ($spml_file && file_exists($spml_file)){ 
  if ($action=="View"){
    echo '<html><head>';
    echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=' . $spml_file . '">' . "\n";
    echo '</head><body>';
    echo '</body></html>';
    die();
  } else if ($action=="Download"){
    $filename = substr($spml_file, strrpos($spml_file, '/') + 1);
    header("Content-Length: " . filesize($spml_file));
    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename=' . $filename);
    readfile($spml_file);
    die();
  }
}

include 'styleB.php';
//$subHead="Export";
$subHead=displayEntry(11,'t',"ui");

include 'header.php';

//Selection display
echo '<h2>' . getSignTitle(146,"ui") . '</h2>';
echo '<form method="POST" action="' . $PHP_SELF . '">';

echo "<table cellpadding=4 border=0>";

  echo "<tr><td>" . getSignTitle(147,"ui") . "</td><td><select name='ex_source'>";
  $opts = array();
  $opts['All'] = 'Entire Puddle';
//  $opts['SignTexts'] = 'SignTexts';
//  $opts['Terms'] = 'Sorted Terms';
  if (is_array($_SESSION['export_list'])) {
    $opts['Selected'] = 'Selected';
    if ($ex_source=='') $ex_source='Selected';
  }
  $opts['UI'] = 'User Interface';
  foreach($opts as $j=>$opt){
    echo "<option value='" . $j . "' ";
    if ($ex_source==$j) {echo "selected";}
    echo ">" . $opt;
  }
  echo "</select></td></tr>";

  echo '<tr><td></td><td>';
  echo '<button type="submit" name="action" value="Download">';
  echo 'Download';
  echo '</button>';
  echo ' ';
  echo '<button type="submit" name="action" value="View">';
  echo 'View';
  echo '</button>';
  echo '</td></tr>';

echo "</table>";
echo '</form>';

//if (1==0){
echo '<hr>';
echo '<h2>' . getSignTitle(148,"ui") . '</h2>';
if (!is_array($_SESSION['export_list'])){
  echo '<form method="POST" action="' . $PHP_SELF . '">';
  echo '<input type="hidden" name="action" value="Start">';
  echo '<button type="submit">';
  echo displayEntry(107,"i","ui");
  echo '</button>';
  echo "</form>";
} else {
  echo '<form method="POST" action="' . $PHP_SELF . '">';
  echo 'Export list <input name="export_list_override" value="' . implode(',',$_SESSION['export_list']) . '">';
  echo ' <button type="submit">';
  echo 'Update';
  echo '</button>';
  echo "</form>";
  echo "<br>";
  echo '<form method="POST" action="' . $PHP_SELF . '">';
  echo '<input type="hidden" name="action" value="Cancel">';
  echo '<button type="submit">';
  echo "Cancel";
  echo '</button>';
  echo "</form>";
}
//}












if (1==0){
echo '<hr>';
echo '<h2>' . getSignTitle(148,"ui") . '</h2>';
if (!is_array($_SESSION['export_list'])){
  echo '<form method="POST" action="' . $PHP_SELF . '">';
  echo '<input type="hidden" name="action" value="Start">';
  echo '<button type="submit">';
  echo displayEntry(107,"i","ui");
  echo '</button>';
  echo "</form>";
} else {
  echo '<form method="POST" action="' . $PHP_SELF . '">';
  echo 'Export list <input name="export_list_override" value="' . implode(',',$_SESSION['export_list']) . '">';
  echo ' <button type="submit">';
  echo 'Update';
  echo '</button>';
  echo "</form>";
  echo "<br>";
  echo '<form method="POST" action="' . $PHP_SELF . '">';
  echo '<input type="hidden" name="action" value="Cancel">';
  echo '<button type="submit">';
  echo "Cancel";
  echo '</button>';
  echo "</form>";
}
}

/*
if (count($_SESSION['export_list'])>0){
  echo '<br><h3>SignPuddle Reader</h3>';
  echo '<form method="POST" action="' . $PHP_SELF . '">';
  echo '<input type="hidden" name="action" value="Export">';

  echo "<table cellpadding=4 border=0>";

  echo "<tr><td>Export Level</td><td><select name='ex_level'>";
  $opts = array();
  $opts['Sign'] = 'Signs';
  $opts['SignText'] = 'SignTexts';
  foreach($opts as $j=>$opt){
    echo "<option value='" . $j . "' ";
    if ($ex_level==$j) {echo "selected";}
    echo ">" . $opt;
  }
  echo "</select></td></tr>";

  echo "<tr><td>Image Format</td><td><select name='font'>";
  $opts = array();
  $opts['png1'] = 'PNG Standard';
  $opts['png2'] = 'PNG Inverse';
  $opts['png3'] = 'PNG Shadow';
  $opts['png4'] = 'PNG Colorize';
  $opts['svg1'] = 'SVG Line Trace';
  $opts['svg2'] = 'SVG Shadow Trace';
  $opts['svg3'] = 'SVG Smooth';
  $opts['svg4'] = 'SVG Angular';
  foreach($opts as $j=>$opt){
    echo "<option value='" . $j . "' ";
    if ($font==$j) {echo "selected";}
    echo ">" . $opt;
  }
  echo "</select></td></tr>";

  echo '<tr><td></td><td>';
  echo '<button type="submit" name="action" value="Download">';
  echo 'Download';
  echo '</button>';
  echo ' ';
  echo '<button type="submit" name="action" value="View">';
  echo 'View';
  echo '</button>';
  echo '</td></tr>';

  echo "</table>";
  echo '</form>';
}
*/

echo "<hr>";
echo "<h2>SignMaker 2015</h2>";
echo '<p><a href="dictionary.php?ui=' . $ui . '&sgn=' . $sgn .'">dictionary.js</a>';
echo '<p><a href="alphabet.php?ui=' . $ui . '&sgn=' . $sgn .'">alphabet.js</a>';

echo "<hr>";
echo "<h2>SignMaker 2017</h2>";
echo '<p><a href="plaintext.php?ui=' . $ui . '&sgn=' . $sgn .'">Plain text dictionary</a>';

include 'footer.php';
?>
