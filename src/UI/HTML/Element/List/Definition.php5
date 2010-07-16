<?php
/**
 *	HTML Definition List Tag.
 *	@category		cmClasses
 *	@package		UI.HTML.Element.List
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	HTML Definition List Tag.
 *	@category		cmClasses
 *	@package		UI.HTML.Element.List
 *	@extends		UI_HTML_Element_Abstract
 *	@uses			UI_HTML_Element_List_Definition_Term
 *	@uses			UI_HTML_Element_List_Definition_Definition
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_List_Definition extends UI_HTML_Element_Abstract
{
	protected $items		= array();
	protected $nodeName		= 'dl';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$map		Pairs of definition list as map of strings or objects
	 *	@return		void
	 */
	public function __construct( $map = array() )
	{
		foreach( $map as $key => $value )
			$this->add( $key, $value );
	}

	/**
	 *	Adds a definition pair.
	 *	@access		public
	 *	@param		UI_HTML_Element_List_Definition_Term|string			$term		Term string or object
	 *	@param		UI_HTML_Element_List_Definition_Definition|string	$definition	Definition string or object
	 *	@throws		InvalidArgumentException if term is empty
	 *	@throws		InvalidArgumentException if term is neither string nor object
	 *	@throws		InvalidArgumentException if definition is neither string nor object
	 *	@return		self
	 */
	public function add( $term, $definition )
	{
		if( empty( $term ) )
			throw new InvalidArgumentException( 'Term cannot be empty' );
		if( is_string( $term ) )
			$term	= new UI_HTML_Element_List_Definition_Term( $term );
		if( !( $term instanceof UI_HTML_Element_List_Definition_Term ) )
			throw new InvalidArgumentException( 'Term has to be UI_HTML_Element_List_Definition_Term or string' );
		if( is_string( $definition ) )
			$definition	= new UI_HTML_Element_List_Definition_Definition( $definition );
		if( !( $definition instanceof UI_HTML_Element_List_Definition_Definition ) )
			throw new InvalidArgumentException( 'Definition has to be UI_HTML_Element_List_Definition_Definition or string' );

		$this->items[] = $term->render().$definition->render();
		return $this;
	}

	/**
	 *	Returns rendered HTML string.
	 *	@access		public
	 *	@return		string
	 */
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