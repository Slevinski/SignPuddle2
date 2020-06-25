<?php
$rSL = 1;
set_time_limit(60);
include 'styleA.php';

$subHead='FingerSpeller';
  //main page
include 'styleB.php';
include 'header.php'; 
$build = $_REQUEST['build'];
$fngr = $_REQUEST['fngr'];
if (get_magic_quotes_gpc()){
  $build = stripslashes($build);
}

$files = $data . '/sgn/*.fngr';
$list = glob($files);
$fingers = array();
$alt = '';
foreach ($list as $file){
  $file = str_replace('.fngr','',str_replace($data . '/sgn/','',$file));
  $sign = readSign(0,'sgn',$file);
  $fingers[$file] = $sign["trm"][0];
  if (!$alt) $alt = $file;
}

//get fngr files for this and others

echo '<form action="' . $SELF_PHP . '" method="get">';
echo '<h3>';
echo "Create fingerspelling from typed words";
//echo getSignTitle(76,"ui");
echo '</h3>';
echo '<table border><tr><td> ';
echo '<input type="text" NAME="build" value="' . $build . '"></input>';
echo '</td><td>';
echo '<select name="fngr">';
if (!$fngr) $fngr = $sgn;
$found = false;
foreach ($fingers as $index=>$val){
  echo '<option value="' . $index . '"';
  if ($fngr==$index){
    echo ' selected';
    $found = true;
  }
  echo '>' . $val . '</option>';
}
if (!$found) $fngr = $alt;
echo '</select>';
echo '</td></tr>';
echo '<tr><td></td><td>';
echo '<button type="submit">';
echo "Create";
//echo getSignTitle(76,"ui");//translate
echo '</button>';
echo '</td></tr>';
echo '</table>';
echo '</form>';
$fspell = array();
$re_list = array();
if ($build){
 if($fh = fopen($data . '/sgn/' . $fngr . '.fngr',"r")){
    while (($line=fgets($fh))!==false){
      $parts = explode(',',$line);
      $fspell[strtoupper($parts[0])] = $parts[1];
      $re_list[] = strtoupper($parts[0]);
    }
    fclose($fh);
  } 
  $arr = array();
  $re = '(' . implode('|', $re_list) . ')';
  preg_match_all('/\b' . $re . '+\b/ui', $build, $arr);
  $sgntxt = '';
  foreach ($arr[0] as $word){
    $ksw = '';
    $letters = array();
    //preg_match_all('/' . $re . '/i', $word, $letters);
    preg_match_all('/' . $re . '/ui', $word, $letters);
    foreach ($letters[0] as $letter){
      $letter = strtoupper($letter);
      $ksw .= fsw2ksw($fspell[$letter]) . ' ';
    }
    //$ksw = cluster2ksw(panel2cluster(ksw2panel(trim($ksw))));
    $params = array();
    $params['signTop']=0;
    $params['signBottom']=4;
    $kswV = panelTrim(ksw2panel($ksw,1000,$params));
    stDisplay($kswV);
    sgnOptions($kswV);
    echo "<hr><br>"; 
    $kswH = panelTrim(ksw2panel(reorient($ksw),1000,$params));
    stDisplay($kswH);
    sgnOptions($kswH); 
    echo "<hr><br>"; 
    //$sgntxt .= $ksw . ' ';
  }
}


if ($sgntxt){
  stDisplay($sgntxt);
  stOptions($sgntxt);
}

include 'footer.php';

?>
