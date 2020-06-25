<?php
/*
#@+leo-ver=4-thin
#@+node:ses.20070627121637:@thin W:/www/flag_order.php
#@@first
#@@first
#@delims /* */ 

$rSL = 5;
include 'styleA.php';
$order= $_REQUEST['order'];
$update= $_REQUEST['update'];
include 'styleB.php';
$subHead="flag order";
include 'header.php';
$filename = "flag_order.txt"; 
if ($update=="update"){
  $out = fopen($filename, "w");
  fwrite($out, $order);
  fclose($out);}

//if file exists
$flag_order = file_get_contents($filename);

$keySGN = array();
$d = dir($data . '/sgn');
while (false !== ($entry = $d->read())) {
  if ($entry!="." && $entry!=".."){
    if (is_dir($data . "/sgn/" . $entry)){
     $keySGN[]=$entry;
    }
  }
}
$d->close();

$flag_lines= explode("\n",str_replace("\r","",$flag_order));
foreach ($flag_lines as $i=>$line){
  $flag_lines[$i] = explode(",",$line);
  $cnt = count($flag_lines[$i]);
  $max = max($max,$cnt);
}

$flags= explode(",",str_replace("\r","",str_replace("\n",",",$flag_order)));

//now compare and contrast flag order and sgn key
$result = array_diff($keySGN,$flags);
if (count($result)){
  $missing = 'Missing<p>' . implode(",",$result);
} else {
  $missing = "All puddles accounted for.";
}
//display user list
echo "<form action='$PHP_SELF' method=post>";
echo '<table cellpadding=5 border=1>';
echo '<tr>';
echo '<th>Flag order</th>';
echo '<td><textarea cols=40 rows=7 name=order>' . $flag_order . '</textarea></td>';
echo '<th>' . $missing . '</th>';
echo '</tr></table>';
echo '<input type="hidden" name="update" value="update">';
echo '<P><button type="submit">Update</button>';
echo '</form>';

echo "<br><p><h2>Puddle key</h2>";
echo "<table cellpadding=2 border=1><th>ID</th><th>Title</th></tr>";
foreach ($keySGN as $sgnID){
  echo "<tr><td>" . $sgnID . "</td><td>" . displayEntry(0,"a","sgn",$sgnID) . "</td></tr>"; 
}
echo "</table>";
include 'footer.php';
/*@@last*/
/*@nonl*/
/*@-node:ses.20070627121637:@thin W:/www/flag_order.php*/
/*@-leo*/
?>