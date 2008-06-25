<?php
class UI_Image_Creator
{
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
	
	public function loadImage( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new InvalidArgumentException( 'Image File "'.$fileName.'" is not existing.' );
		$info	= pathinfo( $fileName );
		$type	= strtolower( $info['extension'] );
		switch( $type )
		{
			case 'png':
				$this->resource	= imagecreatefrompng( $fileName );
				break;
			case 'jpe':
			case 'jpeg':
			case 'jpg':
				$this->resource	= imagecreatefromjpeg( $fileName );
				break;
			case 'gif':
				$this->resource	= imagecreatefromgif( $fileName );
				break;
			default:
				throw new InvalidArgumentException( 'Image Type "'.$type.'" is not supported.' );
		}
		$this->type		= $type;
		$this->width	= imagesx( $this->resource );		
		$this->height	= imagesy( $this->resource );		
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
}
?>