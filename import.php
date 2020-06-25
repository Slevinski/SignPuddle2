<?php
$rSL = 1;
set_time_limit(60);
include 'styleA.php';


if($security>4 || isPP()){
// forced rename of xml to spml
  foreach (glob('import/*.xml') as $file){
    $ofile = str_replace(".xml",'.spml',$file);
    rename ($file,$ofile);
  }

  //replace
  $replaceUI = $_REQUEST['replaceUI'];
  if ($replaceUI){
    foreach (glob($uidir . '.*') as $filename){
      unlink ($filename);
    }
   //remove data files
    foreach (glob($uidir . '/*') as $filename){
      unlink ($filename);
    }
   //remove data files
    foreach (glob($data . '/spml/ui*.spml') as $filename){
      unlink ($filename);
    }
    //remove directory
    rmdir($uidir);
  }

  //replace
  $replace = $_REQUEST['replace'];
  if ($replace) {
    $parts = explode('.',$replace);
    $type = $parts[0];
    $id = $parts[1];
    if (file_exists('import/' . $type . $id . '.spml')){
      $dir = 'data/' . $type . '/' . $id;
      foreach (glob($dir . '.*') as $filename){
  //      echo "<p>1 Unlink $filename";
        unlink ($filename);
      }
     //remove data files
      foreach (glob($dir . '/*') as $filename){
  //      echo "<p>2 Unlink $filename";
        unlink ($filename);
      }
      //remove directory
  //    echo "<p>rm Dir $dir";
      rmdir($dir);

      //remove main spml
  //    echo "<p>rm Dir $dir";
      unlink ('data/spml/' . $type . $id . '.spml');
      $_REQUEST['import'] = $replace;
    }
  }
  //move
  //move does not work yet
  $move = $_REQUEST['move'];
  if ($move) {
    $parts = explode('.',$move);
    $type = $parts[0];
    $id = $parts[1];
    if (file_exists('import/' . $type . $id . '.spml')){
      //get new ID
      $inext = $id;
      while (file_exists('data/' . $type . '/' . $inext)) $inext +=1000;

      $odir = 'data/' . $type . '/' . $id;
      $ndir = 'data/' . $type . '/' . $inext;
      foreach (glob($odir . '.*') as $ofile){
        $nfile = str_replace($odir,$ndir,$ofile);
        rename ($ofile,$nfile);
      }
      //rename directory
      rename ($odir,$ndir);

      //setup import
      $_REQUEST['import'] = $move;

      //now page forward for rename
  //    echo '<html>';
  //    echo '<head>';
  //    echo '<META ';
  //    echo '  HTTP-EQUIV="Refresh"';
  //    echo 'CONTENT="0; URL=canvas.php?';
  //    if ($type == 'ui'){
  //      echo 'ui=0&sgn=0&sid=' . $inext;
  //    } else {
  //      echo 'ui=' . $ui . '&sgn=0&sid=' . $inext;
  //    }
  //    echo '">';
  //    echo '</head>';
  //    echo '<body></body>';
  //    echo '</html>';
  //    die();
    }
  }

  //import
  $import = $_REQUEST['import'];
  $success=0;
  if ($import) {
    $parts = explode('.',$import);
    $type = $parts[0];
    $id = $parts[1];

    $file = 'import/' . $type . $id. '.spml';
    if (file_exists($file)){
      $datafile = 'data/spml/' . $type . $id . '.spml';
      if (file_exists($datafile)){
        unlink($datafile);
      }

      //remove archive files for sgn and ui
      @unlink ('data/spml/' . $type . '.spml');
   
      //copy file
      copy($file,$datafile);
      @unlink ($file);
      unpack_spml($type,$id);
    
      //this will overwrite home link in ui
      if ($type=='ui'){
        $sgn=$id;
        $ui=0;
        setUISGN();
        $tsgn = readSign(3);
        $tsgn['trm'][1]='';
        writeSign(3,$tsgn);
        $ui=$sgn;
        $sgn=0;
        setUISGN();
        $_SESSION['UI'] = $sgn;
        $_SESSION['SGN'] = 0;
      }
      $success=1;
    }
  }
}

$subHead=displayEntry(76,'t',"ui");
include 'library/zip/pclzip.lib.php';
  //main page
include 'styleB.php';
$subHead=displayEntry(76,'t',"ui");
include 'header.php'; 
$build = $_REQUEST['build'];
if (get_magic_quotes_gpc()){
  $build = stripslashes($build);
}

echo '<form action="' . $SELF_PHP . '" method="post">';
echo '<h3>';
echo getSignTitle(76,"ui");
echo '</h3>';
echo '<table border><tr><td> ';
echo '<TEXTAREA NAME="build" COLS=40 ROWS=6>' . $build . '</TEXTAREA>';
echo '</td><td>';
echo '<input type="hidden" name="action" value="translate">';
echo '<button type="submit">';
echo getSignTitle(76,"ui");//translate
echo '</button>';

echo '</td></tr>';
echo '</table>';
echo '</form>';

if ($build){
  //KSW Display
  if (cswText($build)){
    $sgntxt = fsw2ksw(bsw2fsw(csw2bsw($build)));
  } else if (bswText($build)){
    $sgntxt = fsw2ksw(bsw2fsw($build));
  } else if (fswText($build)){
    $sgntxt = fsw2ksw($build);
  } else if (kswLayout($build)){
    $sgntxt = $build;
  } else {
    $xml = simplexml_load_string($build);
    if ($xml) print_r($xml);
  }
}
if (!$sgntxt) $sgntxt =fsw2ksw(fswString($build));
if ($sgntxt){
  stDisplay($sgntxt);
  stOptions($sgntxt);
}


