<?php
/*
#@+leo-ver=4-thin
#@+node:ses.20070204154538:@thin W:/www/signmaker.php
#@@first
#@@first
#@delims /* */ 
$rSL = 2;
include("styleA.php");
//$subHead="SignMaker";
$subHead=displayEntry(8,'t',"ui");

?>

<html>
<head>
<LINK REL=STYLESHEET HREF="standard.css" TYPE="text/css">
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<title>SignPuddle - SignMaker</title>
<SCRIPT LANGUAGE="Javascript" SRC="PopupWindow.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="AnchorPosition.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="ColorPicker2.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
var cp = new ColorPicker('window'); // Popup window
var cp2 = new ColorPicker(); // DIV style
</SCRIPT>
<script language="Javascript" src="library/dynapi/dynapi.js"></script>
<script language="Javascript" src="keyISWA.js"></script>
<script language="Javascript" src="keyColor.js"></script>
<script language="Javascript">
DynAPI.setLibraryPath('library/');
DynAPI.include('dynapi.api.*');
DynAPI.include('dynapi.event.*');
<?php
$build = $_REQUEST['build'];
$ksw = $_REQUEST['ksw'];
if ($ksw) $build = ksw2bld($ksw,1);
echo("swis_glyph = \"" . $swis_glyph . "\";\n");
echo("bLoad = \"" . $build . "\";\n");
echo("uiVal = \"" . $ui . "\";\n");
echo("sgnVal = \"" . $sgn . "\";\n");
echo("sid = \"" . urldecode($_REQUEST['sid']) . "\";\n");
echo("iGLi = \"" . addslashes(displayIcon(17)) . "\";\n");
echo("iPRi = \"" . addslashes(displayIcon(18)) . "\";\n");
echo("iADi = \"" . addslashes(displayIcon(19)) . "\";\n");
echo("iCSi = \"" . addslashes(displayIcon(20)) . "\";\n");
echo("iDSi = \"" . addslashes(displayIcon(21)) . "\";\n");
echo("iCAi = \"" . addslashes(displayIcon(22)) . "\";\n");
echo("iVRi = \"" . addslashes(displayIcon(23)) . "\";\n");
echo("iMSi = \"" . addslashes(displayIcon(24)) . "\";\n");
echo("iFSi = \"" . addslashes(displayIcon(25)) . "\";\n");
echo("iPOi = \"" . addslashes(displayIcon(26)) . "\";\n");
echo("iRCCi = \"" . addslashes(displayIcon(27)) . "\";\n");
echo("iRCi = \"" . addslashes(displayIcon(28)) . "\";\n");
echo("iSNi = \"" . addslashes(displayIcon(29)) . "\";\n");
echo("iSCi = \"" . addslashes(displayIcon(30)) . "\";\n");
echo("iPCi = \"" . addslashes(displayIcon(31)) . "\";\n");
?>
</script>
<script language="Javascript" src="signmaker.js">
</script>
</head>

<body bgcolor="#ffffff">
<?php include("header.php");?>
</td></tr></table>
<?php include 'ui_elem.php';?>
</body>
</html>
<?php
/*@@last*/

/*@-node:ses.20070204154538:@thin W:/www/signmaker.php*/
/*@-leo*/
?>
