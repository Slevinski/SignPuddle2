<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
include 'styleA.php';
$emails = array();
if ($_REQUEST['key']=='admin_only') {
  header("Content-Type: text/plain; charset=utf-8");
  header('Content-Disposition: filename=user.txt');
  foreach ($userlist as $name=>$user){
    if(array_key_exists($user['email'],$emails)){
      echo $user['email'] . "\n";
    }
    $emails[$user['email']] = $user['password'];
    //echo $name . "\t" . $user['display'] . "\t\t" . $user['email'] . "\t" . $user['password'] . "\t" . $user['temp'] . "\t" . $user['security'] . "\t\n";
  }
}
?>