if($security>4 || isPP()){
  echo "<br><br><hr><br><br>";

  if ($id and $inext){
    $msg .= '<p><table cellpadding=20 border=1><tr>';

    $msg .= '<td><h3>Old ' . $type . '.' . $id . ' now ' . $type . '.' . $inext . '</h3>';
    $msg .= '<form method="POST" action="canvas.php">';
    $msg .= '<input type="hidden" name="ui" value="';
    if ($type=='ui') {
      $msg .= '0';
    } else {
      $msg .= $ui;
    }
    $msg .= '">';
    $msg .= '<input type="hidden" name="sgn" value="0">';
    $msg .= '<input type="hidden" name="sid" value="' . $inext . '">';
    $msg .= '<button type="submit">';
    $msg .= '<table cellpadding=5><tr><td align=middle><font size=-1>Edit Puddle Details</font></td></tr></table>'; 
    $msg .= '</button>';
    $msg .= "</form>";
    $msg .= '</td>';

    $msg .= '<td><h3>New ' . $type . '.' . $id . '</h3>';
    $msg .= '<form method="POST" action="canvas.php">';
    $msg .= '<input type="hidden" name="ui" value="';
    if ($type=='ui') {
      $msg .= '0';
    } else {
      $msg .= $ui;
    }
    $msg .= '">';
    $msg .= '<input type="hidden" name="sgn" value="0">';
    $msg .= '<input type="hidden" name="sid" value="' . $id . '">';
    $msg .= '<button type="submit">';
    $msg .= '<table cellpadding=5><tr><td align=middle><font size=-1>Edit Puddle Details</font></td></tr></table>'; 
    $msg .= '</button>';
    $msg .= "</form>";
    $msg .= '</td></tr></table>';

  }

  $files=array();
  foreach (glob('import/*.spml') as $file){
    $file = str_replace("import/",'',$file);
    $files[] = $file;
  }

  if ($success){
    echo "<h2>Import Successful</h2>";
    if ($type!='ui'){
      echo '<a href="index.php?ui=' . $ui . '&' . $type . '=' . $id .'">';
      $img = 'data/' . $type . '/' . $id .'.png';
      if (file_exists($img)){
        echo '<img src="' . $img . '">';
      } else {
      echo $type . '.' . $id;
      }
    }
    echo "</a><hr>";
  } else {
    if (count($files)==0){
      if ($msg){
        echo $msg;
      } else {
        echo '<h2>Nothing to import</h2>';
        echo 'To import, copy SPML files to<br>' . getcwd() . '/import';
      }
    }
  }


  $ifiles = array(); // import files
  $cfiles = array(); // conflicting files
  foreach ($files as $file){

    $import=substr($file,0,strpos($file,'.'));
    $pattern = '/[0123456789]+/';
    preg_match($pattern, $import, $matches);
    $id = $matches[0];
    $len = strlen($import) - strlen($id);
    $type = substr($import,0,$len);

    $dir = 'data/' . $type . '/' . $id;

    //remove directory if it exists
    if (is_dir($dir)){
      $cfiles[] = $type . '.' . $id;
    } else {
      $ifiles[] = $type . '.' . $id;
    }
  }

  if (count($ifiles)>0){
    echo '<h2>Import</h2>';
    echo '<table cellpadding=5>';
    foreach ($ifiles as $file){
      $import=substr($file,0,strpos($file,'.'));
      $pattern = '/[0123456789]+/';
      preg_match($pattern, $import, $matches);
      $id = $matches[0];
      $len = strlen($import) - strlen($id);
      $type = substr($import,0,$len);
      echo '<tr>';
      echo '<td>' . $file . '</td><td>';
      //import button
      echo '<form method="POST" action="' . $PHP_SELF . '">';
      echo '<input type="hidden" name="import" value="' . $file . '">';
      echo '<button type="submit">';
      echo '<table cellpadding=5><tr><td align=middle><font size=-1>Import</font></td></tr></table>'; 
      echo '</button>';
      echo "</form>";
      echo '</td><td>';
      //replace uibutton
      if ($type=='ui'){
        echo '<form method="POST" action="' . $PHP_SELF . '">';
        echo '<input type="hidden" name="replaceUI" value="1">';
        echo '<input type="hidden" name="import" value="' . $file . '">';
        echo '<button type="submit">';
        echo '<table cellpadding=5><tr><td align=middle><font size=-1>Replace UI</font></td></tr></table>'; 
        echo '</button>';
        echo "</form>";
      }

      echo '</td></tr>';
    }
    echo '</table>';
  }

  if (count($cfiles)>0){
    echo '<h2>Already have...</h2>';
    echo '<table cellpadding=5>';
    foreach ($cfiles as $file){
      echo '<tr>';
      echo '<td>' . $file . '</td><td>';
      //replace button
      echo '<form method="POST" action="' . $PHP_SELF . '">';
      echo '<input type="hidden" name="replace" value="' . $file . '">';
      echo '<button type="submit">';
      echo '<table cellpadding=5><tr><td align=middle><font size=-1>Replace</font></td></tr></table>'; 
      echo '</button>';
      echo "</form>";
  //    echo '</td><td>';
      //Move button
  //    echo '<form method="POST" action="' . $PHP_SELF . '">';
  //    echo '<input type="hidden" name="move" value="' . $file . '">';
  //    echo '<button type="submit">';
  //    echo '<table cellpadding=5><tr><td align=middle><font size=-1>Move</font></td></tr></table>'; 
  //    echo '</button>';
  //    echo "</form>";
      echo '</td></tr>';
    }
      echo '</table>';
  }
}

include 'footer.php';

?>
