<?php
/**
 *	Basic Image Creation.
 *	@package		ui
 *	@subpackage		image
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Basic Image Creation.
 *	@package		ui
 *	@subpackage		image
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Image
{
	protected $image;
	protected $type	= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Resource		Image Handler
	 *	@return		void
	 */
	public function __construct( $image = false )
	{
		if( $image )
			$this->setImage( $image );
	}
	
	public function allocateColor ($r, $g, $b)
	{
		return imagecolorallocate( $this->image, $r, $g, $b);
	}

	public function create( $x, $y )
	{
		$this->setImage ( imagecreate( $x, $y ) );
	}

	public function drawLine( $x0, $y0, $x1, $y1, $color )
	{
		imageline( $this->image, $x0, $y0, $x1, $y1, $color );
	}
	
	public function drawPixel( $x, $y, $color )
	{
		imagesetpixel( $this->image, $x, $y, $color );		
	}
	
	public function drawString( $x, $y, $text, $size, $color )
	{
		imagestring( $this->image, $size, $x, $y, $text, $color );
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
	
	
	public function show( $quality = 100 )
	{
		switch( $this->type )
		{
			case 0:
				header( "Content-type: image/png" );
				ImagePNG( $this->image );
				break;
			case 1:
				header( "Content-type: image/gif" );
				ImageGIF( $this->image);
				break;
			case 2:
				header( "Content-type: image/jpeg");
				ImageJPEG( $this->image, "", $quality );
				break;
		}
		die;
	}
	
	public function showError( $message, $x = 200, $y = 40 )
	{
		header( "Content-type: image/png" );
		$image = new Image();
		$image->create( $x, $y );
		$back_color	= $image->allocateColor( 255, 255, 255 );
		$text_color	= $image->allocateColor( 233, 14, 91 );
		$image->drawString( 5, 5, $message, 2, $text_color );
		$image->show();
	}
}
?>