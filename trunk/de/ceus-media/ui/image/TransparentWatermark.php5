<?php
/**
 *	Put watermark in image with transparent and randomize effect
 *
 *	Last change: 2004-04-16
 *
 *	@package		ui
 *	@subpackage		image
 *	@author			Lionel Micault <lionel.micault@laposte.net>
 *	@version		1.01
 */
/**
 *	Put watermark in image with transparent and randomize effect
 *
 *	Last change: 2004-04-16
 *
 *	@package		ui
 *	@subpackage		image
 *	@author			Lionel Micault <lionel.micault@laposte.net>
 *	@version		1.01
 *	@todo			check Integration
 *	@todo			create TestCases
 *	@todo			Code Documentation
 */
// position constants
define ("transparentWatermarkOnTop", -10);
define ("transparentWatermarkOnMiddle", 0);
define ("transparentWatermarkOnBottom", 10);
define ("transparentWatermarkOnLeft", -10);
define ("transparentWatermarkOnCenter", 0);
define ("transparentWatermarkOnRight", 10);

class UI_Image_TransparentWatermark  {
	var $stampImage=0;
	var $stampWidth;
	var $stampHeight;
	var $stampPositionX= transparentWatermarkOnRight;
	var $stampPositionY= transparentWatermarkOnBottom;
	
	var $errorMsg="";
	
	/**
	* Constructor
	*
	* @param string $stampFile  filename of stamp image
	* @return boolean
	* @access public
	* @uses setStamp()
	*/
	public function __construct( $stampFile="") {
		return( $this->setStamp( $stampFile));
	}
	
	/**
	* mark an image file and  display/save it
	*
	* @param int $imageFile  image file (JPEG or PNG format)
	* @param int $resultImageFile new image file (same format)
	* @return boolean
	* @access public
	* @uses readImage()
	* @uses markImage()
	* @uses writeImage()
	* @uses readImage()
	* @uses errorMsg
	*/
	public function markImageFile ( $imageFile, $resultImageFile="") {
		if (!$this->stampImage) {
			$this->errorMsg="Stamp image is not set.";
			return(false);
		}

		$imageinfos = @getimagesize($imageFile);
		$type   = $imageinfos[2];
		
		$image=$this->readImage($imageFile, $type);
		if (!$image) {
			$this->errorMsg="Error on loading '$imageFile', image must be a valid PNG or JPEG file.";
			return(false);
		}
		
		$this->markImage ( $image);
		
		if ($resultImageFile!="") {
			$this->writeImage( $image, $resultImageFile, $type);
		}
		else {
			$this->displayImage( $image, $type);
		}
		return( true);
		
	}
	
	/**
	* mark an image
	*
	* @param int $imageResource resource of image
	* @return boolean
	* @access public
	* @uses stampWidth
	* @uses stampHeight
	* @uses stampImage
	* @uses stampPositionX
	* @uses stampPositionY
	*/
	public function markImage ( $imageResource) {
		if (!$this->stampImage) {
			$this->errorMsg="Stamp image is not set.";
			return(false);
		}
		$imageWidth  = imagesx( $imageResource);
		$imageHeight = imagesy( $imageResource);

		//set position of logo
		switch ($this->stampPositionX) {
			case transparentWatermarkOnLeft: 
			$leftStamp=0;
			break;
			case transparentWatermarkOnCenter:
			$leftStamp=($imageWidth - $this->stampWidth)/2;
			break;
			case transparentWatermarkOnRight:
			$leftStamp=$imageWidth - $this->stampWidth;
			break;
			default :
			$leftStamp=0;
		}
		switch ($this->stampPositionY) {
			case transparentWatermarkOnTop:
			$topStamp=0;
			break;
			case transparentWatermarkOnMiddle:
			$topStamp=($imageHeight - $this->stampHeight)/2;
			break;
			case transparentWatermarkOnBottom:
			$topStamp=$imageHeight - $this->stampHeight;
			break;
			default:
			$topStamp=0;
		}
		
		// for each pixel of stamp
		for ($x=0; $x<$this->stampWidth; $x++) {
			if (($x+$leftStamp<0)||($x+$leftStamp>=$imageWidth)) continue;
			for ($y=0; $y<$this->stampHeight; $y++) {
				if (($y+$topStamp<0)||($y+$topStamp>=$imageHeight)) continue;
				
				// search RGB values of stamp image pixel
				$indexStamp=ImageColorAt($this->stampImage, $x, $y);
				$rgbStamp=imagecolorsforindex ( $this->stampImage, $indexStamp);

				
				// search RGB values of image pixel
				$indexImage=ImageColorAt( $imageResource, $x+$leftStamp, $y+$topStamp);
				$rgbImage=imagecolorsforindex ( $imageResource, $indexImage);

				$randomizer=0;
				
				// compute new values of colors pixel
				$r=max( min($rgbImage["red"]+$rgbStamp["red"]-0x80, 0xFF), 0x00);
				$g=max( min($rgbImage["green"]+$rgbStamp["green"]-0x80, 0xFF), 0x00);
				$b=max( min($rgbImage["blue"]+$rgbStamp["blue"]-0x80, 0xFF), 0x00);
				
				// change  image pixel
				imagesetpixel ( $imageResource, $x+$leftStamp, $y+$topStamp, ($r<<16)+($g<<8)+$b);
			}
		}
	}
	
