<?php
class UI_HTML_Button_Container extends UI_HTML_Abstract
{
	protected static $defaultClass	= "buttons";
	protected $buttons	= array();

	public function __construct( $buttons = NULL, $attributes = NULL )
	{
		if( !is_null( $buttons ) )
			$this->setButtons( $buttons );
		$this->attributes['class']	= array( self::$defaultClass );
		if( !is_null( $attributes ) )
			$this->addAttributes( $attributes );
	}

	public function render()
	{
		$list	= array();
		foreach( $this->buttons as $button )
			$list[]	= $button;
		$list	= join( $list );
		$clear	= UI_HTML_Tag::create( "div", NULL, array( 'style' => "clear: both" ) );
		return UI_HTML_Tag::create(
			'div',
			$list.$clear,
			$this->getAttributes()
		);
	}

	public function addButton( $button )
	{
		$button	= $this->renderInner( $button );
		if( !is_string( $button ) )
			throw new InvalidArgumentException( 'Given button is neither rendered nor renderable' );
		$this->buttons[]	= $button;
	}

	public function setButtons( $buttons )
	{
		$this->buttons	= array();
		$buttons	= $this->renderInner( $buttons );
		if( is_array( $buttons ) )
			foreach( $buttons as $button )
				$this->addButton( $button );
		else if( is_string( $buttons ) )
			$this->addButton( $buttons );
		else
			throw new InvalidArgumentException( '...' );
	}
}
?>