<?php
include 'msw.php';

$type = $_REQUEST['type'];
$pid = $_REQUEST['pid'];

$spml = read_spml($type,$pid);

  $xml = simplexml_load_string($spml);

  //get items...
  $items = $xml->children();
  $cnt=0;
  foreach ($items as $item) {
    $name = $item->getName();
    $itemXML = $item->asXml();
    switch($name){
      case "entry":
        $entry = $item;
        $e_id = $entry['id'];
        foreach($entry as $e_item) {
          $ei_name = $e_item->getName();
          if($ei_name == 'png' || $ei_name=='svg') {
            $outfile =  $data . '/' . $type . '/' . $id . '/' . $e_id . '.' . $ei_name;
            file_put_contents($outfile,base64_decode($e_item));
            $dom=dom_import_simplexml($e_item);
            $dom->parentNode->removeChild($dom);
          }
        }
        $outfile =  $data . '/' . $type . '/' . $id . '/' . $e_id . '.xml';
        $outxml = $entry->asXml();
        $outxml = str_replace("\n  \n","",$outxml);
//        $outxml = str_replace("\n\n","",$outxml);
        file_put_contents($outfile,$outxml."\n");
        break;
    }
  }
?>
