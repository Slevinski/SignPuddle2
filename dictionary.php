<?php
header("Content-type: text/plain");
header('Content-Disposition: filename=dictionary.js');
include 'styleA.php';
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

    $xml = read_spml($type,$pid);
    echo 'window.dict = "";'; 
    echo "\n";
    foreach($xml->children() as $entry) {
      $terms = array();
      $signs = array();
      $arr = $entry->attributes();
      $id = $arr['id'];
      if ($id){
        foreach($entry->children() as $item) {
          if ($item->getName() == 'term'){
            if (fswText($item)){
              $signs[] = $item;
            } else {
              $terms[] = str_replace('"',"'",$item);
            }
          }
        }
      }
      if (count($signs)){
        echo 'dict += "' . implode($signs,"\t") . "\t" . implode($terms,"\t") . '\n";' . "\n";
      }
    }
    //echo 'localStorage["dict"] = dict;';
?>

