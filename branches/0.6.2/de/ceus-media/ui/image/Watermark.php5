<?php
import( 'de.ceus-media.ui.image.Creator' );
import( 'de.ceus-media.ui.image.Printer' );
/**
 *	Mark Image with another Image.
 *	@package		ui.image
 *	@extends		UI_Image_Creator
 *	@extends		UI_Image_Printer
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.12.2005
 *	@version		0.5
 */
/**
 *	Mark Image with another Image.
 *	@package		ui.image
 *	@extends		UI_Image_Creator
 *	@extends		UI_Image_Printer
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.12.2005
 *	@version		0.5
 */
class UI_Image_Watermark
{
	/**	@var		array		$size			Array of Information of Stamp Image */
	protected $size;
	/**	@var		string		$stamp			File Name of Stamp Image */
	protected $stamp;
	/**	@var		array		$stampSource	Image Source Stamp Image */
	protected $stampSource;
	/**	@var		int			$quality		Quality of resulting JPEG Image */
	protected $quality;
	/**	@var		string		$positionH		Horizontal Position of Stamp Image (left, center, right) */
	protected $positionH		= 'right';
	/**	@var		string		$positionV		Vertical Position of Stamp Image (top, middle, bottom) */
	protected $positionV		= 'bottom';
	/**	@var		int			$marginX		Horizontal Margin of Stamp Image */
	protected $marginX			= 0;
	/**	@var		int			$marginY		Vertical Margin of Stamp Image */
	protected $marginY			= 0;
	/**	@var		int			$alpha			Opacity of Stamp Image */
	protected $alpha;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$stamp 			File Name of Stamp Image
	 *	@param		int			$alpha 			Opacity of Stamp Image
	 *	@param		int			$quality 		Quality of resulting Image
	 *	@return		void
	 */
	public function __construct( $stamp, $alpha = 100, $quality = 100 )
	{
		$this->setStamp( $stamp );
		$this->setAlpha( $alpha );
		$this->setQuality( $quality );
	}
	
	/**
	 *	Return Array with Coords of Stamp Image within a given Image.
	 *	@access		protected
	 *	@param		resource		$img 		Image Resource
	 *	@return		array
	 */
	protected function calculatePosition( $image )
	{
		switch( $this->positionH )
		{
			case 'left':
				$posX	= 0 + $this->marginX;
				break;
			case 'center':
				$posX	= ceil( $image->getWidth() / 2 - $this->stamp->getWidth() / 2 );
				break;
			case 'right':
				$posX	= $image->getWidth() - $this->stamp->getWidth() - $this->marginX;
				break;
		}
		switch( $this->positionV )
		{
			case 'top':
				$posY	= 0 + $this->marginY;
				break;
			case 'middle':
				$posY	= ceil( $image->getHeight() / 2 - $this->stamp->getHeight() / 2 );
				break;
			case 'bottom':
				$posY	= $image->getHeight() - $this->stamp->getHeight() - $this->marginY;
				break;
		}
		$position	= array(
			'x'	=> $posX,
			'y'	=> $posY
		);
		return $position;
	}
	
	/**
	 *	Marks a Image with Stamp Image.
	 *	@access		public
	 *	@param		string		$source 		File Name of Source Image
	 *	@param		string		$target 		Target Name of Target Image
	 *	@return		bool
	 */
	public function markImage( $source, $target = NULL )
	{
		if( !$target )
			$target = $source;
		
		$creator	= new UI_Image_Creator();
		$creator->loadImage( $source );
		$image		= $creator->getResource();
		$type		= $creator->getType();

		$position		= $this->calculatePosition( $creator );
		$stampHeight	= $this->stamp->getHeight();
		$stampWidth		= $this->stamp->getWidth();
		$stampResource	= $this->stamp->getResource();
		imagecopymerge( $image, $stampResource, $position['x'], $position['y'], 0, 0, $stampWidth, $stampHeight, $this->alpha );

		$printer	= new UI_Image_Printer( $image );
		$printer->save( $target, $type );
	}

	/**
	 *	Sets the Opacity of Stamp Image.
	 *	@access		public
	 *	@param		int		$alpha 		Opacity of Stamp Image
	 *	@return		void
	 */
	public function setAlpha( $alpha )
	{
		$this->alpha	= abs( (int) $alpha );
	}
	
	/**
	 *	Sets the Marig of Stamp Image.
	 *	@access		public
	 *	@param		int			$x 				Horizontal Margin of Stamp Image
	 *	@param		int			$y 				Vertical Margin of Stamp Image
	 *	@return		void
	 */
	public function setMargin( $x, $y )
	{
		$this->marginX	= abs( (int)$x );
		$this->marginY	= abs( (int)$y );
	}
	
	/**
	 *	Sets the Position of Stamp Image.
	 *	@access		public
	 *	@param		string		$horizontal 	Horizontal Position of Stamp Image (left,center,right)
	 *	@param		string		$vertical 		Vertical Position of Stamp Image (top,middle,bottom)
	 *	@return		void
	 */
	public function setPosition( $horizontal, $vertical )
	{
		if( in_array( $horizontal, array( 'left', 'center', 'right' ) ) )
			$this->positionH	= $horizontal;
		else
			throw new InvalidArgumentException( 'Horizontal Position "'.$horizontal.'" must be on of (left, center, right).' );
		if( in_array( $vertical, array( 'top', 'middle', 'bottom' ) ) )
			$this->positionV	= $vertical;
		else
			throw new InvalidArgumentException( 'Vertical Position "'.$horizontal.'" must be on of (top, middle, bottom).' );
	}
	
	/**
	 *	Sets the Quality of resulting Image.
	 *	@access		public
	 *	@param		int			$quality 		Quality of resulting Image
	 *	@return		void
	 */
	public function setQuality( $quality )
	{
		$this->quality	= $quality;
	}

	/**
	 *	Sets the Stamp Image.
	 *	@access		public
	 *	@param		string		$stamp			File Name of Stamp Image
	 *	@return		void
	 */
	public function setStamp( $stamp )
	{
		$this->stamp	= new UI_Image_Creator();
		$this->stamp->loadImage( $stamp );
	}
}
?>