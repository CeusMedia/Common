<?php
class UI_HTML_Link extends UI_HTML_Abstract
{
	protected $label		= NULL;
	protected $url			= NULL;

	public function __construct( $url = NULL, $label = NULL, $attributes = NULL )
	{
		if( !is_null( $attributes ) )
			$this->setAttributes( $attributes );	
		if( !is_null( $label ) )
			$this->setContent( $label );	
		$this->setUrl( $url );	
	}
		
	public function render()
	{
		$attributes	= $this->getAttributes();
		if( is_array( $attributes['href'] ) )
			$attributes['href']	= ADT_URL_Inference::buildStatic( $attributes['href'] );
		$content	= $this->renderInner( $this->content );
		if( !is_string( $content ) )
			throw new InvalidArgumentException( 'Link label is neither rendered nor renderable' );
		return UI_HTML_Tag::create( "a", $content, $attributes );	
	}
	
	public function setUrl( $url )
	{
		$this->attributes['href']	= $url;
	}
}
?>