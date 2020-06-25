<?php
/*
#@+leo-ver=4-thin
#@+node:ses.20070217163148:@thin W:/www/admin.php
#@@first
#@@first
#@delims /* */ 

$rSL = 5;
include 'styleA.php';
$update= $_REQUEST['update'];
$username= $_REQUEST['username'];
$usecurity= $_REQUEST['usecurity'];
$display= $_REQUEST['display'];
$email= $_REQUEST['email'];
$upsw= $_REQUEST['upsw'];
$sView= $_REQUEST['sView'];
$sAdd= $_REQUEST['sAdd'];
$sEdit= $_REQUEST['sEdit'];
$tCopy= $_REQUEST['tCopy'];
$tRegister= $_REQUEST['tRegister'];
$tUpload= $_REQUEST['tUpload'];
$vh= $_REQUEST['vh'];
$vw= $_REQUEST['vw'];
include 'styleB.php';
$subHead=displayEntry(91,'t',"ui");
include 'header.php'; 
if ($update=='update'){
  if (array_key_exists($username,$localusers)){
    $localusers[$username]["security"] = $usecurity;
    $userlist[$username]["display"] = $display;
    $userlist[$username]["email"] = $email;
    if ($upsw){
      $userlist[$username]["password"] = md5($upsw);
    }
    archiveAdmin();
    archiveUsers();
  }
}

if ($update=='remove'){
  if (array_key_exists($username,$localusers)){
    unset($localusers[$username]);
    archiveAdmin();
  }
}

if ($update=='delete'){
  if (array_key_exists($username,$localusers)){
    unset($localusers[$username]);
    archiveAdmin();
    unset($userlist[$username]);
    archiveUsers();
  }
}
if ($update=='include'){
  if (!array_key_exists($username,$localusers)){
    $localusers[$username]["security"] = $usecurity;
    archiveAdmin();
  }
}

if ($update=='add'){
  if (!array_key_exists($username,$userlist)){
    $localusers[$username]["security"] = $usecurity;
    archiveAdmin();

    $userlist[$username]["display"] = $display;
    $userlist[$username]["security"] = 0;
    $userlist[$username]["email"] = $email;
    $userlist[$username]["password"] = md5($upsw);
    archiveUsers();
  }
}

if ($update=='security'){
  if($sView) {$view=1;} else {$view=0;}
  if($sAdd) {$add=1;} else {$add=0;}
  if($sEdit) {$edit=1;} else {$edit=0;}
  archiveAdmin();
}

if ($update=='settings'){
  if($tCopy) {$copy=1;} else {$copy=0;}
  if($tRegister) {$register=$tRegister;} else {$register=0;}
  if($tUpload) {$upload=$tUpload;} else {$upload=0;}
  if($vh) {$video_height=intval($vh);} else {$video_height=0;}
  if($vw) {$video_width=intval($vw);} else {$video_width=0;}
  archiveAdmin();
}

//display user list
echo '<table cellpadding=5 border=1>';
echo '<tr>';
echo '<th>' . displayEntry(95,'t',"ui") . '</th>';
echo '<th>' . displayEntry(92,'t',"ui") . '</th>';
echo '<th>' . displayEntry(96,'t',"ui") . '</th>';
echo '<th>' . displayEntry(99,'t',"ui") . '</th>';
echo '<th>' . displayEntry(82,'t',"ui") . '</th>';
echo '<th></th><th></th><th></th>';
echo '</tr>';
//security options array
$sa = array(1=>87,2=>88,3=>89,4=>90,5=>91);
foreach ($userlist as $username=>$user){
  if ($user["security"]){
    echo '<tr>';
    echo '<th>' . $username . '</th>';
    echo '<th>' . displayEntry($sa[$user["security"]],'t',"ui") . '</th>';
    echo '<th>' . $user["display"] . '</th>';
    echo '<th>' .$user["email"] . '</th>';
    echo '<th></th>';

    echo '<th>';
    echo '</th>';
    echo '<th>';
    echo '</th>';
    echo '<th>';
    echo '</th>';
    echo '</tr>';
  }
}
ksort ($localusers);
foreach ($localusers as $username=>$user){
  $uSec = $user["security"];
  $user = $userlist[$username];
  echo "<form action='$PHP_SELF' method=post>";
  echo '<tr>';
  echo '<th>' . $username . '<input type=hidden name="username" value="' . htmlspecialchars($username) . '"></th>';
  //user security
  echo '<th><select name="usecurity">';
  foreach ($sa as $sv => $st) {
    echo '<option value="' . $sv . '" ';
    if ($sv == $uSec){echo "selected";}
    echo '>' . displayEntry($st,'t',"ui");
  }  
  echo '</select></th>';

  echo '<th><input name="display" value="' . htmlspecialchars($user["display"]) . '"></th>';
  echo '<th><input name="email" value="' . htmlspecialchars($user["email"]) . '"></th>';
  echo '<th><input type=password name="upsw" value=""></th>';

  echo '<th>';
  echo '<button type="submit" name="update" value="update">';
  echo displayEntry(52,"i","ui");
  echo '</button>';
  echo '</th>';
  echo '<th>';
  echo '<button type="submit" name="update" value="remove">';
  echo displayEntry(46,"i","ui");
  echo '</button>';
  echo '</th>';
  echo '<th>';
  echo '<button type="submit" name="update" value="delete">';
  echo displayEntry(98,"i","ui");
  echo '</button>';
  echo '</th>';
  echo '</tr>';
  echo '</form>';
}

