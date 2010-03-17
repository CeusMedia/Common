<?php
class UI_HTML_Form extends UI_HTML_Abstract
{
	public function __construct( $action, $content = NULL, $attributes = NULL )
	{
		if( !is_null( $action ) )
			$this->setAction( $action );
		if( !is_null( $content ) )
			$this->setContent( $content );
		if( !is_null( $attributes ) )
			$this->setAttributes( $attributes );
	}
	
	public function render()
	{
		$attributes	= $this->getAttributes();
		if( is_array( $attributes['action'] ) )
			$attributes['action']	= ADT_URL_Inference::buildStatic( $attributes['action'] );
		$content	= $this->renderInner( $this->content );
		return UI_HTML_Tag::create( "form", $content, $attributes );
	}
	
	public function setAction( $url )
	{
		$this->attributes['action']	= $url;	
	}
}
?>