<?php
/**
 *	Mark Image with another Image.
 *	@package	ui
 *	@subpackage	image
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		16.12.2005
 *	@version		0.1
 */
/**
 *	Mark Image with another Image.
 *	@package	ui
 *	@subpackage	image
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		16.12.2005
 *	@version		0.1
 */
class Watermark
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
	protected function calculatePosition( $img )
	{
		$sx	= imagesx( $img );
		$sy	= imagesy( $img );
		
		switch( $this->positionH )
		{
			case 'left':
				$posX	= 0 + $this->marginX;
				break;
			case 'center':
				$posX	= ceil( $sx / 2 - $this->size[0] / 2 );
				break;
			case 'right':
				$posX	= $sx - $this->size[0] - $this->marginX;
				break;
		}
		switch( $this->positionV )
		{
			case 'top':
				$posY	= 0 + $this->marginY;
				break;
			case 'middle':
				$posY	= ceil( $sy / 2 - $this->size[1] / 2 );
				break;
			case 'bottom':
				$posY	= $sy - $this->size[1] - $this->marginY;
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
	public function markImage( $source, $target = false )
	{
		if( false === $target )
			$target = $source;
		
		if( $size = getimagesize( $source ) )
		{
	
			switch( $size[2] )
			{
				case 1:
					$img	= imagecreatefromgif( $source );
					$img	= $this->markImageSource( $img );
					imagegif( $img, $target );
					return true;
				case 2:
					$img	= imagecreatefromjpeg( $source );
					$img	= $this->markImageSource( $img );
					imagejpeg( $img, $target, $this->quality );
					return true;
				case 3:
					$img	= imagecreatefrompng( $source );
					$img	= $this->markImageSource( $img );
					imagepng( $img, $target );
					return true;
			}
		}
		else
			trigger_error( "Source File is not an supported Image", E_USER_WARNING );
		return false;
	}
	
	/**
	 *	Returns marked Image Source.
	 *	@access		protected
	 *	@param		resource		$img 		Image Resource
	 *	@return		resource
	 */
	protected function markImageSource( $img )
	{
		$position	= $this->calculatePosition( $img );
		imagecopymerge( $img, $this->stampSource, $position['x'], $position['y'], 0, 0, $this->size[0], $this->size[1], $this->alpha );
		return $img;
	}

	/**
	 *	Create Image Resource from Image File.
	 *	@access		protected
	 *	@return		resource
	 */
	protected function getStampSource()
	{
		switch( $this->size[2] )
		{
			case 1:
				$img	= imagecreatefromgif( $this->stamp );
				return $img;
			case 2:
				$img	= imagecreatefromjpeg( $this->stamp );
				return $img;
			case 3:
				$img	= imagecreatefrompng( $this->stamp );
				return $img;
		}
	}
	
	/**
	 *	Sets the Opacity of Stamp Image.
	 *	@access		public
	 *	@param		int		$alpha 		Opacity of Stamp Image
	 *	@return		void
	 */
	public function setAlpha( $alpha )
	{
		$this->alpha	= abs( (int)$alpha );
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
			trigger_error( "Horizontal Position '".$horizontal."' must be on of ('left', 'center', 'right').", E_USER_ERROR );
		if( in_array( $vertical, array( 'top', 'middle', 'bottom' ) ) )
			$this->positionV	= $vertical;
		else
			trigger_error( "Vertical Position '".$horizontal."' must be on of ('top', 'middle', 'bottom').", E_USER_ERROR );
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
		if( $size = getimagesize( $stamp ) )
		{
			$this->size			= $size;
			$this->stamp			= $stamp;
			$this->stampSource	= $this->getStampSource();
		}
		else
		{
			$this->size	= false;
			trigger_error( "Stamp File is not an supported Image", E_USER_WARNING );
		}
	}
}
?>