<?php
/*
#@+leo-ver=4-thin
#@+node:ses.20070127171438.1:@thin W:/www/sequence.php
#@@first
#@@first
#@delims /* */ 
$rSL = 2;
$subHead="Search by Symbols";
include("styleA.php");

  $subHead="SignSpelling Sequence";
  ?>
<HTML>
<HEAD>
<LINK REL=STYLESHEET HREF="standard.css" TYPE="text/css">
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<title>SignPuddle <?php echo $subHead;?></title>
<script language="Javascript" src="library/dynapi/dynapi.js"></script>
<script language="Javascript">
DynAPI.setLibraryPath('library/');
DynAPI.include('dynapi.api.*');
DynAPI.include('dynapi.event.*');<?php
$sid = $_REQUEST['sid'];
if ($sid) {
  //load spelling
  $sign = readSign($sid);
  $build= ksw2bld($sign["ksw"],1);
  $seq = ksw2seq($sign["ksw"],1);
  $seq = str_replace("S",'',$seq);
  $keys = str_split($seq,5);
  $ids = array();
  foreach($keys as $key){
    $ids[] = key2id($key,1);
  }
  $sequence= implode($ids,',');
}

echo("swis_glyph = \"" . $swis_glyph . "\";\n");
echo("\nbBuild = \"" . $build . "\";\n");
echo("bSequence= \"" . $sequence. "\";\n");
echo("uiVal = \"" . $ui . "\"\n");
echo("sgnVal = \"" . $sgn . "\"\n");
echo("bSign = \"" . $sid . "\"\n");
?>
</script>
<script language="Javascript" src="sequence.js">
</script>
</head>
<body>
<?php include("header.php");?>
</td></tr></table>
</body>
</html>
<?php
/*@@last*/
/*@-node:ses.20070127171438.1:@thin W:/www/sequence.php*/
/*@-leo*/
?>
