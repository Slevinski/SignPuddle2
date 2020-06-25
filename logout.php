<?php
/*
#@+leo-ver=4-thin
#@+node:ses.20070216105216:@thin W:/www/logout.php
#@@first
#@@first
#@delims /* */ 
  $rSL = 0;
  include 'styleA.php';
  unset($_SESSION['puddle_usr']);
  unset($_SESSION['puddle_psw']);
  setSecurity();
  include 'styleB.php';
  $subHead=displayEntry(81,'t',"ui");
  include 'header.php'; 
  echo '<h2>' . $subHead . '</h2>';
  include 'footer.php';
/*@@last*/
/*@-node:ses.20070216105216:@thin W:/www/logout.php*/
/*@-leo*/
?>
