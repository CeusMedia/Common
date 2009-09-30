<?php
class UI_HTML_Fieldset extends UI_HTML_Abstract
{
	protected $content	= NULL;
	protected $legend	= NULL;

	public function __construct( $legend = NULL, $content = NULL )
	{
		if( !is_null( $legend ) )
			$this->setLegend( $legend );
		if( !is_null( $content ) )
			$this->setContent( $content );
	}
	
	public function setLegend( $legend )
	{
		$this->legend	= $legend;
	}

	public function render()
	{
		$legend		= $this->renderInner( $this->legend );
		if( !is_string( $legend ) )
			throw new InvalidArgumentException( 'Fieldset legend is neither rendered nor renderable' );
		
		$content	= $this->renderInner( $this->content );
		if( !is_string( $content ) )
			throw new InvalidArgumentException( 'Fieldset content is neither rendered nor renderable' );

		return UI_HTML_Tag::create( "fieldset", $legend.$content, $this->getAttributes() );
	}
}
?>