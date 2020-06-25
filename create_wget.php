<?php
  $list = implode(',',glob('data/spml/*.spml'));
  $list = str_replace("data/spml/",'',$list);
  $list = str_replace(".spml",'',$list);
  $list = str_replace("ui,",'',$list);
  $list = str_replace("sgn,",'',$list);
  $list = explode(',',$list);
foreach ($list as $item){
  $type = substr($item,0,2);
  if ($type == 'ui'){
    $id = str_replace('ui','',$item);
    echo 'wget -O /home/sworg3x/public_html/signbank/swserver_data/source/' . $item . '.txt "http://signbank.org/signpuddle2.0/puddle_detail.php?ui=0&sgn=' . $id . '"' .  "\n";
    echo 'wget -O /home/sworg3x/public_html/signbank/swserver_data/source/' . $item . '.term.txt "http://signbank.org/signpuddle2.0/puddle_term.php?ui=0&sgn=' . $id . '"' .  "\n";
    echo 'wget -O /home/sworg3x/public_html/signbank/swserver_data/source/' . $item . '.head.txt "http://signbank.org/signpuddle2.0/puddle_head.php?ui=0&sgn=' . $id . '"' .  "\n";
  } else {
    $id = str_replace('sgn','',$item);
    if ($id>0){
      echo 'wget -O /home/sworg3x/public_html/signbank/swserver_data/source/' . $item . '.txt "http://signbank.org/signpuddle2.0/puddle_detail.php?ui=1&sgn=' . $id . '"' . "\n";
      echo 'wget -O /home/sworg3x/public_html/signbank/swserver_data/source/' . $item . '.term.txt "http://signbank.org/signpuddle2.0/puddle_term.php?ui=1&sgn=' . $id . '"' . "\n";
      echo 'wget -O /home/sworg3x/public_html/signbank/swserver_data/source/' . $item . '.head.txt "http://signbank.org/signpuddle2.0/puddle_head.php?ui=1&sgn=' . $id . '"' . "\n";
    }  
  }  
  if ($id>0){
    echo 'wget -O /home/sworg3x/public_html/signbank/swserver_data/source/' . $item . '.sql "http://signbank.org/signpuddle2.0/create_sql.php?puddle=' . $item . '"' . "\n";
    echo 'wget -O /home/sworg3x/public_html/signbank/swserver_data/source/' . $item . '.head.sql "http://signbank.org/signpuddle2.0/create_sql_head.php?puddle=' . $item . '"' . "\n";
  }
}
echo 'cd /home/sworg3x/public_html/signbank/swserver_data' . "\n";
echo './create_db.sh' . "\n";
echo './create_dbs.sh' . "\n";
