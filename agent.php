<?php
/* user agent monitoring*/
$agent = $PHP_SELF . ': ' .  $_SERVER['HTTP_USER_AGENT'] . "\n";
//banning algorithm
$agent_ban = "spider,Bot,Googlebot,MJ12bot,yandex";
$banned = explode(',',$agent_ban);
foreach ($banned as $ban){
  $pos = strpos($agent, $ban);
  if ($pos===false) continue;
    $fp = fopen('agent_no.txt', 'a');
    fwrite($fp, $agent);
    fclose($fp);
  die();
}
$fp = fopen('agent_yes.txt', 'a');
fwrite($fp, $agent);
fclose($fp);
?>
