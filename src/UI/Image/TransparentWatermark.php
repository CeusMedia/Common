<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	Put watermark in image with transparent and randomize effect
 *
 *	Last change: 2004-04-16
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Lionel Micault <lionel.micault@laposte.net>
 */

namespace CeusMedia\Common\UI\Image;

use GdImage;

// position constants
define ("transparentWatermarkOnTop", -10);
define ("transparentWatermarkOnMiddle", 0);
define ("transparentWatermarkOnBottom", 10);
define ("transparentWatermarkOnLeft", -10);
define ("transparentWatermarkOnCenter", 0);
define ("transparentWatermarkOnRight", 10);

/**
 *	Put watermark in image with transparent and randomize effect
 *
 *	Last change: 2004-04-16
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Lionel Micault <lionel.micault@laposte.net>
 *	@todo			check Integration
 *	@todo			create TestCases
 *	@todo			Code Documentation
 */
class TransparentWatermark
{
	protected ?GdImage $stampImage		= NULL;

	protected int $stampWidth			= 0;

	protected int $stampHeight			= 0;

	protected int $stampPositionX		= transparentWatermarkOnRight;

	protected int $stampPositionY		= transparentWatermarkOnBottom;

	protected ?string $errorMsg			= NULL;

	/**
	 *	Constructor
	 *
	 *	@access		public
	 *	@param		string		$stampFile		Filename of stamp image
	 *	@return		void
	 */
	public function __construct( string $stampFile = '' )
	{
		$this->setStamp( $stampFile );
	}

	/**
	 *	Send image to stdout.
	 *
	 *	@access		protected
	 *	@param		GdImage		$image		image
	 *	@param		int			$type		image type (2:JPEG or 3:PNG)
	 *	@return		void
	 */
	public function displayImage( GdImage $image, int $type ): void
	{
		switch( $type ){
			case IMAGETYPE_JPEG:
				header( 'Content-Type: image/jpeg' );
				imagejpeg( $image );
				break;
			case IMAGETYPE_PNG:
				header( 'Content-Type: image/png' );
				imagepng( $image );
				break;
			default:
				$this->errorMsg = 'File format not supported.';
		}
	}

	/**
	 *	Retrieve last error message.
	 *
	 *	@access public
	 *	@return string
	 */
	public function getLastError(): ?string
	{
		return $this->errorMsg;
	}

	/**
	 *	Mark an image file and display/save it.
	 *
	 *	@access		public
	 *	@param		string		$imageFile			image file (JPEG or PNG format)
	 *	@param		string		$resultImageFile	new image file (same format)
	 *	@return		boolean
	 */
	public function markImageFile( string $imageFile, string $resultImageFile = '' ): bool
    {
		if( !$this->stampImage ){
			$this->errorMsg = 'Stamp image is not set.';
			return( FALSE );
		}

		$imageInfos	= @getimagesize( $imageFile );
		$type		= $imageInfos[2];
		$image		= $this->readImage( $imageFile, $type );
		if( !$image ){
			$this->errorMsg = 'Error on loading "'.$imageFile.'", image must be a valid PNG or JPEG file.';
			return( FALSE );
		}

		$this->markImage( $image );

		if( $resultImageFile != ''){
			$this->writeImage( $image, $resultImageFile, $type );
		}
		else{
			$this->displayImage( $image, $type );
		}
		return TRUE;
	}

