<?php
include 'styleA.php';
if(!$register) {die();}
$action= $_REQUEST['action'];
$username= $_REQUEST['username'];
$display= $_REQUEST['display'];
$email= $_REQUEST['email'];
$upsw= $_REQUEST['upsw'];
include 'styleB.php';
$subHead=displayEntry(101,'t',"ui");
include 'header.php'; 

if ($action=='register'){
  if (!array_key_exists($username,$userlist)){
    $userlist[$username]["display"] = $display;
    $userlist[$username]["security"] = 0;
    $userlist[$username]["email"] = $email;
    $userlist[$username]["password"] = md5($upsw);
    archiveUsers();
    $localusers[$username]["security"] = $register;
    archiveAdmin();
  } else {
    //add to localusers if it increases security level
    if(array_key_exists($username,$localusers)){
      if (($register>$localusers[$username]["security"]) or ($register>$userlist[$username]["security"])){
        $localusers[$username]["security"] = $register;
        archiveAdmin();
      }
    } else {
      $localusers[$username]["security"] = $register;
      archiveAdmin();
    }
  }
} else {
  $err = " ";
}

if ($err){
  if(trim($err)){  echo '<h2>' . $err . '</h2>';}
  //display user list
  echo '<table cellpadding=5 border=1>';
  echo '<tr>';
  echo '<th>' . displayEntry(95,'t',"ui") . '</th>';
  echo '<th>' . displayEntry(92,'t',"ui") . '</th>';
  echo '<th>' . displayEntry(96,'t',"ui") . '</th>';
  echo '<th>' . displayEntry(99,'t',"ui") . '</th>';
  echo '<th>' . displayEntry(82,'t',"ui") . '</th>';
  echo '<th></th>';
  echo '</tr>';
  //security options array
  $sa = array(1=>87,2=>88,3=>89,4=>90,5=>91);

  //new user line...
  echo "<form action='$PHP_SELF' method=post>";
  echo '<tr>';
  echo '<th><input name="username"></th>';
  //user security
  echo '<th>' . displayEntry($sa[$register],'t',"ui");
  echo '</th>';

  echo '<th><input name="display"></th>';
  echo '<th><input name="email"></th>';
  echo '<th><input type=password name="upsw"></th>';

  echo '<th>';
  echo '<input type="hidden" name="action" value="register">';
  echo '<button type="submit">';
  echo displayEntry(101,"i","ui");
  echo '</button>';
  echo '</th>';
  echo '</tr>';
  echo '</form>';

  echo '</table>';
} else {
  echo '<h1>'; 
  echo displayEntry(161,"t","ui");
  echo '</h1>';
  echo getSignText(161,"ui","ui",2);
}





include 'footer.php';
/*@@last*/
/*@nonl*/
/*@-node:ses.20070301102721:@thin W:/www/register.php*/
/*@-leo*/
?>
