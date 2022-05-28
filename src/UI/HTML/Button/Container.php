<?php
/**
 *	HTML Button Container (for CSS).
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			0.7.0
 */

namespace CeusMedia\Common\UI\HTML\Button;

use CeusMedia\Common\UI\HTML\Abstraction as HtmlAbstraction;
use CeusMedia\Common\UI\HTML\Buffer as HtmlBuffer;
use CeusMedia\Common\UI\HTML\Tag;

/**
 *	HTML Button Container (for CSS).
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			0.7.0
 */
class Container extends HtmlAbstraction
{
	protected $defaultClass	= "buttons button-bar";
	protected $content	= array();

	public function __construct( HtmlBuffer $buffer )
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
			if( $button instanceof HtmlAbstraction )
				$button	= $button->render();
			$list[]	= $button;
		}
		$list	= join( $list );
		return Tag::create( "div", $list, $this->getAttributes() );
	}
}
