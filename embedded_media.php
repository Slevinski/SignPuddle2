<?php
/*
#@+leo-ver=4-thin
#@+node:ses.20070128183518:@thin W:/www/embedded_media.php
#@@first
#@@first
#@delims /* */ 
$rSL = 1;
include 'styleA.php'; ?>
<html>
<head>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<title>SignPuddle</title>

</head>
<body>

<?php
include 'header.php';
// get post variable
$sid = urldecode($_REQUEST['sid']);
$ext = $_REQUEST['ext'];

  $filename = $sgnwww . '/' . $sid . '.' . $ext;
  $display = "";  
  //display media based on switch
if (in_array($ext,$vidGeneralExt)){
    echo '<img src="library/icons/video.jpg">';
  $display.='      <!-- begin embedded WindowsMedia file... -->';
  $display.='      <table border="0" cellpadding="0" align="left">';
  $display.='      <tr><td>';
  $display.= '<EMBED SRC="' . $filename . '">';
  $display.='      </td></tr>';
  $display.='        <tr><td align="left">';
  $display.='        <a href="' . $filename . '" style="font-size: 85%;" target="_blank">Launch in external player</a>';
  $display.='        </td></tr>';
  $display.='      </table>'; 

} else if (in_array($ext,$vidFlashlExt)) {
    echo '<img src="library/icons/videoFlash.gif">';
  $display.='      <!-- begin embedded Flash file... -->';
  $display.='      <table border="0" cellpadding="0" align="left">';
  $display.='        <tr><td>';
  $display.='        <OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"';
  $display.='        codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"';
  if ($video_height and $video_width) { $display.=' width="' . $video_width . '" height="' . $video_height . '" ';}
  $display.='        >';
  $display.='        <param name="movie" value="' . $filename . '">';
  $display.='        <param name="quality" value="high">';
  $display.='        <param name="bgcolor" value="#FFFFFF">';
  $display.='        <param name="loop" value="false">';
  $display.='        <EMBED src="' . $filename . '" quality="high" bgcolor="#FFFFFF" ';
  $display.='         loop="false" type="application/x-shockwave-flash"';
  if ($video_height and $video_width) { $display.=' width="' . $video_width . '" height="' . $video_height . '" ';}
  $display.='        pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">';
  $display.='        </EMBED>';
  $display.='        </OBJECT>';
  $display.='        </td></tr>';
  $display.='        <!-- ...end embedded Flash file -->';
  $display.='       </table>';    
} else if (in_array($ext,$vidQuickTimeExt)) {
      echo '<img src="library/icons/videoQuickTime.gif">';
  $display.='      <!-- begin embedded QuickTime file... -->';
  $display.='      <table border="0" cellpadding="0" align="left">';
//  $display.='        <!-- begin video window... -->';
  $display.='        <tr><td>';
  $display.='        <OBJECT classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" ';
  if ($video_height and $video_width) { $display.=' width="' . $video_width . '" height="' . (15 + $video_height) . '" ';}
  $display.='         codebase="http://www.apple.com/qtactivex/qtplugin.cab">';
  $display.='        <param name="src" value="' . $filename . '">';
  $display.='        <param name="autoplay" value="true">';
  $display.='        <param name="controller" value="true">';
  $display.='        <param name="loop" value="false">';
  $display.='        <EMBED src="' . $filename . '"  autoplay="true" ';
  if ($video_height and $video_width) { $display.=' width="' . $video_width . '" height="' . (15 + $video_height) . '" ';}
  $display.='        controller="true" loop="false" pluginspage="http://www.apple.com/quicktime/download/">';
  $display.='        </EMBED>';
  $display.='        </OBJECT>';
  $display.='        </td></tr>';
//  $display.='        <!-- ...end embedded QuickTime file -->';
  $display.='        </table>';
} else if (in_array($ext,$vidRealMediaExt)) {
      echo '<img src="library/icons/videoRealMedia.gif">';
  $display.='      <!-- begin embedded RealMedia file... -->';
  $display.='      <table border="0" cellpadding="0" align="left">';
  $display.='        <!-- begin video window... -->';
  $display.='        <tr><td>';
  $display.='        <OBJECT id="rvocx" classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA"';
  if ($video_height and $video_width) { $display.=' width="' . $video_width . '" height="' . $video_height . '" ';}
  $display.='        >';
  $display.='        <param name="src" value="' . $filename .'">';
  $display.='        <param name="autostart" value="true">';
  $display.='        <param name="controls" value="imagewindow">';
  $display.='        <param name="console" value="video">';
  $display.='        <param name="loop" value="false">';
  $display.='        <EMBED src="' . $filename . '"  ';
  if ($video_height and $video_width) { $display.=' width="' . $video_width . '" height="' . $video_height . '" ';}
  $display.='        loop="false" type="audio/x-pn-realaudio-plugin" controls="imagewindow" console="video" autostart="true">';
  $display.='        </EMBED>';
  $display.='        </OBJECT>';
  $display.='        </td></tr>';
  $display.='        <!-- ...end video window -->';
  $display.='          <!-- begin control panel... -->';
  $display.='          <tr><td>';
  $display.='          <OBJECT id="rvocx" classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA"';
  if ($video_height and $video_width) { $display.=' width="' . $video_width . '" height="30" ';}
  $display.='          >';
  $display.='          <param name="src" value="' . $filename . '">';
  $display.='          <param name="autostart" value="true">';
  $display.='          <param name="controls" value="ControlPanel">';
  $display.='          <param name="console" value="video">';
  $display.='          <EMBED src="' . $filename . '" ';
  if ($video_height and $video_width) { $display.=' width="' . $video_width . '" height="30"';}
  $display.='          controls="ControlPanel" type="audio/x-pn-realaudio-plugin" console="video" autostart="true">';
  $display.='          </EMBED>';
  $display.='          </OBJECT>';
  $display.='          </td></tr>';
  $display.='          <!-- ...end control panel -->';
  $display.='          <!-- ...end embedded RealMedia file -->';
  $display.='        <!-- begin link to launch external media player... -->';
  $display.='        <tr><td align="left">';
  $display.='        <a href="' . $filename . '" style="font-size: 85%;" target="_blank">Launch in external player</a>';
  $display.='        <!-- ...end link to launch external media player... -->';
  $display.='        </td></tr>';
  $display.='      </table>';
} else if (in_array($ext,$vidWindowsMediaExt)) {
      echo '<img src="library/icons/videoWindowsMedia.gif">';
  $display.='      <!-- begin embedded WindowsMedia file... -->';
  $display.='      <table border="0" cellpadding="0" align="left">';
  $display.='      <tr><td>';
  $display.='      <OBJECT id="mediaPlayer"  ';
  if ($video_height and $video_width) { $display.=' width="' . $video_width . '" height="' . (45 + $video_height) . '" ';}
  $display.='      classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" ';
  $display.='      codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701"';
  $display.='      standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject">';
  $display.='      <param name="fileName" value="' . $filename . '">';
  $display.='      <param name="animationatStart" value="true">';
  $display.='      <param name="transparentatStart" value="true">';
  $display.='      <param name="autoStart" value="true">';
  $display.='      <param name="showControls" value="true">';
  $display.='      <param name="loop" value="false">';
  $display.='      <EMBED type="application/x-mplayer2"';
  $display.='        pluginspage="http://microsoft.com/windows/mediaplayer/en/download/"';
  $display.='        id="mediaPlayer" name="mediaPlayer" displaysize="4" autosize="-1" ';
  $display.='        bgcolor="darkblue" showcontrols="true" showtracker="-1" ';
  $display.='        showdisplay="0" showstatusbar="-1" videoborder3d="-1" ';
  if ($video_height and $video_width) { $display.=' width="' . $video_width . '" height="' . (45 + $video_height) . '" ';}
  $display.='        src="' . $filename . '" autostart="true" designtimesp="5311" loop="false">';
  $display.='      </EMBED>';
  $display.='      </OBJECT>';
  $display.='      </td></tr>';
  $display.='      <!-- ...end embedded WindowsMedia file -->';
  $display.='    <!-- begin link to launch external media player... -->';
  $display.='        <tr><td align="left">';
  $display.='        <a href="' . $filename . '" style="font-size: 85%;" target="_blank">Launch in external player</a>';
  $display.='        <!-- ...end link to launch external media player... -->';
  $display.='        </td></tr>';
  $display.='      </table>'; 
} else {
      echo '<img alt="' .$ext . '" src="library/icons/video' . $ext . '.gif">';
  $display.='      <!-- begin embedded WindowsMedia file... -->';
  $display.='      <table border="0" cellpadding="0" align="left">';
  $display.='      <tr><td>';
  $display.= '<EMBED SRC="' . $filename . '">';
  $display.='      </td></tr>';
  $display.='        <tr><td align="left">';
  $display.='        <a href="' . $filename . '" style="font-size: 85%;" target="_blank">Launch in external player</a>';
  $display.='        </td></tr>';
  $display.='      </table>'; 
}

echo $display;

echo "<hr>";

displaySWFull($sid);

include 'footer.php'; 
/*@@last*/

/*@-node:ses.20070128183518:@thin W:/www/embedded_media.php*/
/*@-leo*/
?>
