<?php
import ("de.ceus-media.file.Writer");
import ("de.ceus-media.xml.dom.FileReader");
import ("de.ceus-media.xml.dom.Builder");
/**
 *	Editor for XML Strings.
 *	@package	xml
 *	@subpackage	dom
 *	@extends	XML_DOM_FileReader 
 *	@uses		XML_DOM_Builder
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		20.07.2005
 *	@version		0.4
 */
/**
 *	Editor for XML Strings.
 *	@package	xml
 *	@subpackage	dom
 *	@extends	XML_DOM_FileReader 
 *	@uses		XML_DOM_Builder
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		20.07.2005
 *	@version		0.4
 */
class XML_DOM_Editor extends XML_DOM_FileReader
{
	/**	@var	DOMDocument	_doc		DOM Document Element */
	var $_doc;
	/**	@var	string			filename		URI of XML File to edit */
	var $_filename;
	/**	@var	string			filename		URI of XML File to edit */
	var $_builder;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		filename		URI of XML File
	 *	@return		void	
	 */
	public function __construct ( $filename = false )
	{
		$this->_builder	= new XML_DOM_Builder();
		if( $filename )
		{
			$this->_filename	= $filename;
			$this->loadFile( $filename );
		}
	}

	/**
	 *	Adds a child to a node.
	 *	@access		public
	 *	@param		DomNode		node		Current node
	 *	@param		string		tagname		Tag name of child node to add
	 *	@param		string		content		Content of child node to add
	 *	@param		array		attributes	Array of attributes of child node to add
	 *	@return		DomNode	
	 */ 
	function appendChild( &$node, $tagname, $content = false, $attributes = array() )
	{
		$doc	= &$node->owner_document();
		$child	= $doc->createElement( $tagname );
		if( $content )
			$child->setContent( $content );
		if( count( $attributes ) )
			foreach( $attributes as $key => $value )
				$child->setAttribute( $key, $value );
		$node->appendChild( $child );
		return $child;
	}

	/**
	 *	Inserts a child before a node.
	 *	@access		public
	 *	@param		DomNode		node		Current node
	 *	@param		string		tagname		Tag name of child node to insert before
	 *	@param		string		content		Content of child node to insert before
	 *	@param		array		attributes	Array of attributes of child node to insert before
	 *	@return		DomNode	
	 */ 
	function insertBefore( &$node, $tagname, $content = false, $attributes = array() )
	{
		$doc	= &$node->owner_document();
		$child	= $doc->createElement( $tagname );
		$parent	= $node->parent_node();
		if( $content )
			$child->set_content( $content );
		if( count( $attributes ) )
			foreach( $attributes as $key => $value )
				$child->setAttribute( $key, $value );
		$child = $parent->insert_before( $child, $node );
		return $child;
	}

	/**
	 *	Removes an attribute of a node.
	 *	@access		public
	 *	@param		DomNode		node		Current node
	 *	@param		string		key			Key of attribute to be removed
	 *	@return		bool
	 */ 
	function removeAttribute( &$node, $key )
	{
		return $node->remove_attribute( $key );
	}

	/**
	 *	Removes an attribute of a node.
	 *	@access		public
	 *	@param		DomNode		node		Current node
	 *	@param		DomNode		child			Node to be removed
	 *	@return		DomNode
	 */ 
	function removeChild( &$node, &$child )
	{
		return $node->remove_child( $child );		
	}

	/**
	 *	Sets an attribute of a node.
	 *	@access		public
	 *	@param		DomNode		node		Current node
	 *	@param		string		key			Key of attribute to be set
	 *	@param		string		value		Value of attribute to be set
	 *	@return		bool
	 */ 
	function setAttribute( &$node, $key, $value )
	{
		return $node->setAttribute( $key, $value );
	}
	
	/**
	 *	Sets the content of a node.
	 *	@access		public
	 *	@param		DomNode		node		Current node
	 *	@param		string		content		Content of node to be set
	 *	@return		bool
	 */ 
	function setContent( &$node, $content )
	{
		$children	= &$node->child_nodes();
		foreach( $children as $child )
			if ( $child->nodeType == XML_TEXT_NODE )
				$node->removeChild( $child );
		return $node->set_content( $content );
	}

	/**
	 *	Builds XML and returns XML as string.
	 *	@access		public
	 *	@param		string		encoding	 	Encoding Type
	 *	@return		string
	 */
	function build( $encoding = "utf-8" )
	{
		$this->_builder->XML_DOM_Builder();
		$tree = $this->parse();
		$xml = $this->_builder->build( $tree, $encoding );
		return $xml;
	}

	/**
	 *	Writes edited XML to XML File.
	 *	@access		public
	 *	@param		string		filename		URI of XML File
	 *	@param		string		encoding	 	Encoding Type
	 *	@return		void
	 */
	function write( $filename = false, $encoding = "utf-8" )
	{
		if( !$filename && $this->_filename)
			$filename	= $this->_filename;
		if( $filename )
		{
			$xml	= $this->build( $encoding );
			$file	= new File_Writer( $filename, 0777 );
			$file->writeString( $xml );
		}
	}

	/**
	 *	Returns reference to DOM Node by Object ID.
	 *	@access		public
	 *	@param		int				oid			Object ID
	 *	@return		DomNode
	 */
	function getDomNodeByOid ( $oid )
	{
		$coid = 0;
		if( $this->_doc )
			return $this->_getDomNodeByOid_rec ($this->_doc->document_element(), $coid, $oid);
		else
			trigger_error( "No XML Document loaded", E_USER_ERROR );
	}
	
	/**
	 *	Returns reference to DOM Node by Object ID recursive.
	 *	@access		private
	 *	@param		DomNode			tree			DOM Node Element
	 *	@param		int				oid			Object ID
	 *	@return		DomNode
	 */
	function & _getDomNodeByOid_rec (&$tree, &$coid, $oid)
	{
		$nodes = array ();
		if ($child = $tree->first_child())
		{
			while($child)
			{
				if ($child->nodeType == 3 && trim($content = $child->get_content()))
					return false;
				else if ($child->nodeType == 1)
				{
					$coid++;
					if ($coid === (int) $oid)
						return $child;
					if( $node =& $this->_getDomNodeByOid_rec( $child, $coid, $oid ) )
						return $node;
				}
				$child =& $child->next_sibling();
			}
		}
	}
}
?>