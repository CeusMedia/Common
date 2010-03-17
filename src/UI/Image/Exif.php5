<?php
import( 'de.ceus-media.adt.list.Dictionary' );
import( 'de.ceus-media.ui.html.Tag' );
class UI_Image_Exif extends ADT_List_Dictionary
{
	protected $imageUri;

	public function __construct( $imageUri )
	{
		if( !function_exists( 'exif_read_data' ) )
			throw new RuntimeException( 'Exif not supported' );
		if( !file_exists( $imageUri ) )
			throw new RuntimeException( 'Image file "'.$imageUri.'" is not existing' );

		$this->imageUri	= $imageUri;
		$this->raw		= exif_read_data( $imageUri );
		foreach( $this->raw as $key => $value )
		{
			if( $key == "MakerNote" )
				continue;
			if( preg_match( "/^UndefinedTag/i", $key ) )
				continue;
			if( is_array( $value ) )
				foreach( $value as $nestKey => $nestValue )
					$this->set( $key.".".$nestKey, $nestValue );
			else
				$this->set( $key, $value );
		}
	}

	public function getThumbnailData()
	{
		$content	= exif_thumbnail( $this->imageUri, $width, $height, $type );
		return array(
			'content'	=> $content,
			'width'		=> $width,
			'height'	=> $height,
			'type'		=> $type
		);
	}

	public function getThumbnailImage()
	{
		$content	= exif_thumbnail( $this->imageUri, $width, $height, $type );
		$attributes	= array(
			'width'		=> $width,
			'height'	=> $height,
			'src'		=> 'data:image/gif;base64,'.base64_encode( $content )
		);
		return UI_HTML_Tag::create( 'img', NULL, $attributes );
	}
	
	public function getRawData()
	{
		return $this->raw;
	}
}
?>