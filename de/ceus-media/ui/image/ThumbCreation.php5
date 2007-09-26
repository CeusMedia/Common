<?php
/**
 *	Resizing Images.
 *	@package		ui
 *	@subpackage		image
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.12.2005
 *	@version		0.1
 */
/**
 *	Resizing Images.
 *	@package		ui
 *	@subpackage		image
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.12.2005
 *	@version		0.1
 */
class ThumbCreation
{
	/**	@var	array		$size			Sizes of Source Image */
	private  $size	= array();
	/**	@var	string		$source			Source File Name of Source Image */
	private  $source;
	/**	@var	string		$target			Target File Name of Target Image */
	private  $target;
	/**	@var	int			$quality		Quality of Target Image */
	private  $quality;
	
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
	public function setSource( $source )
	{
		if( file_exists( $source ) && $size = getimagesize( $source ) )
		{
			$this->size		= $size;
			$this->source	= $source;
		}
		else
		{
			$this->size	= array();
			trigger_error( "Source File is not an supported Image", E_USER_WARNING );
		}
	}

	/**
	 *	Sets the File Name of Target Image.
	 *	@access		public
	 *	@param		string	$target 		File Name of resulting Target Image
	 *	@return		void
	 */
	public function setTarget( $target )
	{
		$this->target	= $target;
	}
	
	/**
	 *	Sets the Quality of resulting Image.
	 *	@access		public
	 *	@param		int		$quality 	Quality of resulting Image
	 *	@return		void
	 */
	public function setQuality( $quality )
	{
		$this->quality	= $quality;
	}

	/**
	 *	Resizes Image to a given Size.
	 *	@access		public
	 *	@param		int		$width 		Width of Target Image
	 *	@param		int		$height 		Height of Target Image
	 *	@return		bool
	 */
	public function thumbize( $width, $height )
	{
		if( count( $this->size ) )
		{
			$thumb	= imagecreatetruecolor( $width, $height );
			if( function_exists( 'imageantialias' ) )
				imageantialias( $thumb, TRUE );

			switch( $this->size[2] )
			{
				case 1:      //GIF
					$source	= imagecreatefromgif( $this->source );
					imagecopyresampled( $thumb, $source, 0, 0, 0, 0, $width, $height, $this->size[0], $this->size[1] );
					imagegif( $thumb, $this->target );
					return true;
				case 2:      //JPEG
					$source	= imagecreatefromjpeg( $this->source );
					imagecopyresampled( $thumb, $source, 0, 0, 0, 0, $width, $height, $this->size[0], $this->size[1] );
					imagejpeg( $thumb, $this->target, $this->quality );
					return true;
				case 3:      //PNG
					$source	= imagecreatefrompng( $this->source );
					imagecopyresampled( $thumb, $source, 0, 0, 0, 0, $width, $height, $this->size[0], $this->size[1] );
					imagepng( $thumb, $this->target );
					return true;
				default:
					trigger_error( "Image Type is unsupported.", E_USER_WARNING );
					return false;
			}
		}
		return false;
	}
		
	/**
	 *	Resizes Image within a given Limit.
	 *	@access		public
	 *	@param		int		$width 		Largest Width of Target Image
	 *	@param		int		$height 		Largest Height of Target Image
	 *	@return		bool
	 */
	public function thumbizeByLimit( $width, $height )
	{
		if( count( $this->size ) )
		{
			$ratio_s	= $this->size[0] / $this->size[1];
			$ratio_t	= $width / $height;
			if( ( $ratio_s / $ratio_t ) > 1 )
				$height	= ceil( $width / $ratio_s );
			else
				$width	= ceil( $height * $ratio_s );
			return $this->thumbize( $width, $height );
		}
		return false;
	}
}
?>