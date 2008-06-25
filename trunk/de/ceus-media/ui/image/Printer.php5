<?php
/**
 *	Prints an Image Resource into a File or on Screen.
 *	@package		ui.image
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
/**
 *	Prints an Image Resource into a File or on Screen.
 *	@package		ui.image
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
class UI_Image_Printer
{
	const TYPE_PNG	= 0;
	const TYPE_JPEG	= 1;
	const TYPE_GIF	= 2;

	/**	@var		resource		$resource		Image Resource */
	protected $resource;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		resource		$resource		Image Resource
	 *	@return		void
	 */
	public function __construct( $resource )
	{
		if( !is_resource( $resource ) )
			throw new InvalidArgumentException( 'Given Image Resource is not a valid Resource.' );
		$this->resource	= $resource;
	}
	
	/**
	 *	Print Image on Screen.
	 *	@access		public
	 *	@param		int			$type			Image Type
	 *	@param		int			$quality		JPEG Quality (1-100)
	 *	@param		bool		$showHeader		Flag: set Image MIME Type Header
	 *	@return		void
	 */
	public function show( $type = self::TYPE_PNG, $quality = 100, $sendHeader = TRUE )
	{
		$this->showImage( $this->resource, $type, $quality, $sendHeader );
	}
	
	/**
	 *	Writes Image to File.
	 *	@access		public
	 *	@param		string		$fleName		Name of target Image File
	 *	@param		int			$type			Image Type
	 *	@param		int			$quality		JPEG Quality (1-100)
	 *	@return		void
	 */
	public function save( $fileName, $type = self::TYPE_PNG, $quality = 100 )
	{
		$this->saveImage( $fileName, $this->resource, $type, $quality );
	}
	
	/**
	 *	Prints an Image to Screen statically.
	 *	@access		public
	 *	@param		resource	$resource		Image Resource
	 *	@param		int			$type			Image Type
	 *	@param		int			$quality		JPEG Quality (1-100)
	 *	@param		bool		$showHeader		Flag: set Image MIME Type Header
	 *	@return		void
	 */
	public static function showImage( $resource, $type = self::TYPE_PNG, $quality = 100, $sendHeader = TRUE )
	{
		switch( $type )
		{
			case 0:
				if( $sendHeader )
					header( "Content-type: image/png" );
				ImagePNG( $resource );
				break;
			case 1:
				if( $sendHeader )
					header( "Content-type: image/jpeg" );
				ImageJPEG( $resource, "", $quality );
				break;
			case 2:
				if( $sendHeader )
					header( "Content-type: image/gif" );
				ImageGIF( $resource );
				break;
			default:
				throw new InvalidArgumentException( 'Invalid Image Type' );
		}
	}
	
	/**
	 *	Saves an Image to File statically.
	 *	@access		public
	 *	@param		string		$fleName		Name of target Image File
	 *	@param		resource	$resource		Image Resource
	 *	@param		int			$type			Image Type
	 *	@param		int			$quality		JPEG Quality (1-100)
	 *	@return		void
	 */
	public static function saveImage( $fileName, $resource, $type = self::TYPE_PNG, $quality = 100 )
	{
		switch( $type )
		{
			case 0:
				ImagePNG( $resource, $fileName );
				break;
			case 1:
				ImageJPEG( $resource, $fileName, $quality );
				break;
			case 2:
				ImageGIF( $resource, $fileName );
				break;
			default:
				throw new InvalidArgumentException( 'Invalid Image Type' );
		}
	}
}
?>