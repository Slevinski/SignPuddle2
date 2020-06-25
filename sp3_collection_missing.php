<?php
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

  $list = implode(',',glob('data/spml/*.spml'));
  $list = str_replace("data/spml/",'',$list);
  $list = str_replace(".spml",'',$list);
  $list = str_replace("ui,",'',$list);
  $list = str_replace("sgn,",'',$list);
  $list = explode(',',$list);
foreach ($list as $item){
  $name = $pudlist[$item];
  if (!$name){
    echo $item . "\n";
  }
}

