<?php
class UI_HTML_Legend extends UI_HTML_Abstract
{
	public function __construct( $label = NULL, $attributes = NULL )
	{
		if( !is_null( $label ) )
			$this->setContent( $label );
		if( !is_null( $attributes ) )
			$this->setAttributes( $attributes );
	}
	
	public function render()
	{
		$content	= $this->renderInner( $this->content );
		if( !is_string( $content ) )
			throw new InvalidArgumentException( 'Legend content is neither rendered nor renderable' );
		return UI_HTML_Tag::create( "legend", $content, $this->getAttributes() );	
	}
}
?>