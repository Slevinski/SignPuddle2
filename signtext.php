<?php
/*
#@+leo-ver=4-thin
#@+node:slevin.20070121151424:@thin W:/www/signtext.php
#@@first
#@@first
#@delims /* */ 
$rSL = 1;
include("styleA.php");

?>
<html>
<head>
<LINK REL=STYLESHEET HREF="standard.css" TYPE="text/css">
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<title>SignText Editor</title>
<script language="Javascript" src="library/dynapi/dynapi.js"></script>
<script language="Javascript" src="keyISWA.js"></script>
<script language="Javascript">
DynAPI.setLibraryPath('library/');
DynAPI.include('dynapi.api.*');
DynAPI.include('dynapi.event.*');
<?php
$lst = str_replace("\r","",$_REQUEST['list']);
//if(!$_REQUEST['sgntxt']){
//  parse_str($GLOBALS['QUERY_STRING'],$qsArray);
//  $_REQUEST['sgntxt']=$qsArray['sgntxt'];
//}
$sgntxt = $_REQUEST['sgntxt'];
if ($sgntxt){
  $lst = str_replace("\n",'\n',ksw2lst($sgntxt,1));
}
echo("swis_glyph = \"" . $swis_glyph . "\";\n");
echo("bLoad = \"" . $lst . "\";\n");
echo("uiVal = \"" . $ui . "\";\n");
echo("sgnVal = \"" . $sgn . "\";\n");
echo("sid = \"" . urldecode($_REQUEST['sid']) . "\";\n");
echo("iGLi = \"" . addslashes(displayIcon(17)) . "\";\n");
echo("iPRi = \"" . addslashes(displayIcon(18)) . "\";\n");
echo("iSNi = \"" . addslashes(displayIcon(29)) . "\";\n");
echo("iCSi = \"" . addslashes(displayIcon(20)) . "\";\n");
echo("iDSi = \"" . addslashes(displayIcon(21)) . "\";\n");
echo("iCAi = \"" . addslashes(displayIcon(22)) . "\";\n");
echo("iVRi = \"" . addslashes(displayIcon(23)) . "\";\n");
echo("iMSi = \"" . addslashes(displayIcon(24)) . "\";\n");
echo("iFSi = \"" . addslashes(displayIcon(25)) . "\";\n");
echo("iPOi = \"" . addslashes(displayIcon(26)) . "\";\n");
echo("iRCCi = \"" . addslashes(displayIcon(27)) . "\";\n");
echo("iRCi = \"" . addslashes(displayIcon(28)) . "\";\n");
echo("iLLi = \"" . addslashes(displayIcon(37)) . "\";\n");
echo("iLCi = \"" . addslashes(displayIcon(38)) . "\";\n");
echo("iLRi = \"" . addslashes(displayIcon(39)) . "\";\n");
echo("iSAVEi = \"" . addslashes(displayIcon(54)) . "\";\n");

?>
</script>
<script language="Javascript" src="signtext.js">
</script>
</head>

<body bgcolor="#ffffff">
</body>
</html>
<?php
/*@@last*/
/*@-node:slevin.20070121151424:@thin W:/www/signtext.php*/
/*@-leo*/
?>
