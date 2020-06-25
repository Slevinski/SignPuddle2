<?php
header("Content-type: text/plain; charset=utf-8");
header('Content-Disposition: filename=dictionary.txt');
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
    $lines = array();
    foreach($xml->children() as $entry) {
      $terms = array();
      $signs = array();
      $arr = $entry->attributes();
      $id = $arr['id'];
      if ($id){
        foreach($entry->children() as $item) {
          if ($item->getName() == 'term'){
            if (fswText($item)){
              $signs[] = fsw2swu($item);
            } else {
              $terms[] = str_replace('"',"'",$item);
            }
          }
        }
      }
      if (count($signs)){
        $lines[] = implode($signs,"\t") . "\t" . implode($terms,"\t") . "\n";
      }
    }
    sort($lines);
    foreach ($lines as $line){
      echo $line;
    }
    //echo 'localStorage["dict"] = dict;';
?>

