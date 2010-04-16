<?php
/**
 *	HTML Button Container (for CSS).
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	HTML Button Container (for CSS).
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@extends		UI_HTML_Element_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_Button_Container extends UI_HTML_Element_Abstract
{
	public static $defaultClass	= "buttons default";

	public function __construct()
	{
		$this->setClass( self::$defaultClass );
	}

	public function addButton( $button )
	{
		$this->addContent( $button );
		return $this;
	}

	public function render()
	{
		$list	= array();
		if( !$this->content )
			return '';
		foreach( $this->content as $button )
		{
			if( $button instanceof UI_HTML_Element_Abstract )
				$button	= $button->render();
			$list[]	= $button;
		}
		$list	= join( $list );
		$attributes	= $this->renderAttributes();
		return '<div'.$attributes.'>'.$list.'<div style="clear: both"></div></div>';
	}
}
?>