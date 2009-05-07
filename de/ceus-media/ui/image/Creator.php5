<?php
/**
 *	@package		ui.image
 *	@version		0.2
 *	@todo			Code Doc
 */
class UI_Image_Creator
{
	const TYPE_PNG	= 0;
	const TYPE_JPEG	= 1;
	const TYPE_GIF	= 2;

	protected $height		= -1;
	protected $resource		= NULL;
	protected $type			= NULL;
	protected $width		= -1;

	public function create( $width, $height, $backgroundRed = 255, $backgroundGreen = 255, $backgroundBlue = 255, $alpha = 0 )
	{
		$this->resource	= imagecreatetruecolor( $width, $height );
		$this->width	= $width;
		$this->height	= $height;
		$backColor		= imagecolorallocatealpha( $this->resource, $backgroundRed, $backgroundGreen, $backgroundBlue, $alpha );
		imagefilledrectangle( $this->resource, 0, 0, $width - 1, $height - 1, $backColor );		
	}
	
	public function getExtension()
	{
		return $this->extension;	
	}
	
	public function getHeight()
	{
		return $this->height;
	}
	
	public function getResource()
	{
		return $this->resource;
	}
	
	public function getType()
	{
		return $this->type;	
	}
	
	public function getWidth()
	{
		return $this->width;
	}
	
	public function loadImage( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new InvalidArgumentException( 'Image File "'.$fileName.'" is not existing.' );
		$info		= pathinfo( $fileName );
		$extension	= strtolower( $info['extension'] );
		switch( $extension )
		{
			case 'png':
				$this->resource	= imagecreatefrompng( $fileName );
				$this->type	= self::TYPE_PNG;
				break;
			case 'jpe':
			case 'jpeg':
			case 'jpg':
				$this->resource	= imagecreatefromjpeg( $fileName );
				$this->type	= self::TYPE_JPEG;
				break;
			case 'gif':
				$this->resource	= imagecreatefromgif( $fileName );
				$this->type	= self::TYPE_GIF;
				break;
			default:
				throw new InvalidArgumentException( 'Image Type "'.$type.'" is not supported.' );
		}
		$this->extension	= $extension;
		$this->width		= imagesx( $this->resource );		
		$this->height		= imagesy( $this->resource );		
	}
}
?>