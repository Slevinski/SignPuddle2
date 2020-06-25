<?php
header("Content-type: text/plain");
header('Content-Disposition: filename=alphabet.js');
include 'styleA.php';
$xml = get_spml('sgn',$sgn);
echo 'window.alphabet = {' . "\n"; 
     
foreach ($sg_list as $i=>$first){
  if ($i == 29) {
    $last = '38b';
  } else {
    $last = dechex(hexdec($sg_list[$i+1])-1);
  }
  $total= 0;
  $keys = Array(); 
  for ($i = hexdec($first);$i<=hexdec($last);$i++){
    $pattern = '/S' . dechex($i) . '[0-5][0-9a-f]/';
    preg_match($pattern,$xml,$matches);
    if (count($matches)){
      $keys[] = '"S' . base2view(dechex($i)) . '"';
    } 
  } 
  if (count($keys)){
    echo '  S' . base2view($first) . ': [' . implode(", ",$keys) . '],' . "\n";
  }
} 
echo "};\n";
?>

