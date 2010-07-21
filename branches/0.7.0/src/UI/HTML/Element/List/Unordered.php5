<?php
/**
 *	HTML Unordered List Tag.
 *	@category		cmClasses
 *	@package		UI.HTML.Element.List
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	HTML Unordered List Tag.
 *	@category		cmClasses
 *	@package		UI.HTML.Element.List
 *	@extends		UI_HTML_Element_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_List_Unordered extends UI_HTML_Element_Abstract
{
	protected $items		= array();
	protected $nodeName		= 'ul';

	public function __construct( $items = array() )
	{
		if( !is_array( $items ) )
			throw new InvalidArgumentException( 'Must be an array' );
		foreach( $items as $item )
			$this->add( $item );
	}

	public function add( $item )
	{
		if( $item instanceof UI_HTML_Element_List_Item )
			$item	= $item->render();
		if( !is_string( $item ) )
			throw new InvalidArgumentException( 'Has to be string or UI_HTML_Element_ListItem' );
		$this->items[] = $item;
		return $this;
	}

	public function render()
	{
    	$attributes	= array(
    		'id'		=> $this->id,
    		'class'		=> $this->class
    	);
		$content	= join( $this->items );
    	return $this->renderTag( $this->nodeName, $content, $attributes );
	}
}
?>