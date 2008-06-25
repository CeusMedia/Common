<?php
import( 'de.ceus-media.ui.image.Printer' );
/**
 *	Basic Image Creation.
 *	@package		ui.image
 *	@uses			UI_Image_Printer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Basic Image Creation.
 *	@package		ui.image
 *	@uses			UI_Image_Printer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class UI_Image_Drawer
{
	protected $image;
	protected $type	= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Resource		Image Resource, can be created with UI_Image_Creator
	 *	@return		void
	 */
	public function __construct( $image )
	{
		$this->setImage( $image );
	}
	
	public function getColor( $red, $green, $blue, $alpha = 0 )
	{
		
		return imagecolorallocatealpha( $this->image, $red, $green, $blue, $alpha );
	}

	public function drawBorder( $color, $width = 1 )
	{
		for( $i = 0; $i < $width; $i++ )
			$this->drawRectangle( 0 + $i, 0 + $i, imagesx( $this->image ) - $i - 1, imagesy( $this->image ) - $i - 1, $color );
	}
	
	public function drawRectangle( $x0, $y0, $x1, $y1, $color )
	{
		return imagerectangle( $this->image, $x0, $y0, $x1, $y1, $color );
	}

	public function drawLine( $x0, $y0, $x1, $y1, $color )
	{
		return imageline( $this->image, $x0, $y0, $x1, $y1, $color );
	}
	
	public function drawPixel( $x, $y, $color )
	{
		return imagesetpixel( $this->image, $x, $y, $color );		
	}
	
	public function drawString( $x, $y, $text, $size, $color )
	{
		return imagestring( $this->image, $size, $x, $y, $text, $color );
	}
	
/*	public function isSet()
	{
		return isset( $this->image );
	}
*/	
	/**
	 *	Sets Image Handler.
	 *	@access		public
	 *	@param		Resource		Image Handler
	 *	@return		void
	 */
	public function setImage( $image )
	{
		$this->image = $image;
	}
	
	public function getImage()
	{
		return $this->image;
	}
	
	public function show( $quality = 100 )
	{
		UI_Image_Printer::showImage( $this->image, $this->type, $quality );
		die;
	}
}
?>