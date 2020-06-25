<?php
  ini_set('session.save_path','data/tmp');
  ini_set('session.gc_maxlifetime', 86400);

  session_start();
if(@$_REQUEST['backdoor']){
  $_SESSION['backdoor'] = $_REQUEST['backdoor'];
}
if (@$_SESSION['backdoor']){
//} else {
?>
<html>
  <head>
  </head>
  <body>
  <h2>Conversion Notice</h2>
<p>PS - If you want to accessing SignPuddle during the transition, you can use this backdoor <a href="?backdoor=styleA">link</a>.  Note carefully, the site may or may not be in working order.
  </body>
</html>
<?php
die();
}
  set_time_limit(60);
  include 'msw.php';
  include 'spml.php';
  include 'spl.php';
  include 'global.php';
  include 'loguser.php';
?>
