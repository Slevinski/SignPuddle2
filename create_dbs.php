<?php
  $list = implode(',',glob('data/spml/*.spml'));
  $list = str_replace("data/spml/",'',$list);
  $list = str_replace(".spml",'',$list);
  $list = str_replace("ui,",'',$list);
  $list = str_replace("sgn,",'',$list);
  $list = explode(',',$list);
foreach ($list as $item){
  echo 'rm puddle/' . $item . '.db' . "\n";
  echo 'sqlite3 puddle/' . $item . '.db < source/' . $item . '.sql' . "\n"; 
  echo 'sqlite3 puddle/' . $item . '.db < source/' . $item . '.head.sql' . "\n"; 
  echo 'sqlite3 swserver.db < source/' . $item . '.head.sql' . "\n"; 
}

