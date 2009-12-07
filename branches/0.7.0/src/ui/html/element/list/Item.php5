<?php
/**
 *	HTML List Item Tag.
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	HTML List Item Tag.
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@extends		UI_HTML_Element_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_List_Item extends UI_HTML_Element_Abstract
{
	
	public function __construct( $content = NULL )
	{
		if( !is_null( $content ) )
			$this->setContent( $content );
	}

    public function render()
    {
    	$attributes	= array(
    		'id'		=> $this->id
    	);
    	return $this->renderTag( 'li', $this->renderContent(), $attributes );
    }
}
?>