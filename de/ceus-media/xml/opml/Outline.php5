<?php
import( 'de.ceus-media.xml.dom.Node' );
/**
 *	XML Node for OPML Outlines.
 *	@package		xml.opml
 *	@extends		XML_DOM_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.02.2006
 *	@version		0.6
 */
/**
 *	XML Node for OPML Outlines.
 *	@package		xml.opml
 *	@extends		XML_DOM_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.02.2006
 *	@version		0.6
 */
class XML_OPML_Outline extends XML_DOM_Node
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
	 *	@param		XML_OPML_Outline	$outline		Outline Node
	 *	@return		void
	 */
	public function addOutline( $outline )
	{
		$this->addChild( $outline );
	}
}
?>