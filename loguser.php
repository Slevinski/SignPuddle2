<?php
function getIP() {
  $ip;
  if ($_SERVER["HTTP_CLIENT_IP"]) $ip = $_SERVER["HTTP_CLIENT_IP"];
  else if($_SERVER["HTTP_X_FORWARDED_FOR"]) $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
  else if($_SERVER["REMOTE_ADDR"]) $ip = $_SERVER["REMOTE_ADDR"];
  else $ip = "UNKNOWN";

  if(trim($ip)==""){$ip="UNKNOWN";}
  return $ip;
}

$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
$logout= $_REQUEST['logout'];
$_SESSION['ip'] = getIP();

if ($password) {
  $sTemp = $security;
  $_SESSION['puddle_usr'] = $username;
  $_SESSION['puddle_psw'] = md5($password);
  $_SESSION['ip'] = getIP();
  $errorMSG = setSecurity();
  $bForceLogin=false;
}

if ($security < $rSL) {
    $errorMSG = " "; 
}

//login 
if ($bForceLogin || $errorMSG) {
// display logon
  include 'styleB.php';
  $subHead=displayEntry(80,'t',"ui");

  include 'header.php';
//  if ($rSL > $security){
//    $sTemp = $rSL;
//  } else {
//    $sTemp = $security;
//  }
//  switch ($sTemp){
//  case 0:
//    $label = "";
//    break;
//  case 1:
//    $label = displayEntry(87,'t',"ui");
//    break;
//  case 2:
//    $label = displayEntry(88,'t',"ui");
//    break;
//  case 3:
//    $label = displayEntry(89,'t',"ui");
//    break;
//  case 4:
//    $label = displayEntry(90,'t',"ui");
//    break;
//  case 5:
//    $label = displayEntry(91,'t',"ui");
//    break;
 // default:
//    die("bad security setting... abort!");
//  }     
//  echo "<h2>" . $label . " " . displayEntry(92,'t',"ui") . "</h2>";
//  echo '<hr><br><br>';

  if (trim($errorMSG)){ echo $errorMSG . '<hr><br><br>';}
  
  echo "<form action='$PHP_SELF' method=post>";
  echo "<table cellpadding=5 border=1><tr>";
  echo "<td>" . displayEntry(95,'t',"ui") . "<br>";
  echo "<input name='username'></td>";
  echo "<td>" . displayEntry(82,'t',"ui") . "<br>";
  echo "<input type='password' name='password'></td>";
  echo "<td><button type='submit'>" . displayEntry(80,'t',"ui") . "</button></td>";
  echo "</tr></table>";
  echo "</form>";
  include 'footer.php';
  die;
}
?>
