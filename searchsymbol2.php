<?php
/*
#@+leo-ver=4-thin
#@+node:slevin.20070119161527.2:@thin W:/www/searchsymbol.php
#@@first
#@@first
#@delims /* */ 
$rSL = 1;
include("styleA.php");
$subHead="Search by Symbols";
$subHead=displayEntry(6,'t',"ui");

?>
<html>
<head>
<LINK REL=STYLESHEET HREF="standard.css" TYPE="text/css">
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<title>SignPuddle - SignSearch</title>
<script language="Javascript" src="library/dynapi/dynapi.js"></script>
<script language="Javascript" src="keyISWA.js"></script>
<script language="Javascript">
DynAPI.setLibraryPath('library/');
DynAPI.include('dynapi.api.*');
DynAPI.include('dynapi.event.*');
<?php
echo("swis_glyph = \"" . $swis_glyph . "\";\n");
echo("bLoad = \"" . $_REQUEST['build'] . "\";\n");
echo("uiVal = \"" . $ui . "\";\n");
echo("sgnVal = \"" . $sgn . "\";\n");
echo("iGLi = \"" . addslashes(displayIcon(17)) . "\";\n");
echo("iPRi = \"" . addslashes(displayIcon(18)) . "\";\n");
echo("iADi = \"" . addslashes(displayIcon(32)) . "\";\n");
echo("iCSi = \"" . addslashes(displayIcon(20)) . "\";\n");
echo("iDSi = \"" . addslashes(displayIcon(21)) . "\";\n");
echo("iCAi = \"" . addslashes(displayIcon(22)) . "\";\n");
echo("iVRi = \"" . addslashes(displayIcon(23)) . "\";\n");
echo("iMSi = \"" . addslashes(displayIcon(24)) . "\";\n");
echo("iFSi = \"" . addslashes(displayIcon(25)) . "\";\n");
echo("iEMi = \"" . addslashes(displayIcon(33)) . "\";\n");
echo("iRCCi = \"" . addslashes(displayIcon(27)) . "\";\n");
echo("iRCi = \"" . addslashes(displayIcon(28)) . "\";\n");
echo("iSNi = \"" . addslashes(displayIcon(29)) . "\";\n");
echo("iAMi = \"" . addslashes(displayIcon(34)) . "\";\n");
echo("iFMi = \"" . addslashes(displayIcon(35)) . "\";\n");
echo("iRMi = \"" . addslashes(displayIcon(36)) . "\";\n");
?>
</script>
<script language="Javascript" src="searchsymbol2.js">
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
/*@nonl*/
/*@-node:slevin.20070119161527.2:@thin W:/www/searchsymbol.php*/
/*@-leo*/
?>