	/**
	 *	Mark an image.
	 *
	 *	@access		public
	 *	@param		GdImage			$imageResource		resource of image
	 *	@return		boolean
	 */
	public function markImage( GdImage $imageResource ): bool
    {
		if( !$this->stampImage ){
			$this->errorMsg = 'Stamp image is not set.';
			return FALSE;
		}
		$imageWidth  = imagesx( $imageResource );
		$imageHeight = imagesy( $imageResource );

		//set position of logo
		switch( $this->stampPositionX ){
			case transparentWatermarkOnLeft:
				$leftStamp = 0;
				break;
			case transparentWatermarkOnCenter:
				$leftStamp = ( $imageWidth - $this->stampWidth ) / 2;
				break;
			case transparentWatermarkOnRight:
				$leftStamp = $imageWidth - $this->stampWidth;
				break;
			default :
				$leftStamp = 0;
		}
		switch( $this->stampPositionY ){
			case transparentWatermarkOnTop:
				$topStamp = 0;
				break;
			case transparentWatermarkOnMiddle:
				$topStamp = ( $imageHeight - $this->stampHeight ) / 2;
				break;
			case transparentWatermarkOnBottom:
				$topStamp = $imageHeight - $this->stampHeight;
				break;
			default:
				$topStamp = 0;
		}

		// for each pixel of stamp
		for( $x=0; $x<$this->stampWidth; $x++ ){
			if( ( $x+$leftStamp < 0 ) || ( $x+$leftStamp >= $imageWidth ) )
			 	continue;
			for( $y=0; $y<$this->stampHeight; $y++ ){
				if( ( $y+$topStamp < 0) || ( $y+$topStamp >= $imageHeight ) )
					continue;

				// search RGB values of stamp image pixel
				$indexStamp	= imagecolorat( $this->stampImage, $x, $y );
				$rgbStamp	= imagecolorsforindex( $this->stampImage, $indexStamp );


				// search RGB values of image pixel
				$indexImage	= imagecolorat( $imageResource, $x+$leftStamp, $y+$topStamp );
				$rgbImage=imagecolorsforindex( $imageResource, $indexImage );

				$randomizer = 0;

				// compute new values of colors pixel
				$r	= max( min( $rgbImage["red"] + $rgbStamp["red"]-0x80, 0xFF), 0x00 );
				$g	= max( min( $rgbImage["green"] + $rgbStamp["green"]-0x80, 0xFF), 0x00 );
				$b	= max( min( $rgbImage["blue"] + $rgbStamp["blue"]-0x80, 0xFF), 0x00 );

				// change  image pixel
				imagesetpixel( $imageResource, $x+$leftStamp, $y+$topStamp, ($r<<16)+($g<<8)+$b );
			}
		}
        return TRUE;
	}

	/**
	 *	Read image from file.
	 *
	 *	@access		protected
	 *	@param		string		$file		image file (JPEG or PNG)
	 *	@param		int			$type		file type (2:JPEG or 3:PNG)
	 *	@return		GdImage|NULL
	 */
	public function readImage( string $file, int $type ): ?GdImage
    {
		switch( $type ){
			case IMAGETYPE_JPEG:
				return ImageCreateFromJPEG( $file );
			case IMAGETYPE_PNG:
				return ImageCreateFromPNG( $file );
			default:
				$this->errorMsg = 'File format not supported.';
				return NULL;
		}
	}

	/**
	 *	Set stamp image for watermark.
	 *
	 *	@access		public
	 *	@param		string		$stampFile		image file (JPEG or PNG)
	 *	@return		boolean
	 */
	public function setStamp( string $stampFile ): bool
    {
		$imageInfos	= @getimagesize( $stampFile );
		$width		= $imageInfos[0];
		$height		= $imageInfos[1];
		$type		= $imageInfos[2];

		if( $this->stampImage )
			imagedestroy( $this->stampImage );

		$this->stampImage = $this->readImage( $stampFile, $type );

		if( !$this->stampImage ){
			$this->errorMsg = 'Error on loading "'.$stampFile.'", stamp image must be a valid PNG or JPEG file.';
			return FALSE;
		}
		else{
			$this->stampWidth	= $width;
			$this->stampHeight	= $height;
			return TRUE;
		}
	}

	/**
	 *	Set stamp position on image.
	 *
	 *	@access		public
	 *	@param		int			$Xposition		x position
	 *	@param		int			$Yposition		y position
	 *	@return		void
	 */
	public function setStampPosition( int $Xposition, int $Yposition )
    {
		// set X position
		switch( $Xposition ){
			case transparentWatermarkOnLeft:
			case transparentWatermarkOnCenter:
			case transparentWatermarkOnRight:
				$this->stampPositionX	= $Xposition;
				break;
		}
		// set Y position
		switch( $Yposition ){
			case transparentWatermarkOnTop:
			case transparentWatermarkOnMiddle:
			case transparentWatermarkOnBottom:
				$this->stampPositionY	= $Yposition;
				break;
		}
	}

	/**
	 *	Write image to file.
	 *
	 *	@access		protected
	 *	@param		GdImage		$image		image
	 *	@param		string		$file		image file (JPEG or PNG)
	 *	@param		int			$type		file type (2:JPEG or 3:PNG)
	 *	@return     void
	 */
	public function writeImage( GdImage $image, string $file, int $type ): void
    {
		switch( $type ){
			case IMAGETYPE_JPEG:
				Imagejpeg( $image, $file );
				break;
			case IMAGETYPE_PNG:
				Imagepng( $image, $file);
				break;
			default:
				$this->errorMsg = 'File format not supported.';
		}
	}
}
