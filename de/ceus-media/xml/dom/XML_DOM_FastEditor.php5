<?php
import ("de.ceus-media.file.File");
import ("de.ceus-media.xml.dom.XML_DOM_Node");
import ("de.ceus-media.xml.dom.XML_DOM_FileReader");
import ("de.ceus-media.xml.XML_Format");
/**
 *	@package	xml
 *	@subpackage	dom
 */
/**
 *	@package	xml
 *	@subpackage	dom
 *	@todo		Code Documentation
 */
class XML_DOM_FastEditor extends XML_DOM_FileReader
{
	/**	@var	DOMDocument	_doc		DOM Document Element */
	var $_doc;
	/**	@var	string			filename		URI of XML File to edit */
	var $_filename;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		filename		URI of XML File
	 *	@return		void	
	 */
	public function __construct ( $filename = false )
	{
		if( $filename )
			$this->loadFile( $filename );
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
		$child	= $doc->create_element( $tagname );
		if( $content )
			$child->set_content( $content );
		if( count( $attributes ) )
			foreach( $attributes as $key => $value )
				$child->set_attribute( $key, $value );
		$node->append_child( $child );
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
		$child	= $doc->create_element( $tagname );
		$parent	= $node->parent_node();
		if( $content )
			$child->set_content( $content );
		if( count( $attributes ) )
			foreach( $attributes as $key => $value )
				$child->set_attribute( $key, $value );
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
		return $node->set_attribute( $key, $value );
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
			if ( $child->node_type() == XML_TEXT_NODE )
				$node->remove_child( $child );
		return $node->set_content( $content );
	}





	/**
	 *	Builds XML and returns XML as string.
	 *	@access		public
	 *	@return		string
	 */
	function build()
	{
		$xml	= $this->_doc->dump_mem( true );
		$xf	= new XML_Format();
		$xml	= $xf->tidy( $xml );
		return $xml;
	}

	/**
	 *	Writes edited XML to XML File.
	 *	@access		public
	 *	@param		string		filename		URI of XML File
	 *	@return		void
	 */
	function write( $filename = false )
	{
		if( !$filename && $this->_filename)
			$filename	= $this->_filename;
		if( $filename )
		{
			$xml	= $this->build();
			$file	= new File( $filename, 0777 );
			$file->writeString( $xml );
		}
	}




	/**
	 *	@return		XML_DOM_Node
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
	 *	Parses XML File to XML Tree recursive.
	 *	@access		private
	 *	@param		DOMElement		root			DOM Node Element
	 *	@param		int				oid			Object ID
	 *	@return		void
	 */
	function & _getDomNodeByOid_rec (&$root, &$coid, $oid)
	{
		$nodes = array ();
		if ($child = $root->first_child())
		{
			while($child)
			{
				if ($child->node_type() == 3 && trim($content = $child->get_content()))
					return false;
				else if ($child->node_type() == 1)
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
