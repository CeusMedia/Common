<?php
/**
 *	Median Blur.
 *	@package		ui
 *	@subpackage		image
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.12.2005
 *	@version		0.1
 */
/**
 *	Median Blur.
 *	@package		ui
 *	@subpackage		image
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.12.2005
 *	@version		0.1
 */
class MedianBlur
{
	/*	@var	array	$_size		Sizes of Source Image */
	var $_size	= array();
	/*	@var	string	$_source		Source File Name of Source Image */
	var $_source;
	/*	@var	string	$_target		Target File Name of Target Image */
	var $_target;
	/*	@var	int		$_quality		Quality of Target Image */
	var $_quality;
	
	/**
	 *	Constructur.
	 *	@access		public
	 *	@param		string	$source 		File Name of Source Image
	 *	@param		string	$target 		File Name of Target Image
	 *	@param		int		$quality 		Quality of Target Image
	 *	@return		void
	 */
	public function __construct( $source, $target, $quality = 100 )
	{
		$this->setSource( $source );	
		$this->setTarget( $target );	
		$this->setQuality( $quality );	
	}

	/**
	 *	Sets the File Name of Source Image.
	 *	@access		public
	 *	@param		string	$source 		File Name of Source Image
	 *	@return		void
	 */
	function setSource( $source )
	{
		if( $size = getimagesize( $source ) )
		{
			$this->_size		= $size;
			$this->_source	= $source;
		}
		else
		{
			$this->_size	= array();
			trigger_error( "Source File is not an supported Image", E_USER_WARNING );
		}
	}

	/**
	 *	Sets the File Name of Target Image.
	 *	@access		public
	 *	@param		string	$target 		File Name of resulting Target Image
	 *	@return		void
	 */
	function setTarget( $target )
	{
		$this->_target	= $target;
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
	 *	Blurs Image.
	 *	@access		public
	 *	@return		bool
	 */
	function blur()
	{
		if( count( $this->_size ) )
		{
			$target	= imagecreatetruecolor( $this->_size[0], $this->_size[1] );
			switch( $this->_size[2] )
			{
				case 1:      //GIF
					$source	= imagecreatefromgif( $this->_source );
					$this->_blur( $source, $target );
					imagegif( $target, $this->_target );
					return true;
				case 2:      //JPEG
					$source	= imagecreatefromjpeg( $this->_source );
					$this->_blur( $source, $target );
					imagejpeg( $target, $this->_target, $this->_quality );
					return true;
				case 3:      //PNG
					$source	= imagecreatefrompng( $this->_source );
					$this->_blur( $source, $target );
					imagepng( $target, $this->_target );
					return true;
				default:
					trigger_error( "Image Type is unsupported.", E_USER_WARNING );
					return false;
			}
		}
		return false;
	}
	
	/**
	 *	Blurs Source Image with 3x3 Matrix to Target Image.
	 *	@access		private
	 *	@return		bool
	 */
	function _blur( $source, &$target )
	{
		$this->_cache	= array();
		for( $x=0; $x<$this->_size[0]; $x++ )
		{
			for( $y=0; $y<$this->_size[1]; $y++ )
			{
				if( $x > 0 && $y > 0 && $x < $this->_size[0]-1 && $y < $this->_size[1]-1 )
				{
					$color	= array();
					$x0y0	= $this->_getColor( $source, $x-1, $y-1 );
					$x0y1	= $this->_getColor( $source, $x-1, $y );
					$x0y2	= $this->_getColor( $source, $x-1, $y+1 );
					$x1y0	= $this->_getColor( $source, $x, $y-1 );
					$x1y1	= $this->_getColor( $source, $x, $y );
					$x1y2	= $this->_getColor( $source, $x, $y+1 );
					$x2y0	= $this->_getColor( $source, $x+1, $y-1 );
					$x2y1	= $this->_getColor( $source, $x+1, $y );
					$x2y2	= $this->_getColor( $source, $x+1, $y+1 );

					$color['red']	= $this->_medianblur( $x0y0['red'], $x0y1['red'], $x0y2['red'], $x1y0['red'], $x1y1['red'], $x1y2['red'], $x2y0['red'], $x2y1['red'], $x2y2['red'] );
					$color['green']	= $this->_medianblur( $x0y0['green'], $x0y1['green'], $x0y2['green'], $x1y0['green'], $x1y1['green'], $x1y2['green'], $x2y0['green'], $x2y1['green'], $x2y2['green'] );
					$color['blue']	= $this->_medianblur( $x0y0['blue'], $x0y1['blue'], $x0y2['blue'], $x1y0['blue'], $x1y1['blue'], $x1y2['blue'], $x2y0['blue'], $x2y1['blue'], $x2y2['blue'] );
				}
				else
					$color	= $this->_getColor( $source, $x, $y );
				$color	= imagecolorallocate( $target, $color['red'], $color['green'], $color['blue'] );
				imagesetpixel( $target, $x, $y, $color );
			}
		}
	}
	
	/**
	 *	Blurs Source Image with 3x3 Matrix to Target Image.
	 *	@access		private
	 *	@param		int		$x0y0		Color of Pixel x0y0
	 *	@param		int		$x0y1		Color of Pixel x0y0
	 *	@param		int		$x0y2		Color of Pixel x0y0
	 *	@param		int		$x1y0		Color of Pixel x0y0
	 *	@param		int		$x1y1		Color of Pixel x0y0
	 *	@param		int		$x1y2		Color of Pixel x0y0
	 *	@param		int		$x2y0		Color of Pixel x0y0
	 *	@param		int		$x2y1		Color of Pixel x0y0
	 *	@param		int		$x2y2		Color of Pixel x0y0
	 *	@return		bool
	 */
	function _medianblur( $x0y0, $x0y1, $x0y2, $x1y0, $x1y1, $x1y2, $x2y0, $x2y1, $x2y2 ) 
	{
		$value	= 1/6 * ( 0*$x0y0 + $x0y1 + 0*$x0y2 + $x1y0 + 2*$x1y1 + $x1y2 + 0*$x2y0 + $x2y1 + 0*$x2y2 );
		return $value;
	}

	/**
	 *	Returns Color of Pixel in Source Image.
	 *	@access		private
	 *	@param		Resource		$source		Source Image
	 *	@param		int			$x			X-Axis
	 *	@param		int			$y			Y-Axis
	 *	@return		int
	 */
	function _getColor( $source, $x, $y )
	{
		if( isset( $this->_cache[$x][$y] ) )
			return $this->_cache[$x][$y];
		else
		{
			$color	= imagecolorat( $source, $x, $y );
			$color	= imagecolorsforindex( $source, $color );
			$this->_cache[$x][$y]	= $color;
			return $color;
		}
	}
}
?>