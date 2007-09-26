<?php
/**
 *	Inverting Images.
 *	@package		ui
 *	@subpackage		image
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.12.2005
 *	@version		0.1
 */
/**
 *	Inverting Images.
 *	@package		ui
 *	@subpackage		image
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.12.2005
 *	@version		0.1
 */
class Inverting
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
	 *	Invertes Source Image.
	 *	@access		public
	 *	@return		bool
	 */
	function invert()
	{
		if( count( $this->_size ) )
		{
			$target	= imagecreatetruecolor( $this->_size[0], $this->_size[1] );
			switch( $this->_size[2] )
			{
				case 1:      //GIF
					$source	= imagecreatefromgif( $this->_source );
					$this->_invert( $source, $target );
					imagegif( $target, $this->_target );
					return true;
				case 2:      //JPEG
					$source	= imagecreatefromjpeg( $this->_source );
					$this->_invert( $source, $target );
					imagejpeg( $target, $this->_target, $this->_quality );
					return true;
				case 3:      //PNG
					$source	= imagecreatefrompng( $this->_source );
					$this->_invert( $source, $target );
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
	 *	Invertes all Pixels of Source Image.
	 *	@access		public
	 *	@param		int		$source 		File Name of resulting Source Image
	 *	@param		int		$target		File Name of resulting Target Image
	 *	@return		bool
	 */
	function _invert( $source, &$target )
	{
		for( $x=0; $x<$this->_size[0]; $x++ )
		{
			for( $y=0; $y<$this->_size[1]; $y++ )
			{
				$color	= imagecolorat( $source, $x, $y );
				$color	= imagecolorsforindex( $source, $color );
				$color['red']	= 255 - $color['red'];
				$color['green']	= 255 - $color['green'];
				$color['blue']	= 255 - $color['blue'];
				$color	= imagecolorallocate( $target, $color['red'], $color['green'], $color['blue'] );
				imagesetpixel( $target, $x, $y, $color );
			}
		}
	}
}
?>