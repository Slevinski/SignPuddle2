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

  $input = file_get_contents('puddle_list.txt');
  $lines = explode("\n",$input);
  $pudlist = array();
  foreach ($lines as $line){
    $parts = explode("\t",$line);
    if (count($parts) >=3){
      $collection = $parts[0];
      $pudl = $parts[1];
      $pudlist[$pudl]=$collection;
    }
  }
  $outdir = "img/" . $pudlist[$type . $pid];
  $newdir = $pudlist[$type . $pid] ."/";;
  @mkdir($outdir, 0777, true);

    $imgExt = array('png'=>'2','jpg'=>'1','jpeg'=>'3','gif'=>'4');
    $cnt = 0;
    $dir = 'data/' . $type . '/' . $pid;
    $files = 0;
    $xml = read_spml($type,$pid);
    foreach($xml->children() as $entry) {
      $output = array();
      $name = $entry->getName();
      $terms = array();
      $images = array();
      switch($name){
        case "entry":
          foreach($entry as $item) {
            $value = trim('' . $item);
            $value = str_replace("\t",' ', $value);
            $value = str_replace("\n",'\n', $value);
            $item_name = $item->getName();
            switch($item_name){
              case "term":
                if (!fswText($value)){
                  $terms[] = $value;
                }
                break;
              case "png":
              case "gif":
              case "jpg":
              case "jpeg":
                $images[] = $item_name;
                break;
              default:
                // nothing
            }
          }
          $cnt++;
          $arr = $entry->attributes();
          $id = $arr['id'];
          $name = fixname($terms[0],$arr['id']);
    
          foreach($imgExt as $img=>$i){
            if (file_exists($dir . '/' . $id . '.' . $img)){
              $images[] = $img;
            }
          }
          foreach ($images as $img){
            $infile = "data/" . $type . "/" . $pid . "/" . $id . "." . $img;
            $outfile = $outdir . "/" . $name . "." . $img;
            copy($infile,$outfile);
            $files++;
          } 

          break;
        }
      }

      $zip = new ZipArchive();
      $filename = $outdir . ".zip";
      $dir = $outdir . "/";
      if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
        exit("cannot open <$filename>\n");
      }
      if ($dh = opendir($dir)){
        while (($file = readdir($dh)) !== false){
          if (is_file($dir.$file)) {
             if ($file != '' && $file != '.' && $file != '..'){
               $zip->addFile($dir.$file,$newdir.$file);
             }
          }
        }
        closedir($dh);
      }
      $zip->close();
      if (file_exists($filename)) {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        header('Content-Length: ' . filesize($filename));
        flush();
        readfile($filename);
      } else {
        header("HTTP/1.0 404 Not Found");
      }
?>
