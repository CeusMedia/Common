<?php
/**
 *	HTML Definition List Definition Tag.
 *	@category		cmClasses
 *	@package		UI.HTML.Element.List.Definition
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	HTML Definition List Definition Tag.
 *	@category		cmClasses
 *	@package		UI.HTML.Element.List.Definition
 *	@extends		UI_HTML_Element_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_List_Definition_Definition extends UI_HTML_Element_Abstract
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		UI_HTML_Element_Abstract|string		$content	Term content
	 *	@return		void
	 */
	public function __construct( $content = NULL )
	{
		if( !is_null( $content ) )
			$this->setContent( $content );
	}

	/**
	 *	Returns rendered HTML tag.
	 *	@access		public
	 *	@return		string		Rendered HTML tag of definition term
	 */
    public function render()
    {
    	$attributes	= array(
    		'id'		=> $this->id
    	);
    	return $this->renderTag( 'dd', $this->renderContent(), $attributes );
    }
}
?>