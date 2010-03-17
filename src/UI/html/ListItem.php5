<?php
class UI_HTML_ListItem extends UI_HTML_Abstract
{
	public function __construct( $content = NULL, $attributes = NULL )
	{
		if( !is_null( $content ) )
			$this->setContent( $content );
		if( !is_null( $attributes ) )
			$this->setAttributes( $attributes );
	}
	
	public function render()
	{
		$content	= $this->renderInner( $this->content );
		return UI_HTML_Tag::create( "li", $content, $this->getAttributes() );
	}
}
?>