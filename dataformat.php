<?php
$rSL = 1;
include 'styleA.php';
$subHead=getSignTitle(140,"ui");

echo '<html><head><title>' . $subHead . '</title>';
?>
<LINK REL=STYLESHEET HREF="standard.css" TYPE="text/css">
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK REL=STYLESHEET HREF="columns.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript">

function SetParams(){
  if (document.options.content.value >2) {
    document.options.params.value = "length=400";
  } else {
    document.options.params.value = "";
  }
} 
</SCRIPT>
</head>
<body>

<?php
//$subHead=displayEntry(9,'t',"ui");
include 'header.php';

$sgntxt = $_REQUEST['sgntxt'];
$ksw = $_REQUEST['ksw'];
$fsw = $_REQUEST['fsw'];

$markup = $_REQUEST['markup'];
$ident = $_REQUEST['ident'];
$content = $_REQUEST['content'];
$params = $_REQUEST['params'];
$view = $_REQUEST['view'];

$qview = $_REQUEST['qview'];

if ($ksw) $sgntxt = $ksw;
if ($fsw) $sgntxt = fsw2ksw($fsw);

if ($sgntxt){

  stDisplay($sgntxt);


  echo "<form name='options' method=post action=$PHP_SELF>";
  echo "<input type=\"hidden\" name=\"sgntxt\" value =\"$sgntxt\">";

  echo "<h2>" . getSignTitle(141,"ui") . "</h2>";

  echo "<table border=0 cellpadding=6 border=0><tr>";

  echo '<td><input type="submit" name="qview" value="BSW"></td>';
  echo '<td><input type="submit" name="qview" value="CSW"></td>';
  echo '<td><input type="submit" name="qview" value="KSW"></td>';
  echo '<td><input type="submit" name="qview" value="FSW"></td>';
  echo '<td><input type="submit" name="qview" value="SWU"></td>';

  echo '</tr></table></form>';

  if ($qview){
    echo '<hr><h3>' . $qview . '</h3>';
    switch ($qview){
  	case 'BSW':
  	  echo fsw2bsw(ksw2fsw($sgntxt));
  	  break;
  	case 'CSW':
          echo "\n";
  	  echo '<span style="font-family:iswa;">';
  	  echo bsw2csw(fsw2bsw(ksw2fsw($sgntxt)));
  	  echo '</span>';
  	  break;
  	case 'FSW':
  	  echo ksw2fsw($sgntxt);
  	  break;
  	case 'SWU':
  	  echo '<p style="font-family:SuttonSignWritingOneD;font-size:30px">' . fsw2swu(ksw2fsw($sgntxt)) . '</p>';
  	  break;
  	case 'KSW':
  	  echo $sgntxt;
  	}
  }


  echo "<form name='options' method=post action=$PHP_SELF>";
  echo "<input type=\"hidden\" name=\"sgntxt\" value =\"$sgntxt\">";

  echo "<br><hr><h2>XML Options</h2>";

  echo "<table border=0 cellpadding=6 border=0>";

  echo "<tr><td>" . getSignTitle(143,"ui") . "</td><td><select name='ident'>";
  $opts = array('Image','ID','Code','Key','BSW','CSW','SWU');
  foreach($opts as $j=>$opt){
    echo "<option value='" . $j . "' ";
    if ($ident==$j) {echo "selected";}
    echo ">" . $opt;
  }
  echo "</select></td></tr>";

  echo "<tr><td>" . getSignTitle(144,"ui") . "</td><td><select name='content'  onChange='SetParams()'>";
  $opts = array('Minimal','Expanded','Layout');
  foreach($opts as $j=>$opt){
    echo "<option value='" . $j . "' ";
    if ($content==$j) {echo "selected";}
    echo ">" . $opt;
  }
  echo "</select></td></tr>";

  echo "<tr><td></td>";
  echo '<td><input type="submit" name="view" value="View"></td></tr>';

  
  echo "</table>";

  echo "<hr>";
  //first, get the data in order

  if ($view=="View"){
    switch ($content){
  	case 0: //"Minimal":
  	  $ksw = ksw2raw($sgntxt);
  	  break;
  	case 1: //"Expanded":
  	  $ksw = ksw2expand($sgntxt);
  	  break;
  	case 2: //"Layout":
  	  $ksw = $sgntxt;
  	  break;
//  	case 3: //"Display":
//  	  parse_str($params,$params_parse); 
//  	  $cols = explode(' ',ksw2panel($sgntxt,$params_parse['length'],$params_parse));
//  	  $ksw = implode(' ',$cols);
//  	  break;
//  	case 4: //"Columns":
//  	  parse_str($params,$params_parse); 
//  	  $cols = explode(' ',ksw2panel($sgntxt,$params_parse['length'],$params_parse));
//  	  $outcols = array();
//  	  foreach ($cols as $col){
//  		$outcols[] = cluster2ksw(panel2cluster($col));
//  	  }
//  	  $ksw = implode(' ',$outcols);
//  	  break;
    }
    //second, get the replacement symbol strings...
    $replace = array();
    $pattern = '/S[123][a-f0-9]{2}[012345][a-f0-9]/i';
    preg_match_all($pattern, $ksw,$matches);
    forEach ($matches[0] as $key){
  	if (!array_key_exists($key,$replace)){
  	  switch ($ident){
  		case 0: //"Image":
  		  $replace[$key]=' <img src="' . $swis_glyph . '?key=' . str_replace('S','',$key) . '&size=.7">';
  		  break;
  		case 1: //"ID":
  		  $replace[$key] = key2id($key,1);
  		  break;
  		case 2: //"Code":
  		  $replace[$key] = 'I' . str_pad(dechex(key2code($key)),4,'0',STR_PAD_LEFT);
  		  break;
  		case 3: //"Key":
  		  $replace[$key] = $key;
  		  break;
  		case 4: //"BSW":
  		  $replace[$key] = key2bsw($key);
  		  break;
  		case 5: //"Preliminary Unicode":
  		  $replace[$key] = bsw2csw(key2bsw($key));
  		  break;
  		case 6: //"SignWriting in Unicode":
  		  $replace[$key] = dec2utf(key2code($key),4);
  		  break;
        }
      }
    }
  
  	$xml = '';
  	$words = explode(' ',trim($ksw));
  	foreach ($words as $word){
  	  if (isPunc($word)) {
  		$len = strlen ($word);
  		$coord = str2koord(substr($word,6,$len-6));
  		switch ($content){
  		  case 0: //"Minimal":
  			$xml .= '<punc>' . substr($word,0,6) . '</punc>' . "\n";
  			break;
  		  case 1: //"Expanded":
  			$xml .= '<punc width="' . $coord[0] . '" height="' . $coord[1] . '">' . substr($word,0,6) . '</punc>' . "\n";
  			break;
  		  case 2: //"Layout":
  			$xml .= '<punc left="' . $coord[0] . '" top="' . $coord[1] . '">' . substr($word,0,6) . '</punc>' . "\n";
  			break;
  		  case 3: //"Display":
  			$xml .= '<punc offset_x="' . $coord[0] . '" offset_y="' . $coord[1] . '">' . substr($word,0,6) . '</punc>' . "\n";
  			break;
  		  case 4: //"Layout":
  			$xml .= '<punc left="' . $coord[0] . '" top="' . $coord[1] . '">' . substr($word,0,6) . '</punc>' . "\n";
  			break;
  		}
  	  } else {
  		$xml .= '<signbox';
  		if ($content==1){
  		  $cluster=expand2cluster($word);
  		} else {
  		  $cluster = ksw2cluster($word);
  		}
  		switch($cluster[0][0]){
  		  case "L":
  			$xml .= ' lane="Left"';
  		  case "R":
  			$xml .= ' lane="right"';
  		}
  		//now additional coords
  		$coord = str2koord($cluster[0][1]);
  		switch ($content){
  		  case 0: //"Minimal":
  			break;
  //          case 1: //"Expanded": 
  //            $xml .= ' width="' . $coord[0] . '" height="' . $coord[1] . '"';
  //            break;
  		  case 2: //"Layout":
  			$xml .= ' max_x="' . $coord[0] . '" max_y="' . $coord[1] . '"';
  			break;
  		  case 3: //"Display":
  			$xml .= ' offset_x="' . $coord[0] . '" offset_y="' . $coord[1] . '"';
  			break;
  		  case 4: //"Display":
  			$xml .= ' width="' . $coord[0] . '" height="' . $coord[1] . '"';
  			break;
  		}
  		
  		$xml .= '>' . "\n";
  
  		$seq = ksw2seq($word);
  		if ($seq) {
  		  $asyms = str_split($seq,6);
  		  foreach ($asyms as $asym){
  			$xml .= '  <seq>' . $asym . '</seq>' . "\n";
  		  }
  		}
  
  		for($i=1;$i<count($cluster);$i++){
  		  $xml .= '  <sym';
  		  $coord = str2koord($cluster[$i][1]);
  		  if ($content==1){  //expanded
  			$xml .= ' width="' . $coord[0] . '" height="' . $coord[1] . '"';
  			$xml .= ' left="' . $coord[2] . '" top="' . $coord[3] . '"';
  		  } else {
  			$xml .= ' left="' . $coord[0] . '" top="' . $coord[1] . '"';
  		  }
  		  $xml .= '>';
  		  $xml .= $cluster[$i][0];
  		  $xml .= '</sym>' . "\n";
  		}
  		$xml .= '</signbox>' . "\n";
  	  }
  	}
  	$xml = str_replace("<","&lt;",$xml);
  	$xml = str_replace(">","&gt;",$xml);
  	$xml = str_replace("\n","<br>",$xml);
  	$xml = str_replace("  ","&nbsp;&nbsp;",$xml);
  	foreach ($replace as $key=>$sym){
  	  if ($ident==2){
  		$code = hexdec(substr($sym,1,4));
  		$xml = str_replace($key,$code,$xml);
  	  } else {
  		$xml = str_replace($key,$sym,$xml);
  	  }
  	}
  	echo $xml;
    }
}

include 'footer.php'; 
?>
