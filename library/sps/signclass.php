<?php
#@+leo-ver=4-thin
#@+node:slevin.20070119105433.16:@thin W:/www/SWML/library/sps/signclass.php
#@@first
/*******************************************************************************
* Software: SPS Sign Class                                                     *
* Version:  1.0                                                                *
* Date:     2006-08-35                                                         *
* Author:   Steve Slevinski                                                    *
* License:  SignPuddle Server License                                          *
*                                                                              *
* This software is only for use by licenced SignPuddle Servers                 *
*******************************************************************************/

require ('symbolclass.php');

if(!class_exists('SIGN'))
{
define('SIGN_VERSION','1.0');

#@<< class SIGN >>
#@+node:slevin.20070119105433.17:<< class SIGN >>
class SIGN
{
//Private properties
var $output;                    //image string output
var $im;                        //image identifier
var $width;                     //image width
var $height;                    //image height

var $minX;                      //min x position in sign
var $centerX;                   //center head x position
var $maxX;                      //max x position in sign

var $minY;                      //min y position in sign
var $centerY;                   //center head y position
var $maxY;                      //max y position in sign

var $symbols = array();         //array of symbols
var $xs = array();              //array of x positions
var $ys = array();              //array of y positions
var $colors = array();          //array of colors
var $size;                      //
var $background;
var $pixels;  //pixel border
var $bounding; // type of bounding box
//include colorize array
var $keyColor = array(
"01-01"=>"0000CC",
"01-02"=>"0000CC",
"01-03"=>"0000CC",
"01-04"=>"0000CC",
"01-05"=>"0000CC",
"01-06"=>"0000CC",
"01-07"=>"0000CC",
"01-08"=>"0000CC",
"01-09"=>"0000CC",
"01-10"=>"0000CC",
"02-01"=>"CC0000",
"02-02"=>"CC0000",
"02-03"=>"CC0000",
"02-04"=>"CC0000",
"02-05"=>"CC0000",
"02-06"=>"CC0000",
"02-07"=>"CC0000",
"02-08"=>"CC0000",
"02-09"=>"CC0000",
"02-10"=>"CC0000",
"03-01"=>"006600",
"03-02"=>"006600",
"03-03"=>"006600",
"03-04"=>"006600",
"03-05"=>"006600",
"04-01"=>"000000",
"04-02"=>"000000",
"05-01"=>"FF0099",
"06-01"=>"FF9900",
"07-01"=>"884411",
);
/*******************************************************************************
*                                                                              *
*                               Public methods                                 *
*                                                                              *
*******************************************************************************/
#@<< function SIGN >>
#@+node:slevin.20070119105433.18:<< function SIGN >>
function SIGN($build, $size=1,$background="",$pixels=0,$bounding="t")  
{
  //set size
  if ($size<=0) {$this->size=1;} else {$this->size=$size;}
  $this->background=$background;
  $this->pixels=$pixels;
  if ($bounding){
    $this->bounding=$bounding;
  } else {
    $this->bounding='t';
  } 
  //split build array
  $build = split(",",$build);
  //determine if build string is colored
  if (strlen($build[3])==6) {
    //colored
    $segments = 4;
  } else {
    //non-colored
    $segments = 3;
  }
  
  $cnt = count($build);
  $cnt = $cnt - ($cnt%$segments); 
  for ($i=0;$i<$cnt;$i++){
    $this->symbols[]=$build[$i];
    $i++;
    $this->xs[]=$build[$i];
    $i++;
    $this->ys[]=$build[$i]; 
    if ($segments==4) {
      $i++;
      $this->colors[]=$build[$i]; 
    } else {
      $this->colors[]='000000';  //black default 
    }
  }
}
#@-node:slevin.20070119105433.18:<< function SIGN >>
#@nl

#@<< function Colorize >>
#@+node:slevin.20070119105433.19:<< function Colorize >>
function Colorize()
{
  //assign color by group
  foreach ($this->symbols as $num => $sss) {
    $group = substr($sss,0,5);
    $color = $this->keyColor[$group];
    $this->colors[$num]=$color;
  }
}
#@-node:slevin.20070119105433.19:<< function Colorize >>
#@nl

#@<< function SetColor >>
#@+node:slevin.20070119105433.20:<< function SetColor >>
function SetColor($color,$match='')
{
  //determine length of match for testing
  $length = strlen($match);
  //assign color by match
  foreach ($this->symbols as $num => $sss) {
    $segment = substr($sss,0,$length);
    if ($segment == $match){
      $this->colors[$num]=$color;
    }
  }
}
#@-node:slevin.20070119105433.20:<< function SetColor >>
#@nl

#@<< function SetColorRightHand >>
#@+node:slevin.20070119105433.21:<< function SetColorRightHand >>
function SetColorRightHand($color)
{
  //assign color by match
  foreach ($this->symbols as $num => $sss) {
    //determine group
    $group = substr($sss,0,2);
    $rotation = substr($sss,16,2);
    if ($group == '01'){ // it's a hand!
      if ($rotation<9) { // it's a right hand
        $this->colors[$num]=$color;
      }
    }
  }
}
#@-node:slevin.20070119105433.21:<< function SetColorRightHand >>
#@nl

#@<< function SetColorLeftHand >>
#@+node:slevin.20070119105433.22:<< function SetColorLeftHand >>
function SetColorLeftHand($color)
{
  //assign color by match
  foreach ($this->symbols as $num => $sss) {
    //determine group
    $group = substr($sss,0,2);
    $rotation = substr($sss,16,2);
    if ($group == '01'){ // it's a hand!
      if ($rotation>8) { // it's a left hand
        $this->colors[$num]=$color;
      }
    }
  }
}
#@-node:slevin.20070119105433.22:<< function SetColorLeftHand >>
#@nl

#@<< function isPunctuation >>
#@+node:slevin.20070119105433.23:<< function isPunctuation >>
function isPunctuation()
{
  $bPunctuation=0;
  //test category and group
  if (count($this->symbols)==1){
    $group = substr($this->symbols[0],0,5);
    if ($group=="06-01") {
      $bPunctuation=1;
    }
  }
  return $bPunctuation;
}
#@-node:slevin.20070119105433.23:<< function isPunctuation >>
#@nl

#@<< function SetCenterX >>
#@+node:slevin.20070119105433.24:<< function SetCenterX >>
function SetCenterX($center=0)
{
  //determine how much to center x
  $adjust = $this->centerX - $center;
  $this->centerX -= $adjust;
  $this->minX -= $adjust;
  $this->maxX -= $adjust;

  foreach ($this->symbols as $num => $sss) {
    $this->xs[$num]-=$adjust;
  }
}
#@-node:slevin.20070119105433.24:<< function SetCenterX >>
#@nl

#@<< function Build >>
#@+node:slevin.20070119105433.25:<< function Build >>
function Build()
{
  //default min and max values
  $xMin=$this->xs[0];
  $xMax=$xMin+2;
  $yMin=$this->ys[0];
  $yMax=$yMin+2;
  //set symbols and get min and max for xy
  foreach ($this->symbols as $num => $sss) {
    $symbol = 'symbol' . $num;
    $$symbol = new SYMBOL($sss,$this->colors[$num]);
    $W= $$symbol->width;
    $H= $$symbol->height;
    $X= $this->xs[$num];
    $Y= $this->ys[$num];
    if ($xMin > $X) { $xMin=$X;}
    if ($yMin > $Y) { $yMin=$Y;}
    if ($xMax < ($X+$W)) { $xMax=$X+$W;}
    if ($yMax < ($Y+$H)) { $yMax=$Y+$H;}
    if ($$symbol->isHead()) {
     //head
        $headCount++;
        $centerX=$X+$W/2;
        $centerY=$Y+$H/2;
        if ($cxMin==0){//initial setup of min and max 
          $cxMin=$centerX;
          $cxMax=$centerX;
          $cyMin=$centerY;
          $cyMax=$centerY;
        }
        $cxMin=min($cxMin,$centerX);
        $cxMax=max($cxMax,$centerX);
        $cyMin=max($cyMin,$centerY);
        $cyMax=max($cyMax,$centerY);

      }
  }
  //set min and max XY
  $this->minX = $xMin;
  $this->maxX = $xMax;
  $this->minY = $yMin;
  $this->maxY = $yMax;
  
  //determin width and height
  $this->height = ($yMax - $yMin);
  $this->width = ($xMax - $xMin);
  
  //determine center x and y
  if ($headCount){
    $this->centerX = ($cxMin + $cxMax)/2; 
    $this->centerY = ($cyMin + $cyMax)/2; 
  } else {
    $this->centerX = ($xMin + $xMax)/2; 
    $this->centerY = ($yMin + $yMax)/2; 
  }
  
  //reset min and max for head center
  if ($headCount){ //only center if there is actually a head!!
    if ($this->bounding=="c" || $this->bounding=="h"){
      if (($this->centerX-$xMin) > ($xMax-$this->centerX)) {
        $xMax = $this->centerX + ($this->centerX - $xMin);
      } else {
        $xMin = $this->centerX - ($xMax - $this->centerX);
      }
    }

    if ($this->bounding=="c" || $this->bounding=="v"){
      if (($centerY-$yMin) > ($yMax-$centerY)) {
        $yMax = $this->centerY + ($this->centerY - $yMin);
      } else {
        $yMin = $this->centerY - ($yMax - $this->centerY);
      }
    }
  }
  
  //reset min and max for border
  $xMin = $xMin-$this->pixels;
  $yMin = $yMin-$this->pixels;
  $xMax = $xMax+$this->pixels;
  $yMax = $yMax+$this->pixels;

  //set up the base image
  $im = imagecreate($xMax-$xMin, $yMax-$yMin);
  if ($this->background){
    sscanf($this->background, "%2x%2x%2x", $backgroundR, $backgroundG, $backgroundB);
    $background_color = imagecolorallocate($im, $backgroundR, $backgroundG, $backgroundB);
  } else {
    $background_color = imagecolorallocate($im, 0, 0, 0);
    ImageColorTransparent($im, $background_color);
  }

  //set images and get min and max for xy
  foreach ($this->symbols as $num => $item) {
    $symbol = 'symbol' . $num;
    //add symbol
    $X = $this->xs[$num];
    $Y = $this->ys[$num];
    $W = $$symbol->width;
    $H = $$symbol->height;
    ImageCopy($im, $$symbol->im, $X-$xMin, $Y-$yMin, 0, 0, $W, $H); 
    $$symbol->Close();
  }
  $this->im = $im;

  if ($this->size<>1) {
    $width = $xMax-$xMin;
    $height = $yMax-$yMin;
    $W = $width*$this->size;
    $H = $height*$this->size;
    $this->height *= $this->size;
    $this->width *= $this->size;
    $this->centerX  *= $this->size; 
    $this->centerY  *= $this->size; 
    $this->minX  *= $this->size; 
    $this->maxX  *= $this->size; 
    $this->minY  *= $this->size; 
    $this->maxY  *= $this->size; 
    
    foreach ($this->symbols as $num => $sss) {
      $this->xs[$num]*=$this->size;
      $this->ys[$num]*=$this->size;
    }

  	$temp = imagecreatetruecolor($W, $H);
 
	  /* making the new image transparent */
 	  if ($this->background){
      sscanf($this->background, "%2x%2x%2x", $backgroundR, $backgroundG, $backgroundB);
      $background_color = imagecolorallocate($temp, $backgroundR, $backgroundG, $backgroundB);
    } else {
	    $background = imagecolorallocate($temp, 253, 253, 253);
	    ImageColorTransparent($temp, $background); // make the new temp image all transparent
    }
	
 	  imagealphablending($temp, false); // turn off the alpha blending to keep the alpha channel
    imagesavealpha ( $temp, true );
	  /* Resize the PNG file */
	  /* use imagecopyresized to gain some performance but loose some quality */
	  imagecopyresampled($temp, $this->im, 0, 0, 0, 0, $W, $H, $width, $height);
	  /* use imagecopyresampled if you concern more about the quality */
	  //imagecopyresampled($temp, $src, 0, 0, 0, 0, $w, $h, imagesx($src), imagesy($src));
    imagedestroy($this->im);
    $this->im = $temp;
//    ob_start();
//    imagepng($temp);
//    $output = ob_get_contents();
//    ob_end_clean();
//    $this->im = imagecreatefromstring($output);
  }


  ob_start();
  imagepng($im);
  $this->output = ob_get_contents();
  ob_end_clean();
}
#@-node:slevin.20070119105433.25:<< function Build >>
#@nl


#@<< function Close >>
#@+node:slevin.20070119105433.26:<< function Close >>
function Close()
{
  imagedestroy($this->im);
}
#@-node:slevin.20070119105433.26:<< function Close >>
#@nl

//End of class
}
#@-node:slevin.20070119105433.17:<< class SIGN >>
#@nl
//End of if exists
}
#@@last
#@-node:slevin.20070119105433.16:@thin W:/www/SWML/library/sps/signclass.php
#@-leo
?>
