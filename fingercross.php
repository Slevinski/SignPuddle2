<?php
$rSL = 1;
set_time_limit(60);
include 'styleA.php';
$subHead='Fingerspeller';
  //main page
//include 'styleB.php';
//include 'header.php'; 
$build = $_REQUEST['build'];
$x = $_REQUEST['x'];
$x = $x?intval($x):0;
$y = $_REQUEST['y'];
$y = $y?intval($y):0;

echo '<form action="' . $SELF_PHP . '" method="get">';
echo '<h3>';
echo "Center signs for fingerspelling";
//echo getSignTitle(76,"ui");
echo '</h3>';
echo '<table border>';
echo '<tr>';
echo '<th>FSW string</th>';
echo '<th>Horizontal offset</th>';
echo '<th>Vertical offset</th>';
echo '<th>Baseline</th>';
echo '<td rowspan=2>';
echo '<button name="cmd" value="baseline" type="submit">Redraw</button>';
echo '</td>';
echo '</tr><tr>';
echo '<td> ';
echo '<input type="text" NAME="build" value="' . $build . '"></input>';
echo '</td>';
echo '<td> ';
echo '<input onchange="this.form.submit()" type="range" name="x" value="' . $x . '" min="-15" max="15"></input>';
echo '</td>';
echo '<td> ';
echo '<input onchange="this.form.submit()" type="range" name="y" value="' . $y . '" min="-15" max="15"></input>';
echo '</td>';
echo '<td> ';
echo '<input type="checkbox" NAME="baseline"';
$baseline = $_REQUEST['baseline'];
if ($baseline) echo ' checked';
echo '></input>';
echo '</td></tr>';
echo '</table>';
echo '</form>';
$fsw = query_displace($build,$x,$y);
echo "<p>" . $fsw;
if ($baseline)  $fsw = query_displace($fsw,0,-7);
$ksw = crosshairs(fsw2ksw($fsw),0);
echo '<p><img src="' . $swis_glyphogram . '?ksw=' . $ksw. '&size=10"></p>';
//include 'footer.php';

?>
