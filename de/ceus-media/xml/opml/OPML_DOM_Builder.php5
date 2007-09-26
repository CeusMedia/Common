<?php
import( 'de.ceus-media.xml.dom.XML_DOM_Node' );
import( 'de.ceus-media.xml.dom.XML_DOM_Leaf' );
import( 'de.ceus-media.xml.dom.XML_DOM_Builder' );
/**
 *	Builder for OPML Files.
 *	@package		xml
 *	@subpackage		opml
 *	@extends		XML_DOM_Node
 *	@uses			XML_DOM_Leaf
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.02.2006
 *	@version		0.1
 */
/**
 *	Builder for OPML Files.
 *	@package		xml
 *	@subpackage		opml
 *	@extends		XML_DOM_Node
 *	@uses			XML_DOM_Leaf
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.02.2006
 *	@version		0.1
 */
class OPML_DOM_Builder extends XML_DOM_Node
{
	/**	@var	XML_DOM_Builder	_builder			Instance of XML_DOM_Builder */
	var $_builder;
	/**	@var	array			_headers			Array of supported Headers */
	var $_headers	= array(
		"title",
		"dateCreated",
		"dateModified",
		"ownerName",
		"ownerEmail",
		"expansionState",
		"vertScrollState",
		"windowTop",
		"windowLeft",
		"windowBottom",
		"windowRight",
		);
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	version		Version of OPML Document
	 *	@return		void
	 */
	public function __construct( $version = "1.0" )
	{
		parent::__construct( "opml" );
		$this->setAttribute( "version", $version );
		$head	= new XML_DOM_Node( "head" );
		$this->addChild( $head );
		$body	= new XML_DOM_Node( "body" );
		$this->addChild( $body );
		$this->_builder	= new XML_DOM_Builder;
	}
	
	/**
	 *	Adds Outline to OPML Document.
	 *	@access		public
	 *	@param		OPML_DOM_Outline	outline		Outline Node to add
	 *	@return		void
	 */
	function addOutline( $outline )
	{
		$children	=& $this->getChildren();
		$body	=& $children[1];
		$body->addChild( $outline );
	}

	/**
	 *	Sets Header of OPML Document.
	 *	@access		public
	 *	@param		string		key			Key of Header
	 *	@param		string		value		Value of Header
	 *	@return		void
	 */
	function setHeader( $key, $value )
	{
		if( in_array( $key, $this->_headers ) )
		{
			$children	=& $this->getChildren();
			$head	=& $children[0];
			$node	= new XML_DOM_Leaf( $key, $value );
			$head->addChild( $node );
		}
		else
			trigger_error( "OPML_Document[setHeader]: Unsupported Header '".$key."'", E_USER_WARNING );
	}
	
	/**
	 *	Sets Header of OPML Document.
	 *	@access		public
	 *	@param		string		encoding		Encoding of OPML Document
	 *	@return		string
	 */
	function build( $encoding = "utf-8" )
	{
		$xml	= $this->_builder->build( $this, $encoding );
		return $xml;
	}
}
?>