	/**
	* set stamp position on image
	*
	* @param int $Xposition x position
	* @param int $Yposition y position
	* @return void
	* @access public
	* $this->stampPositionX
	* $this->stampPositionY
	* @uses errorMsg
	*/
	public function setStampPosition ( $Xposition, $Yposition) {
		// set X position
		switch ($Xposition) {
			case transparentWatermarkOnLeft: 
			case transparentWatermarkOnCenter:
			case transparentWatermarkOnRight:
			$this->stampPositionX=$Xposition;
			break;
		}
		// set Y position
		switch ($Yposition) {
			case transparentWatermarkOnTop:
			case transparentWatermarkOnMiddle:
			case transparentWatermarkOnBottom:
			$this->stampPositionY=$Yposition;
			break;
		}
	}
	
	/**
	* set stamp image for watermak
	*
	* @param string $stampFile  image file (JPEG or PNG)
	* @return boolean
	* @access public
	* @uses readImage()
	* @uses stampImage
	* @uses stampWidth
	* @uses stampHeight
	* @uses errorMsg
	*/
	public function setStamp( $stampFile) {
		$imageinfos = @getimagesize($stampFile);
		$width  = $imageinfos[0];
		$height = $imageinfos[1];
		$type   = $imageinfos[2];
		
		if ($this->stampImage) imagedestroy( $this->stampImage);
		
		$this->stampImage=$this->readImage($stampFile, $type);
		
		if (!$this->stampImage) {
			$this->errorMsg="Error on loading '$stampFile', stamp image must be a valid PNG or JPEG file.";
			return(false);
		}
		else {
			$this->stampWidth=$width;
			$this->stampHeight=$height;
			return(true);
		}
	}


	
	
	/**
	* retrieve last error message
	*
	* @return string
	* @access public
	* @uses errorMsg
	*/
	public function getLastError() {
		return($this->errorMsg);
	}
	
	/**
	* read image from file 
	*
	* @param string $file  image file (JPEG or PNG)
	* @param int $type  file type (2:JPEG or 3:PNG)
	* @return resource
	* @access protected
	* @uses errorMsg
	*/
	public function readImage( $file, $type) {
		switch ($type) {
			case 2:	//JPEG
			return(ImageCreateFromJPEG($file));
			break;
			
			case 3:	//PNG
			return(ImageCreateFromPNG($file));
			break;
			
			default:
			$this->errorMsg="File format not supported.";
			return(false);
		}
	}
	/**
	* write image to file 
	*
	* @param resource $image  image 
	* @param string $file  image file (JPEG or PNG)
	* @param int $type  file type (2:JPEG or 3:PNG)
	* @return void
	* @access protected
	* @uses errorMsg
	*/
	public function writeImage( $image, $file, $type) {
		switch ($type) {
			case 2:	//JPEG
			Imagejpeg( $image, $file);
			break;
			
			case 3:	//PNG
			Imagepng( $image, $file);
			break;
			
			default:
			$this->errorMsg="File format not supported.";
		}
	}
	/**
	* send image to stdout
	*
	* @param resource $image  image 
	* @param int $type  image type (2:JPEG or 3:PNG)
	* @return void
	* @access protected
	* @uses errorMsg
	*/
	public function displayImage( $image, $type) {
		switch ($type) {
			case 2:	//JPEG
			header("Content-Type: image/jpeg");
			Imagejpeg( $image);
			break;
			
			case 3:	//PNG
			header("Content-Type: image/png");
			Imagepng( $image);
			break;
			
			default:
			$this->errorMsg="File format not supported.";
		}
	}
}
?>