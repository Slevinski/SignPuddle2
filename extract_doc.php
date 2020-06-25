<?php
include "global.php";
$filedata = '<?php' . "\n";
$filedata .= '/*<meta name="generator" content="SignPuddle 1.5: http://signbank.org/signpuddle" />*/' . "\n";
$filedata .= extract_list($_REQUEST['sid']);
$filedata .= "\n?>\n";

$tmp = 'data/tmp/spreader-' . time() . '.php';

$f = fopen($tmp,"w");
if ($f) { 
  fwrite($f,$filedata);
  fclose($f);
} else {
  die('could not write file');
}

//now that file exists, headers for download
  header('Content-Length: ' . filesize($tmp));
  header('Content-Type: application/force-download');
  header('Content-Disposition: attachment; filename=extract_doc.php');

//write file for download
echo $filedata;

//done// only function below
  
function extract_list($sid){
  global $sgndir;

  $pos = strrpos($sid,".");
  if ($pos>1) {
   $sid=substr( $sid, 0, $pos); 
  }
  $tSign = readSign($sid);

  $definition = $tSign["txt"];

  $output = '$text[]=<<<EOT' . "\n";
  $output .= markdown($definition);
  $output .= "\nEOT;\n";

  //now check for signtext
  $source = file_get_contents($sgndir . "/" . $sid . ".swml");
  if ($source){
    $list = "";
    $tree = GetXMLTree($source);
    $signCount=count($tree['SWML'][0]['SIGN']);
    for ($i=0;$i<$signCount;$i++){
      $signData=$tree['SWML'][0]['SIGN'][$i];
      $lane = $signData[ATTRIBUTES][LANE];
      $gloss = $signData[ATTRIBUTES][GLOSS];
      $build = "";
      foreach ($signData['SYMBOL'] as $symbols) {
        $symbol = $symbols[VALUE];
        $build .= $symbol;
        $build .= ",";
        $build .= $symbols[ATTRIBUTES][X];
        $build .= ",";
        $build .= $symbols[ATTRIBUTES][Y];
        $build .= ",";
      }
      $build .= $gloss;
      $build .= ",";
      $build .= $lane;
      $list .= $build . "\r";
    }//main for loop
  }
  $output .= '$list[]=<<<EOT' . "\n" . $list . "\nEOT;\n";
  //use next for added text
  if ($tSign["next"]){
    $output .= extract_list($tSign["next"]);
  }
  return $output;  
}
?>

