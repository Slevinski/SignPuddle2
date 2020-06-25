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
      $output = array();
      $sign = '';
      $signtext = '';
      $text = '';
      $source = '';
      $arr = $entry->attributes();
      $name = $entry->getName();
      switch($name){
        case "entry":
          foreach($entry as $item) {
            $value = trim('' . $item);
            $value = str_replace("\t",' ', $value);
            $value = str_replace("\n",'\n', $value);
            $item_name = $item->getName();
            switch($item_name){
              case "term":
                if (fswText($value)){
                  $sign = $value;
                //} else {
                //  $terms[] = $value;
                //  $lower[] = mb_strtolower($value,'UTF-8');
                }
                break;
              case "text":
                if (fswText($value)){
                  $signtext = $value;
                } else {
                  $text = $value;
                }
                break;
              case "src":
                $source = $value;
                break;
              default:
                $output[$item_name] = $value;
            }
//            if($item_name == 'png' || $item_name=='svg') {
//            }
          }
          if ($cnt==0){
            header("Content-Type: text/plain; charset=utf-8");
            header('Content-Disposition: filename=' . $type . $pid . '.txt');
          } else {
            echo "\n";
          }
          $cnt++;
          if ($arr['top']) $output['top'] = intval($arr['top']);
          if ($arr['next']) $output['next'] = intval($arr['next']);
          if ($arr['prev']) $output['prev'] = intval($arr['prev']);
          echo $arr['id'] . "\t" . $arr['usr'] . "\t" . date('Y/m/d H:i:s', ''.$arr['cdt']) . "\t" . date('Y/m/d H:i:s', ''.$arr['mdt']) . "\t" . $sign . "\t" . $signtext . "\t" . $text . "\t" . $source . "\t" . json_encode($output);

          break;
        }
      }
      if ($cnt==0) {
        header('HTTP/1.0 404 Not Found', true, 404);
        header("Location: /not/found.php");
        die();
      }
?>
