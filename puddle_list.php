<?php
include 'styleA.php';
//  include "db.php";
//  include 'fsw.php';

// load puddle_head source
  $input = file_get_contents('puddle_head.txt');
  $lines = explode("\n",$input);
  $pudlist = array();
  foreach ($lines as $line){
    $parts = explode("\t",$line);
    if (count($parts) >=4){
      $code = $parts[0];
      $pudlist[$code]=$parts;
    }
  }

// load language to country
  $input = file_get_contents('../swserver_data/source/language_country.txt');
  $lines = explode("\n",$input);
  $langlist = array();
  foreach ($lines as $line){
    $parts = explode("\t",$line);
    if (count($parts) >=2){
      $lang = $parts[0];
      $cc = $parts[1];
      if (!array_key_exists($lang,$langlist)){
        $langlist[$lang] = array(); 
      }
      $langlist[$lang][]=$cc;
    }
  }

$langCC = array();
$langCC['ase'] = 'US';
$langCC['fsl'] = 'FR';
$langCC['rsl'] = 'RU';
$langCC['ysl'] = 'SI';
$langCC['dse'] = 'NL';
$langCC['ins'] = 'IN';
$langCC['ils'] = 'QU';

$pudlType = array();
$pudlType['sgn151'] = 'literature';
$pudlType['sgn152'] = 'literature';
$pudlType['sgn105'] = 'literature';
$pudlType['sgn111'] = 'literature';
$pudlType['sgn17'] = 'literature';

$pudlName = array();
$pudlName['sgn147'] = 'archive';
$pudlName['sgn151'] = 'bible';
$pudlName['sgn152'] = 'shores';
$pudlName['sgn80'] = 'sort';
$pudlName['sgn105'] = 'dac';
$pudlName['sgn111'] = 'translate';
$pudlName['sgn25'] = 'signtyp';
$pudlName['sgn28'] = 'bible';
$pudlName['sgn17'] = 'harbor';
$pudlName['sgn54'] = 'signuno';

  $list = implode(',',glob('data/spml/*.spml'));
  $list = str_replace("data/spml/",'',$list);
  $list = str_replace(".spml",'',$list);
  $list = str_replace("ui,",'',$list);
  $list = str_replace("sgn,",'',$list);
  $list = explode(',',$list);

foreach ($list as $item){
  if (array_key_exists($item,$pudlist) && $item!="sgn150"){
    $lang = $pudlist[$item][1];
    $ccs = $langlist[$lang];
    if (array_key_exists($lang,$langCC)){
      $cc = $langCC[$lang];
    } else {
      $cc = implode(',',$langlist[$lang]);
      if (strpos($lang,'-')){
        $lang = substr($lang,0,3);
      }
    } 
    $type = $pudlist[$item][2];
    switch ($pudlist[$item][2]){
      case "dictionary":
        $type = "dictionary";
        $name = "public";
        break;
      case "literature":
        $type = "literature";
        $name = "public";
        break;
      case "encyclopedia":
        $type = "literature";
        $name = "encyclopedia";
        break;
      case "bible":
        $type = "dictionary";
        $name = "bible";
        break;
      default:
        $type = "dictionary";
        $name = $pudlist[$item][2];
    }
    if (array_key_exists($item,$pudlType)){
      $type = $pudlType[$item];
    }
    if (array_key_exists($item,$pudlName)){
      $name = $pudlName[$item];
    }

    $file = 'data/spml/' . $item . '.spml';
    $xml = simplexml_load_file($file);
    $long = $xml->term;
    echo $lang . "-" . $cc . '-'  . $type . '-' . $name . "\t" . $item . "\t" . $long . "\n";
  } else {
    // echo ">> " . $item . "\n";
  }
}

die();



?>