//existing user for local access
echo "<form action='$PHP_SELF' method=post>";
echo '<tr>';
echo '<th><select name="username"><option value="">';
ksort ($userlist);
foreach ($userlist as $username=>$user){
  if ($user["security"]==0){
    if (!array_key_exists($username,$localusers)){
      echo '<option value="' . htmlspecialchars($username) . '" ';
      echo '>' . htmlspecialchars($username);
    }
  }
}
echo '</select></th>';
//user security
echo '<th><select name="usecurity">';
foreach ($sa as $sv => $st) {
  echo '<option value="' . $sv . '" ';
  echo '>' . displayEntry($st,'t',"ui");
}  
echo '</select></th>';

echo '<th></th>';
echo '<th></th>';
echo '<th></th>';
echo '<th>';
echo '<button type="submit" name="update" value="include">';
echo displayEntry(52,"i","ui");
echo '</button>';
echo '</th>';
echo '<th>';
echo '</th>';
echo '</tr>';
echo '</form>';
    

//new user line...
  echo "<form action='$PHP_SELF' method=post>";
  echo '<tr>';
  echo '<th><input name="username"></th>';
  //user security
  echo '<th><select name="usecurity">';
  foreach ($sa as $sv => $st) {
    echo '<option value="' . $sv . '" ';
    echo '>' . displayEntry($st,'t',"ui");
  }  
  echo '</select></th>';

  echo '<th><input name="display"></th>';
  echo '<th><input name="email"></th>';
  echo '<th><input type=password name="upsw"></th>';

  echo '<th>';
  echo '<button type="submit" name="update" value="add">';
  echo displayEntry(49,"i","ui");
  echo '</button>';
  echo '</th>';
  echo '<th>';
//  echo '<button type="submit" name="update" value="invite">';
//  echo displayEntry(97,"i","ui");
//  echo '</button>';
  echo '</th>';
  echo '<th></th>';
  echo '</tr>';
  echo '</form>';

echo '</table>';

echo "<br><hr>";
//security settings
  $label = displayEntry(82,'t',"ui");
  $label .= ' ';
  $label .= displayEntry(92,'t',"ui");
  echo '<h2>' . $label . '</h2>';
  echo '<form action="' . $PHP_SELF . '" method=post>';
  echo '<table cellpadding=5 border=1><tr>';
  echo '<tr><td>' . displayEntry(87,'t','ui') . '</td>';
  echo '<td><input type="checkbox" name="sView" ';
  if ($view) {echo 'checked';}
  echo '></td></tr>';
  echo '<tr><td>' . displayEntry(88,'t','ui') . '</td>';
  echo '<td><input type="checkbox" name="sAdd" ';
  if ($add) {echo 'checked';}
  echo '></td></tr>';
  echo '<tr><td>' . displayEntry(89,'t','ui') . '</td>';
  echo '<td><input type="checkbox" name="sEdit" ';
  if ($edit) {echo 'checked';}
  echo '></td></tr>';
  echo '<tr><td><button name="update" value="security" type="submit">' . displayEntry(52,'i','ui') . '</button></td>';
  echo '</tr></table>';
  echo '</form>';

//other settings
  echo '<hr>';
  echo '<h2>' . displayEntry(100,'t',"ui") . '</h2>';
  echo '<form action="' . $PHP_SELF . '" method=post>';
  echo '<table cellpadding=5 border=1><tr>';

  //allow copy
  echo '<tr><td>' . displayEntry(108,'t','ui') . '</td>';
  echo '<td><input type="checkbox" name="tCopy" ';
  if ($copy) {echo 'checked';}
  echo '></td></tr>';

  //registration
  echo '<tr><td>' . displayEntry(101,'t','ui') . '</td>';
  echo '<td>';
  echo '<select name="tRegister">';
  echo '<option value="0"';
  if ($register==0) { echo ' selected';}
  echo '>';
  foreach ($sa as $sv => $st) {
    echo '<option value="' . $sv . '"';
    if ($sv == $register){echo " selected";}
    echo '>' . displayEntry($st,'t',"ui");
  }  
  echo '</select></td><tr>';
  //upload
  echo '<tr><td>' . displayEntry(77,'t','ui') . '</td>';
  echo '<td>';
  echo '<select name="tUpload">';
  echo '<option value="0"';
  if ($upload==0) { echo ' selected';}
  echo '>';
  foreach ($sa as $sv => $st) {
    echo '<option value="' . $sv . '"';
    if ($sv == $upload){echo " selected";}
    echo '>' . displayEntry($st,'t',"ui");
  }  
  echo '</select></td><tr>';

// video width and height
  echo '<tr><td>' . "video width" . '</td><td>';
  echo '<input name="vw" value="' . $video_width . '"></td></tr>';
  echo '<tr><td>' . "video height" . '</td><td>';
  echo '<input name="vh" value="' . $video_height . '"></td></tr>';
  echo '</table>';

  echo '<button type="submit" name="update" value="settings">';
  echo displayEntry(52,"i","ui");
  echo '</button>';
  echo '</form>';

include 'footer.php';
/*@@last*/
/*@nonl*/
/*@-node:ses.20070217163148:@thin W:/www/admin.php*/
/*@-leo*/
?>
