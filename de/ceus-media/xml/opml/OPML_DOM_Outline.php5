<?php
import( 'de.ceus-media.xml.dom.XML_DOM_Node' );
/**
 *	XML Node for OPML Outlines.
 *	@package		xml
 *	@subpackage		opml
 *	@extends		XML_DOM_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.02.2006
 *	@version		0.1
 */
class OPML_DOM_Outline extends XML_DOM_Node
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct( "outline" );
	}
	
	/**
	 *	Adds an Outline Node to this Outline Node.
	 *	@access		public
	 *	@param		OPML_DOM_Outline	outline		Outline Node
	 *	@return		void
	 */
	function addOutline( $outline )
	{
		$this->addChild( $outline );
	}
}
?>