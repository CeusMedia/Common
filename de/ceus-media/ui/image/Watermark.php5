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
	/**	@var	array		$size			Array of Information of Stamp Image */
	var $_size;
	/**	@var	string		$_stamp			File Name of Stamp Image */
	var $_stamp;
	/**	@var	array		$_stamp_source	Image Source Stamp Image */
	var $_stamp_source;
	/**	@var	int			$_quality			Quality of resulting JPEG Image */
	var $_quality;
	/**	@var	string		$_position_h		Horizontal Position of Stamp Image (left, center, right) */
	var $_position_h	= 'right';
	/**	@var	string		$_position_v		Vertical Position of Stamp Image (top, middle, bottom) */
	var $_position_v	= 'bottom';
	/**	@var	int			$_margin_x		Horizontal Margin of Stamp Image */
	var $_margin_x	= 0;
	/**	@var	int			$_margin_y		Vertical Margin of Stamp Image */
	var $_margin_y	= 0;
	/**	@var	int			$_alpha			Opacity of Stamp Image */
	var $_alpha;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$stamp 		File Name of Stamp Image
	 *	@param		int		$alpha 		Opacity of Stamp Image
	 *	@param		int		$quality 		Quality of resulting Image
	 *	@return		void
	 */
	public function __construct( $stamp, $alpha = 100, $quality = 100 )
	{
		$this->setStamp( $stamp );
		$this->setAlpha( $alpha );
		$this->setQuality( $quality );
	}
	
	/**
	 *	Sets the Position of Stamp Image.
	 *	@access		public
	 *	@param		string	$horizontal 	Horizontal Position of Stamp Image (left,center,right)
	 *	@param		string	$vertical 		Vertical Position of Stamp Image (top,middle,bottom)
	 *	@return		void
	 */
	function setPosition( $horizontal, $vertical )
	{
		if( in_array( $horizontal, array( 'left', 'center', 'right' ) ) )
			$this->_position_h	= $horizontal;
		else
			trigger_error( "Horizontal Position '".$horizontal."' must be on of ('left', 'center', 'right').", E_USER_ERROR );
		if( in_array( $vertical, array( 'top', 'middle', 'bottom' ) ) )
			$this->_position_v	= $vertical;
		else
			trigger_error( "Vertical Position '".$horizontal."' must be on of ('top', 'middle', 'bottom').", E_USER_ERROR );
	}
	
	/**
	 *	Sets the Opacity of Stamp Image.
	 *	@access		public
	 *	@param		int		$alpha 		Opacity of Stamp Image
	 *	@return		void
	 */
	function setAlpha( $alpha )
	{
		$this->_alpha	= abs( (int)$alpha );
	}
	
	/**
	 *	Sets the Marig of Stamp Image.
	 *	@access		public
	 *	@param		int		$x 			Horizontal Margin of Stamp Image
	 *	@param		int		$y 			Vertical Margin of Stamp Image
	 *	@return		void
	 */
	function setMargin( $x, $y )
	{
		$this->_margin_x	= abs( (int)$x );
		$this->_margin_y	= abs( (int)$y );
	}
	
	/**
	 *	Sets the Quality of resulting Image.
	 *	@access		public
	 *	@param		int		$quality 		Quality of resulting Image
	 *	@return		void
	 */
	function setQuality( $quality )
	{
		$this->_quality	= $quality;
	}

	/**
	 *	Sets the Stamp Image.
	 *	@access		public
	 *	@param		string	$stamp		File Name of Stamp Image
	 *	@return		void
	 */
	function setStamp( $stamp )
	{
		if( $size = getimagesize( $stamp ) )
		{
			$this->_size			= $size;
			$this->_stamp			= $stamp;
			$this->_stamp_source	= $this->_getStampSource();
		}
		else
		{
			$this->_size	= false;
			trigger_error( "Stamp File is not an supported Image", E_USER_WARNING );
		}
	}
	
	/**
	 *	Marks a Image with Stamp Image.
	 *	@access		public
	 *	@param		string	$source 		File Name of Source Image
	 *	@param		string	$target 		Target Name of Target Image
	 *	@return		bool
	 */
	function markImage( $source, $target = false )
	{
		if( false === $target )
			$target = $source;
		
		if( $size = getimagesize( $source ) )
		{
	
			switch( $size[2] )
			{
				case 1:
					$img	= imagecreatefromgif( $source );
					$img	= $this->_markImageSource( $img );
					imagegif( $img, $target );
					return true;
				case 2:
					$img	= imagecreatefromjpeg( $source );
					$img	= $this->_markImageSource( $img );
					imagejpeg( $img, $target, $this->_quality );
					return true;
				case 3:
					$img	= imagecreatefrompng( $source );
					$img	= $this->_markImageSource( $img );
					imagepng( $img, $target );
					return true;
			}
		}
		else
			trigger_error( "Source File is not an supported Image", E_USER_WARNING );
		return false;
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Create Image Resource from Image File.
	 *	@access		private
	 *	@return		resource
	 */
	function _getStampSource()
	{
		switch( $this->_size[2] )
		{
			case 1:
				$img	= imagecreatefromgif( $this->_stamp );
				return $img;
			case 2:
				$img	= imagecreatefromjpeg( $this->_stamp );
				return $img;
			case 3:
				$img	= imagecreatefrompng( $this->_stamp );
				return $img;
		}
	}
	
	/**
	 *	Return Array with Coords of Stamp Image within a given Image.
	 *	@access		private
	 *	@param		resource		$img 		Image Resource
	 *	@return		array
	 */
	function _calculatePosition( $img )
	{
		$sx	= imagesx( $img );
		$sy	= imagesy( $img );
		
		switch( $this->_position_h )
		{
			case 'left':
				$pos_x	= 0 + $this->_margin_x;
				break;
			case 'center':
				$pos_x	= ceil( $sx / 2 - $this->_size[0] / 2 );
				break;
			case 'right':
				$pos_x	= $sx - $this->_size[0] - $this->_margin_x;
				break;
		}
		switch( $this->_position_v )
		{
			case 'top':
				$pos_y	= 0 + $this->_margin_y;
				break;
			case 'middle':
				$pos_y	= ceil( $sy / 2 - $this->_size[1] / 2 );
				break;
			case 'bottom':
				$pos_y	= $sy - $this->_size[1] - $this->_margin_y;
				break;
		}
		$position	= array(
			'x'	=> $pos_x,
			'y'	=> $pos_y
			);
		return $position;
	}
	
	/**
	 *	Returns marked Image Source.
	 *	@access		private
	 *	@param		resource		$img 		Image Resource
	 *	@return		resource
	 */
	function _markImageSource( $img )
	{
		$position	= $this->_calculatePosition( $img );
		imagecopymerge( $img, $this->_stamp_source, $position['x'], $position['y'], 0, 0, $this->_size[0], $this->_size[1], $this->_alpha );
		return $img;
	}
}
?>