<?php
header("Content-type: text/plain");
header('Content-Disposition: filename=datafile.txt');

include "msw.php";
include "spl.php";
$BaseSymbols = loadBaseSymbols();
for ($b=0;$b<652;$b++){
  $base = dechex($b+256);
  $bs = $BaseSymbols[$base];
  for ($f=0;$f<6;$f++){
    for ($r=0;$r<16;$r++){
      $key = 'S' . dechex($b+256) . dechex($f) . dechex($r);
      if (validKey($key)){
        $bsw = key2bsw($key);
        $chars = str_split($bsw,3);
        $uni = array();
        foreach ($chars as $char){
          $uni[] = '1D' . strtoupper(dechex(hexdec(700) +  hexdec($char)));
        }
        $uni = implode(' ',$uni);
        $uni = str_replace(" 1DA9A",'',$uni);
        $uni = str_replace(" 1DAA0",'',$uni);
        $name = 'SIGNWRITING ' . strtoupper($bs['name']);
        $name .= ' with ';
        if ($f==0) $name .= 'inherent ';
        $name .= 'F' . (1+$f) . ' and ';
        if ($r==0) $name .= 'inherent ';
        $name .= 'R' . (1+$r);
        echo $uni . '; ' . $name . "\n";
      }
    }
  }
}
?>

