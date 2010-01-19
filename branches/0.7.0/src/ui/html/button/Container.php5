<?php
/**
 *	HTML Button Container (for CSS).
 *	@package	APL.UI.HTML.Button
 *	@author		Christian Würker <christian.wuerker@ceus-media.de>
 *	@since		0.0.2
 */
/**
 *	HTML Button Container (for CSS).
 *	@package	APL.UI.HTML.Button
 *	@extends	UI_HTML_Abstract
 *	@author		Christian Würker <christian.wuerker@ceus-media.de>
 *	@since		0.0.2
 */
class UI_HTML_Button_Container extends UI_HTML_Abstract
{
	protected $defaultClass	= "buttons button-bar";
	protected $content	= array();

	public function __construct( UI_HTML_Buffer $buffer )
	{
		$this->content[]	= $buffer->render();	
		$this->addClass( $this->defaultClass );
	}

	public function addButton( $button )
	{
		$this->content[]	= $button;
	}

	public function render()
	{
		$list	= array();
		if( !$this->content )
			return '';
		foreach( $this->content as $button )
		{
			if( $button instanceof UI_HTML_Abstract )
				$button	= $button->render();
			$list[]	= $button;
		}
		$list	= join( $list );
		return UI_HTML_Tag::create( "div", $list, $this->getAttributes() );
	}
}
?>