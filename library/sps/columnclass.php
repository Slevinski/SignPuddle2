<?php
/*******************************************************************************
* Software: SPS Column Class                                                   *
* Version:  1.0                                                                *
* Date:     2006-08-26                                                         *
* Author:   Steve Slevinski                                                    *
* License:  SignPuddle Server License                                          *
*                                                                              *
* This software is only for use by licenced SignPuddle Servers                 *
*******************************************************************************/

require ('signclass.php');

if(!class_exists('COLUMN'))
{
define('COLUMN_VERSION','1.0');

class COLUMN
{
//Private properties
var $spacing;                //spacing between items
                                //sign punctuation uses half spacing

//spacing muultiplier array for items classes
//s for SIGN
//p for punctuation SIGN
//i for IMAGE
//t for TEXT
var $keySpacing = array(
'ss'=>4,
'sp'=>2,
'ps'=>5
);
var $keySpacingDefault=5;         //default spacing multiplier

var $width=0;                   //coluumn width
var $height;                    //column height
var $minX;                      //min x position in signs
var $laneOffset;                //cennter offset for lanes


var $items = array();           //array of lanes itemms
var $ys = array();              //array of y positions for items

var $yp=0;                      //current y position in column
var $heightRemain;              //remaining column height

/*******************************************************************************
*                                                                              *
*                               Public methods                                 *
*                                                                              *
*******************************************************************************/
function COLUMN($height, $spacing=30, $laneOffset=75, $background="FFFFFF")
{
  //set column height
  $this->height = $height;
  $this->heightRemain = $height;
  $this->spacing = $spacing;
  $this->laneOffset = $laneOffset;
  $this->background = $background;
}

function ResetHeight($height)
{

  $diff = $height - $this->height;
  $this->height += $diff;
  $this->heightRemain += $diff;
}

function AddSpace($space=0)
{
  $this->yp += $space;
  $this->heightRemain -= $space;
}

function AddSpacing($multiplier=1)
{
  $this->AddSpace($this->spacing * $multiplier);
}

function SpacingFor($key)
{
  //determine last entry if it exists
  $multiplier=0;
  $count = count($this->items);
  if ($count){
    //determine previous item type
    $last = $this->items[$count-1];
    //if ($last instanceof SIGN) {
      if ($last->isPunctuation()) {
        $key = 'p' . $key;
      } else {
        $key = 's' . $key;
      }
    //}
    //if ($last instanceof IMAGE) {
    //  $key .= 'i';
    //}
    //if ($last instanceof TEXT) {
    //  $key .= 't';
    //}
    if (array_key_exists($key, $this->keySpacing)){
      $multiplier = $this->keySpacing[$key];
    } else {
      $multiplier = $this->keySpacingDefault;
    }
    //$this->AddSpacing($multiplier);
  }
  return $multiplier;
}

function AddSign(&$sign, $lane=0)
{

  //adjust center x for lane
  $sign->SetCenterX($lane * $this->laneOffset);
  //determin current sign
  if ($sign->isPunctuation()) {
    $multiplier = $this->SpacingFor('p');
    $bAddOK = 1;
  } else {
    //check for enough room
    $multiplier = $this->SpacingFor('s');
    if ($this->heightRemain >= ($sign->height + ($multiplier * $this->spacing))) {
      $bAddOK = 1;
    } else {
      $bAddOK = 0;
    }
  }
  
  //add sign if OK
  if ($bAddOK) {
    $this->AddSpacing($multiplier);
    $this->items[] = $sign;
    $this->ys[] = $this->yp;
    $this->AddSpace($sign->height);
  }
  return $bAddOK;
}

function SaveImage($location,$padding=0,$width=0)
{
  if ($width==0){
    $widthpad=$padding;
  } else {
    $widthpad = (($width - $this->width) /2); 
  }
//  $im = imagecreate($this->width + $widthpad*2, $this->height + $padding *2);
  $im = imagecreatetruecolor($this->width + $widthpad*2, $this->height + $padding *2);
  if ($this->background){
    //this works for size 1 and others.  Leave it alone!
    sscanf($this->background, "%2x%2x%2x", $backgroundR, $backgroundG, $backgroundB);
    $background_color = imagecolorallocate($im, $backgroundR, $backgroundG, $backgroundB);
    imagefill($im, 0, 0, $background_color);
    //try it?
    //imagealphablending($im, false); // turn off the alpha blending to keep the alpha channel
    imagesavealpha ( $im, true );
  } else {
    
    imagesavealpha($im, true);

    $trans_colour = imagecolorallocatealpha($im, 0, 0, 0, 127);
    imagefill($im, 0, 0, $trans_colour);
//    imagealphablending($im, true); // turn off the alpha blending to keep the alpha channel
//    imagesavealpha ( $im, true );

    //works for size=1
/*    $background_color = imagecolorallocate($im, 255, 255, 255);
    imagefill($im, 0, 0, $background_color);
    ImageColorTransparent($im, $background_color);
*/    //end works for size=1
  }


  $signs = $this->items;
  $minX = $this->minX;
  foreach ($signs as $num => $sign) {      
    $xPos = $sign->minX - $minX + $xp;
    ImageCopy($im, $sign->im, $xPos + $widthpad, $this->ys[$num] + $padding, 0, 0, $sign->width, $sign->height); 
  }
  imagepng($im,$location. '.png');

}

function SetWidth()
{
  $xMin = 0;
  $xMax = 0;
  foreach ($this->items as $num => $item){
    $xMin = min($xMin, $item->minX);
    $xMax = max($xMax, $item->maxX);
  }
  $this->width = $xMax - $xMin;
  $this->minX = $xMin;
}

function AddSigns(&$pdf, $xp){
  $signs = $this->items;
  $minX = $this->minX;
  foreach ($signs as $num => $sign) {      
    $xPos = $sign->minX - $minX + $xp;
    $pdf->MemImage($sign->output, $xPos, $this->ys[$num] + $pdf->tMargin, $sign->width, $sign->height);
//    $pdf->GDImage($sign->im, $xPos, $this->ys[$num] + $pdf->tMargin, $sign->width, $sign->height);
  }
}

function Close()
{
  foreach ($this->items as $item){
    $item->Close();
  }
}

//End of class
}
//End of if exists
}
?>
