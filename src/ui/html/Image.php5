<?php
class UI_HTML_Image extends UI_HTML_Abstract
{
	protected $title	= NULL;
	protected $url		= NULL;

	public function __construct( $url = NULL, $title = NULL, $attributes = array() )
	{
		if( !empty( $url ) )
			$this->setUrl( $url );
		if( !empty( $title ) )
			$this->setTitle( $title );
		if( $attributes )
			$this->setAttributes( $attributes );
	}

	public function render()
	{
		$attributes	= $this->getAttributes();
		if( empty( $this->url ) )
			throw new InvalidArgumentException( 'Image URL is empty' );
		$attributes['title']	= (string) $this->title;
		$attributes['alt'] 		= (string) $this->title;
		$attributes['src'] 		= $this->url;
		return UI_HTML_Tag::create( 'img', NULL, $attributes );
	}
	
	public function setTitle( $title )
	{
		$this->title	= $title;	
	}
	
	public function setUrl( $url )
	{
		$this->url	= $url;	
	}
}
?>