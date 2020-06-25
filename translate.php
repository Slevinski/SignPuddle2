<?php
$rSL = 1;
include 'styleA.php';
include 'styleB.php';
$subHead=displayEntry(9,'t',"ui");
include('library/sps/columnclass.php');
include 'header.php';

$action = $_REQUEST['action'];
$build = $_REQUEST['build'];
$count = $_REQUEST['count'];

if ($action=="update"){$build = urldecode($build);}

/**
 * Part 0, function def
 */
function buildSW($arg) {
  global $sgndir;
  global $sgnwww;

  $input = puddle_spf();
  $dom = new DomDocument();
  $dom->loadXml($input,LIBXML_PARSEHUGE);
  $xpath = new DomXpath($dom);

  $arg=str_replace(",", " , ", $arg);
  $arg=str_replace(":", " : ", $arg);
  $arg=str_replace(".", " . ", $arg);
  $arg=str_replace("!", " ! ", $arg);
  $arg=str_replace("?", " ? ", $arg);
  $arg=str_replace("(", " ( ", $arg);
  $arg=str_replace(")", " ) ", $arg);
  $arg=str_replace("  ", " ", $arg);
  $arg=str_replace("  ", " ", $arg);
  $arg=str_replace("  ", " ", $arg);
  $arg=str_replace("  ", " ", $arg);
  $arg=str_replace("  ", " ", $arg);
  $arg = trim($arg);
  $arg = explode(" ",$arg);
  $output = array();

  foreach ($arg as $id => $value) {
    if ($value and strpos(" ,:;.?!()", $value)) {
      switch($value) {
        case ",":
          $output[] = '38700';
          break;
        case ".":
          $output[] = '38800';
          break;
        case "!":
          $output[] = '38810';
          break;
        case "?":
          $output[] = '38900';
          break;
        case ";":
          $output[] = '38900';
          break;
        case ":":
          $output[] = '38a00';
          break;
        case "(":
          $output[] = '38b00';
          break;
        case ")":
          $output[] = '38b04';
          break;
      }
    } else {

      $arrayExact = array();
      $arrayAny = array();
      //exact search
      $query = '//entry/term[contains(., "' . $value . '")]/..';
      $matchingNodes = $xpath->query($query);
      foreach ($matchingNodes as $match){
        $fswA = array();
        $terms = array();
        $found=0;
        foreach ($match->getElementsByTagName('term') as $node){
          $term = $node->nodeValue;
          if ($term == $value){
            $found++;
          }
          if (fswText($term)){
            $fswA[] = $term;
          } else {
            $terms[] = $term;
          }
        }
        if ($found){
          $arrayExact[$value] = array_unique(array_merge((array)$arrayExact[$value], $fswA)); 
        } else {
          $term = implode(',',$terms);
          $arrayAny[$term] = array_unique(array_merge((array)$arrayAny[$term], $fswA)); 
        }
      }

      //now I either have something, or I don't...
      if (count($arrayExact)>0) {
        $output[] = $arrayExact;
      } else if (count($arrayAny)>0) {
        $output[] = $arrayAny;
      } else {
        $output[] = 'M10x17S29f0cn9xn16';
      }

    }
  }
  return $output;
}

/**
 * Part 1, input area
 */
echo '<h3>' .  getSignTitle(56,"ui",1) . '<br>';
echo '<font color=999999 size=-1>' .  getSignTitle(195,"ui",2) . '</font></h3>';
echo '<form action="' . $SELF_PHP . '" method="post">';
echo '<table border><tr><td> ';
echo '<TEXTAREA NAME="build" COLS=40 ROWS=6>' . $build . '</TEXTAREA>';
echo '</td><td>';
echo '<input type="hidden" name="action" value="translate">';
echo '<button type="submit">';
echo displayEntry(53,"i","ui");//translate
echo '</button>';

echo '</td></tr>';
echo '</table>';
echo '</form>';

/**
 * Part 2, get the sign options...
 */
if ($action=="translate") {
  $build= str_replace("\n"," ", $build);
  $build= str_replace("\r"," ", $build);
  $list = buildSW($build);
  //list options...
  echo '<form action="' . $SELF_PHP . '" method="post">';
  echo '<input type=hidden NAME="build" value="' . urlencode($build) . '">';
  echo '<input type=hidden name="count" value="' . count($list) . '">';
  foreach ($list as $index => $item){
    $checked=0;

//    if (is_array($item)){ 
//      foreach ($item as $key => $fsws){
//        foreach($fsws as $j=>$fsw){
//          $ksw = fsw2ksw($fsw);
//        }
//        if (count($item[$key])==0){ unset($item[$key]);}
//      }
//      if (count($item)==0) $item='';
//    }
    if (is_array($item)){ 
      echo "<table cellpadding=4 border=1><tr>";
      foreach ($item as $key => $id){
        echo '<td colspan=' . count($id) . '>' . $key . '</td>';
      }
      echo '</tr><tr>';
      foreach ($item as $key => $fsws){
        foreach($fsws as $fsw){
          $ksw = fsw2ksw($fsw);
          if (!trim($ksw)){ continue;}
          echo '<td valign=top><INPUT TYPE=RADIO NAME="sign' . $index . '" VALUE="' . $ksw . '"';
          if ($checked==0) { echo ' CHECKED'; $checked++;}
          echo '><br><br><img src="' . $swis_glyphogram . '?ksw=' . $ksw . $glyph_line . '"></td>';
        }
      }
      echo "</tr></table>";
    } else {
      echo '<br><input type=hidden name="sign' . $index . '" value="' . raw2ksw('S' . $item) . '"><img src="' . $swis_glyphogram . '?ksw=' . raw2ksw('S' . $item) . $glyph_line . '"><br><br>';
    }
  }
  echo '<input type=hidden name="action" value="update">';
  echo '<button type="submit">';
  echo displayEntry(52,"i","ui");//update
  echo '</button>';
}

if ($action=="update"){
  $sgntxt = '';
  for($i=0;$i<$count;$i++){
    $sgntxt .= $_REQUEST['sign' . $i] . ' ';
  }
  $sgntxt=trim($sgntxt);
}  

/**
 * Part 2, display the KSW with options...
 */
if ($sgntxt){
  stOptions($sgntxt);
  stDisplay($sgntxt);
}

include 'footer.php'; 
?>
