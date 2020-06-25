<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  include 'styleA.php';
  include 'sp3_common.php';
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
  //png used for gesture illustration
  //jpg used for picture
  $imgExt = array('png'=>'2','jpg'=>'1','jpeg'=>'3','gif'=>'4');

    $cnt = 0;
    $dir = 'data/' . $type . '/' . $pid;
    $xml = read_spml($type,$pid);
    foreach($xml->children() as $entry) {
      $output = array();
      $sign = '';
      $signtext = '';
      $text = '';
      $source = '';
      $arr = $entry->attributes();
      $usr = $arr['usr']!=''?$arr['usr']:'admin';
      $name = $entry->getName();
      $terms = array();
      $lower = array();
      $images = array();
      switch($name){
        case "entry":
          $id = $arr['id'];
          if (!preg_match('/^\d+$/', $id)) {
            break;
          }
          foreach($entry as $item) {
            $value = trim('' . $item);
            $value = str_replace("\t",' ', $value);
            $value = str_replace("\n",'\n', $value);
            $item_name = $item->getName();
            switch($item_name){
              case "term":
                if (fswText($value)){
                  $sign = fsw2swu($value);
                } else {
                  $value = str_replace("|","",$value);
                  $value = trim($value);
                  if ($value){
                    $terms[] = $value;
                    $lower[] = mb_strtolower($value,'UTF-8');
                  }
                }
                break;
              case "text":
                if (fswText($value)){
                  $signtext = fsw2swu($value);
                } else {
                  $text = wrapit($value);
                }
                break;
              case "src":
                $source = wrapit($value);
                break;
              case "png":
              case "gif":
              case "jpg":
              case "jpeg":
                $images[] = $item_name;
                //here is a file...
                break;
              default:
                $output[$item_name] = $value;
            }
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
         
          $iname = fixname($terms[0],$arr['id']);
          foreach($imgExt as $img=>$i){
            if (file_exists($dir . '/' . $id . '.' . $img)){
              $images[] = $img;
            }
          }

          if (count($images)){
            $output['images'] = array();
            foreach ($images as $img){
              if ($pid==49){
                $output['images'][$imgExt[$img]] = $iname . "." . $img;
              } else {
                if (!array_key_exists("picture",$output['images'])){
                  $output['images']['1'] = $iname . "." . $img;
                } elseif (!array_key_exists("2",$output['images'])){
                  $output['images']['2'] = $iname . "." . $img;
                } elseif (!array_key_exists("3",$output['images'])){
                  $output['images']['3'] = $iname . "." . $img;
                } 
              }
            }
          }
          echo $arr['id'] . "\t" . $sign . "\t" . wrapit(implode("|",$terms)) . "\t" . wrapit(implode("|",$lower)) . "\t" . $signtext . "\t" . $text . "\t" . $source . "\t" . toJSON($output) . "\t" . $usr . "\t" . date('Y-m-d\TH:i:s\Z', ''.$arr['cdt']) . "\t" . date('Y-m-d\TH:i:s\Z', ''.$arr['mdt']);
          break;
        }
      }
      if ($cnt==0) {
        header('HTTP/1.0 404 Not Found', true, 404);
        die();
      }
?>
