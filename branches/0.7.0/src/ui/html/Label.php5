<?php
class UI_HTML_Label extends UI_HTML_Abstract
{
	public function __construct( $label = NULL, $relation = NULL, $attributes = NULL )
	{
		if( !is_null( $label ) )
			$this->setContent( $label );
		if( !is_null( $attributes ) )
			$this->addAttributes( $attributes );
	}

	public function render()
	{
		$content	= $this->renderInner( $this->content );
		return UI_HTML_Tag::create( 'label', $content, $this->getAttributes() );
	}
}
?>