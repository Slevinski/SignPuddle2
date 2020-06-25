<?php
$commands=array();
function dirscan($dir=".",$level=0){
  global $commands;
  if (substr($dir,0,11)=="./data/sgn/"){
    $commands[]="chmod 755 1*";
    $commands[]="chmod 755 2*";
    $commands[]="chmod 755 3*";
    $commands[]="chmod 755 4*";
    $commands[]="chmod 755 5*";
    $commands[]="chmod 755 6*";
    $commands[]="chmod 755 7*";
    $commands[]="chmod 755 8*";
    $commands[]="chmod 755 9*";
  } else {
    $commands[]="chmod 755 *";
  }
  if ($handle = opendir($dir)) {
    while (false !== ($filename = readdir($handle))) {
      if ($filename == "." || $filename == "..") {continue;}
      $dirname = $dir . '/' . $filename;
      if(is_dir($dirname)) {
        $commands[] = 'cd ' . $filename; 
        dirscan($dirname,$level+1); 
        $commands[] = 'cd ..'; 
      }
    }
    closedir($handle);
  }
}
//chdir("..");
dirscan();
foreach ($commands as $command){
  echo $command . "<br>";
}
?>
