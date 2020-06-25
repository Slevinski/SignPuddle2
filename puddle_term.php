<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
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

    $cnt = 0;
    $xml = read_spml($type,$pid);
    foreach($xml->children() as $entry) {
      $subcnt = 0;
      $sub = array();
      $terms = array();
      $lower = array();
      $arr = $entry->attributes();
      $name = $entry->getName();
      switch($name){
        case "entry":
          foreach($entry as $item) {
            $value = trim(str_replace("\t",' ','' . $item));
            $item_name = $item->getName();
            switch($item_name){
              case "term":
                if (!fswText($value)){
                  $sub[] = $subcnt;
                  $terms[] = $value;
                  $lower[] = mb_strtolower($value,'UTF-8');
                  $subcnt++;
                }
                break;
              case "text":
                break;
              default:
            }
          }
          if ($cnt==0){
            header("Content-Type: text/plain; charset=utf-8");
            header('Content-Disposition: filename=' . $type . $pid . '.term.txt');
          }
          foreach ($terms as $i=>$term){
            if ($cnt>0){
              echo "\n";
            }
            if ($sub[$i]==0){
              $prime = 1;
            } else {
              $prime = 0;
            }
            $cnt++;
            echo $arr['id'] . "\t" . $prime . "\t" . $term . "\t" . $lower[$i];
          }
          break;
        }
      }
      if ($cnt==0) {
        header('HTTP/1.0 404 Not Found', true, 404);
        header("Location: /not/found.php");
        die();
      }
?>
