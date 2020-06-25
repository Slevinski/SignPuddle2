<?php
#@+leo-ver=4-thin
#@+node:slevin.20070119105433.10:@thin W:/www/SWML/library/sps/symbolclass.php
#@@first
/*******************************************************************************
* Software: SPS Symbol Class                                                   *
* Version:  1.0                                                                *
* Date:     2006-08-25                                                         *
* Author:   Steve Slevinski                                                    *
* License:  SignPuddle Server License                                          *
*                                                                              *
* This software is only for use by licenced SignPuddle Servers                 *
*******************************************************************************/

if(!class_exists('SYMBOL'))
{
define('SYMBOL_VERSION','1.0');

#@<< class SYMBOL >>
#@+node:slevin.20070119105433.11:<< class SYMBOL >>
class SYMBOL
{
//Private properties
var $id;                        //sss id number
var $im;                        //image identifier
var $width;                     //image width
var $height;                    //image height
var $color;                     //symbol color
var $iswa_dir = 'iswa/pack/';        //hardcoded iswa packed directory


var $category;                  //
var $group;                     //
var $symbol;                    //
var $variation;                 //
var $fill;                      //
var $rotation;                  //

var $size;                      //

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
"04-01"=>"000000",
"04-02"=>"000000",
"05-01"=>"FF0099",
"06-01"=>"FF9900",
"07-01"=>"999999",
);

/*******************************************************************************
*                                                                              *
*                               Public methods                                 *
*                                                                              *
*******************************************************************************/
#@<< function SYMBOL >>
#@+node:slevin.20070119105433.12:<< function SYMBOL >>
function SYMBOL($sss, $color='', $size=1)  //size is either 1 or .5
{
  if ($color==-1){
    $group = substr($sss,0,5);
    $color = $this->keyColor[$group];
  }
  //initialize symbol properties
  $this->id = $sss;
  $this->category = substr($sss,0,2);
  $this->group = substr($sss,3,2);
  $this->symbol = substr($sss,6,3);
  $this->fvariation = substr($sss,10,2);
  $this->fill = substr($sss,13,2);
  $this->rotation = substr($sss,16,2);
  if ($size<=0) {$this->size =1;} else {$this->size=$size;}

  //initialize image identifier
  //determine file name
  $filename = substr($sss,0,12);
  //if ($size==.5) {$special='s';} else {
  $special='';
  //}
  $filename = $this->iswa_dir . $filename . $special;
  $data = file($filename);
  //determine the line number of the image data
  $line = (($this->fill-1) * 16) + $this->rotation -1;
  $image_data=$data{$line};
  //decode image data
  $image_data = base64_decode($image_data);
  $im_src = imagecreatefromstring($image_data);
  $width = imagesx($im_src);
  $newwidth=$width/2;
  $height = imagesy($im_src);
  $newheight=$height/2;
  //now color if needed
  if ($color) {
    $this->color = $color;
    sscanf($color, "%2x%2x%2x", $r, $g, $b);
    $im_tint = ImageCreate(imagesx($im_src),imagesy($im_src));
    $trans = ImageColorAllocate($im_tint, 253, 253, 253);
    for ($c = 0; $c < 256; $c++) {
      ImageColorAllocate($im_tint, max($r,$c), max($g,$c), max($b,$c));
    }
    ImageCopyMerge($im_tint,$im_src,0,0,0,0, imagesx($im_src), imagesy($im_src), 100);
    ImageDestroy($im_src);
  
    //make transparent
    imagecolortransparent($im_tint,$trans);
    //assign colored image
    $this->im = $im_tint;

  } else { 
    // assign source image
    $this->im = $im_src;
  }

  if ($this->size<>1) {
    $w = $width*$this->size;
    $h = $height*$this->size;
		$temp = imagecreatetruecolor($w, $h);
 
		/* making the new image transparent */
		$background = imagecolorallocate($temp, 253, 253, 253);
		ImageColorTransparent($temp, $background); // make the new temp image all transparent
		imagealphablending($temp, false); // turn off the alpha blending to keep the alpha channel
    imagesavealpha ( $temp, true );
		/* Resize the PNG file */
		/* use imagecopyresized to gain some performance but loose some quality */
		imagecopyresampled($temp, $this->im, 0, 0, 0, 0, $w, $h, $width, $height);
		/* use imagecopyresampled if you concern more about the quality */
		//imagecopyresampled($temp, $src, 0, 0, 0, 0, $w, $h, imagesx($src), imagesy($src));
		$this->im = $temp;
  }

 
  //determine width, height
  $this->width = ImageSX($this->im);
  $this->height = ImageSY($this->im);
}
#@-node:slevin.20070119105433.12:<< function SYMBOL >>
#@nl

#@<< function isHead >>
#@+node:slevin.20070119105433.13:<< function isHead >>
function isHead()
{
  //test category
  if ($this->category=="03"  or $this->category=="04") {
     $bHead=1;
   } else {
     $bHead=0;
   }
  
   return $bHead;
}
#@-node:slevin.20070119105433.13:<< function isHead >>
#@nl

#@<< function isPunctuation >>
#@+node:slevin.20070119105433.14:<< function isPunctuation >>
function isPunctuation()
{
  //test category
  if ($this->category=="08"  and $this->group=="04") {
     $bPunctuation=1;
   } else {
     $bPunctuation=0;
   }
  
   return $bPunctuation;
}
#@-node:slevin.20070119105433.14:<< function isPunctuation >>
#@nl

#@<< function Close >>
#@+node:slevin.20070119105433.15:<< function Close >>
function Close()
{
  imagedestroy($this->im);
}
#@-node:slevin.20070119105433.15:<< function Close >>
#@nl

//End of class
}
#@-node:slevin.20070119105433.11:<< class SYMBOL >>
#@nl
//End of if exists
}
#@@last
#@-node:slevin.20070119105433.10:@thin W:/www/SWML/library/sps/symbolclass.php
#@-leo
?>
