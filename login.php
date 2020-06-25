<?php
/*
#@+leo-ver=4-thin
#@+node:ses.20070216105216.1:@thin W:/www/login.php
#@@first
#@@first
#@delims /* */ 
$rSL = 0;
$bForceLogin = 1;
include 'styleA.php';

include 'styleB.php';
$subHead=displayEntry(80,'t',"ui");
include 'header.php'; 
echo "<h2>" . displayEntry(110,'t',"ui") . "</h2>";
echo $userlist[$_SESSION["puddle_usr"]]["display"];
include 'footer.php';
/*@@last*/
/*@-node:ses.20070216105216.1:@thin W:/www/login.php*/
/*@-leo*/
?>
