<?php
import( 'de.ceus-media.xml.dom.XML_DOM_Node' );
import( 'de.ceus-media.xml.dom.XML_DOM_SyntaxValidator' );
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.ui.DevOutput' );
/**
 *	@package	xml
 *	@subpackage	dom
 *	@extends	OptionObject
 *	@uses		XML_DOM_Node
 *	@uses		XML_DOM_SyntaxValidator
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	@package	xml
 *	@subpackage	dom
 *	@extends	OptionObject
 *	@uses		XML_DOM_Node
 *	@uses		XML_DOM_SyntaxValidator
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class XML_DOM_Parser extends OptionObject
{
	/**	@var	DOMDocument	_doc		DOM Document */
	var $_doc;
	/**	@var	array			attributes	List of DOM Document Options */
	var $attributes	= array(
			"version",
			"encoding",
			"standalone",
			"type",
			"compression",
			"charset"
			);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		xml			XML to be parsed
	 *	@return		void
	 */
	public function __construct( $xml = false )
	{
		parent::__construct();
		if( $xml )
			$this->loadXML( $xml );
	}
	
	/**
	 *	Loads XML String into DOM Document Object before parsing.
	 *	@access		public
	 *	@param		string		xml			XML to be parsed
	 *	@param		bool			abort		Flag: break on Errors showing Messages
	 *	@return		bool
	 */	
	function loadXML( $xml, $abort = true )
	{
		$xsv	= new XML_DOM_SyntaxValidator;
		if( $xsv->validate( $xml ) )
		{
			$this->_doc	=& $xsv->getDocument();
			$this->clearOptions();
			foreach( $this->attributes as $attribute )
				if( isset( $this->_doc->$attribute ) )
					$this->setOption( $attribute, $this->_doc->$attribute );
			return true;
		}
		else
		{
			if( $abort )
			{
				print_m( $xsv->getErrors() );
				trigger_error( "XML_DOM_Parser[loadXML]: Error while parsing the document", E_USER_ERROR );
			}
			else
			{
				$this->_doc	= domxml_new_doc( "1.0" );
				return false;
			}
		}
	}




	/**
	 *	Parses XML File to XML Tree.
	 *	@access		public
	 *	@param		bool		debug		Flag: debug mode
	 *	@return		XML_DOM_Node
	 */
	function & parse( $debug = false )
	{
		$oid = 0;
		$root = $this->_doc->firstChild;
		$tree =& new XML_DOM_Node( $root->nodeName );
		$tree->setOid( $oid );
		if( $root->hasAttributes())
		{
			$Array = $root->attributes;
			foreach( $Array as $DomAttribute )
				$tree->setAttribute( $DomAttribute->nodeName, $DomAttribute->nodeValue );
		}
		$this->_parse_rec( $root, $tree, $oid, $debug );
		return $tree;
	}
	
	function parse2( $xml )
	{
		$domtree		= domxml_xmltree( $xml );
		$domroot		= $domtree->children[0];
		if( isset( $domroot->children ) )
		{
			$root	= new XML_DOM_Node( $domroot->tagname );
			foreach( $domroot->children as $child )
				$this->_parse2_rec( $root, $child );
		}
		else
		{
			$root	= new XML_DOM_Leaf( $domroot->tagname );
		}
		if( isset( $domroot->attributes ) )
		{
			foreach( $domroot->attributes as $attribute )
				$root->setAttribute( $attribute->name, $attribute->value );
		}
	}
	
	function _parse2_rec( &$root, $node )
	{
		switch( $node->nodeType )
		{
			case XML_TEXT_NODE:
				$root->addChild( new XML_DOM_Leaf( "#text", $node->get_content() ) );
				break;
			case XML_ELEMENT_NODE:
				$new	= new XML_DOM_Node( $node->node_nodeName );
				foreach( $node->children as $child )
					$this->_parse2_rec( $new, $child );
				$root->addChild( $new );
				break;
		}
	}
	
	
	/**
	 *	Returns Node by enumerated OID.
	 *	@access		public
	 *	@param		XML_DOM_Node	tree		Tree
	 *	@param		int				oid		Object ID
	 *	@return		XML_DOM_Node
	 */
	function getNodeByOid( $tree, $oid )
	{
		if($tree->_oid == $oid )
			return $tree;
		else if( $tree->hasChildren() )
		{
			foreach( $tree->getChildren() as $child )
				if( $node = $this->getNodeByOid( $child, $oid ) )
					return $node;
		}
		return false;
	}

	/**
	 *	Parses XML File to Array.
	 *	@access		public
	 *	@return		array
	 *	@deprecated	not useful, use parse instead
	 */
	function & toArray()
	{
		$array = array();
		$root = $this->_doc->firstChild;
		$subarray =& $array[$root->nodeName];
		if( $root->hasAttributes())
		{
			$attrs = $root->attributes;
			foreach( $attrs as $DomAttribute )
				$subarray["@".$DomAttribute->nodeName] = $DomAttribute->nodeValue;
		}
		$this->_toArray_rec( $root, $subarray);
		return $array;
	}

	/**
	 *	Returns Tree as array.
	 *	@access		public
	 *	@param		bool		debug		Flag: debug mode
	 *	@return		array
	 */
	function toList( $debug = false )
	{
		$root = $this->_doc->firstChild;
		$list = array();
		$oid = 0;
		$array = array( "oid"	=> $oid );
		if( $root->hasAttributes())
		{
			$attributes = $root->attributes;
			foreach( $attributes as $attribute )
				$array['attributes'][$attribute->nodeName] = $attribute->nodeValue;
		}
		$list[] = $array;
		$this->_toList_rec( $root, $list, $oid, $debug );
		return $list;
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Parses XML File to XML Tree recursive.
	 *	@access		private
	 *	@param		DOMElement		root			DOM Node Element
	 *	@param		XML_DOM_Node	tree			Parent XML Node
	 *	@param		int				oid			Object ID
	 *	@param		bool				debug		Flag: debug mode
	 *	@return		bool
	 */
	function _parse_rec( &$root, &$tree, &$oid, $debug = false )
	{
		$nodes = array();
		if( $child = $root->firstChild )
		{
			while( $child )
			{
				if( $debug )
				{
					error_reporting( 7 );
//					echo "<b>".$root->nodeName."::".max($child->nodeName, $child->name)." [".$child->nodeType."]: ".$child->hasChildNodes()."</b>:<br>";
//					print_m( $child );
				}
				$attributes	= $child->hasAttributes()? $child->attributes : array();
				if( $child->nodeType == 3 && strlen( trim( $content = $child->textContent ) ) )
				{
					return false;
				}
				else if( $child->nodeType == 3 && isset( $attributes['type'] ) && preg_match( "/.*ml/i", $attributes['type'] ) )
				{
					return false;
				}
				else if( $child->nodeType == 1 )
				{
					$oid++;
					$node =& new XML_DOM_Node( $child->nodeName );
					$node->setOid( $oid );
					foreach( $attributes as $attribute)
						$node->setAttribute( $attribute->nodeName, $attribute->nodeValue );
					if( !$this->_parse_rec( $child, $node, $oid, $debug ) )
					{
						$node->setContent( utf8_decode( $child->textContent ) );
					}
					$tree->addChild( $node );
				}
				$child = $child->nextSibling;
			}
		}
		return true;
	}

	/**
	 *	Returns Tree as array recursive.
	 *	@access		private
	 *	@param		DomDocument	root			DOM Document
	 *	@param		array		list			Reference to list
	 *	@param		int			oid			Object ID
	 *	@param		bool			debug		Flag: debug mode
	 *	@return		void
	 */
	function _toList_rec( &$root, &$list, &$oid, $debug = false )
	{
		if( $child = $root->firstChild )
		{
			while( $child )
			{
				$oid++;
				if( $debug )
				{
					error_reporting( 7 );
//					echo "<b>".$oid.":".$root->nodeName."::".max($child->tagname, $child->name)." [".$child->nodeType."]: ".$child->hasChildNodes()."</b>:<br>";
					print_m( $child );
				}
				$array = array( "oid" => $oid );

				if( $child->nodeType == 8 )
				{
					$array['type'] = "comment";
					$array['content'] = $child->nodeValue;
					$list[] = $array;
				}
				else if( $child->nodeType == 3 )
				{
					$array['type'] = "content";
					$array['content'] = $child->nodeValue;
					$list[] = $array;
				}
				else if( $child->nodeType == 1 )
				{
					$array['type'] = "node";
					$node =& new XML_DOM_Node( $child->tagname );
					if( $child->hasAttributes()&& $attributes = $child->attributes )
						foreach( $attributes as $attribute )
							$array['attributes'][$attribute->nodeName] = $attribute->nodeValue;
					$list[] = $array;
					if( $child->hasChildNodes() )
						$this->_toList_rec( $child, $list, $oid, $debug );
				}
				$child =& $child->nextSibling;
			}
		}
	}

	/**
	 *	Parses XML File to Array recursive.
	 *	@access		private
	 *	@param		DOMElement	root			DOM Node Element
	 *	@param		array		array		Parent Array
	 *	@return		void
	 */
	function _toArray_rec( $root, &$array)
	{
		if( $child = $root->firstChild )
		{
			while( $child )
			{
				if( $child->nodeType == XML_ELEMENT_NODE )
				{
					$node = array();
					if( $child->hasAttributes()&& $attrs = $child->attributes )
						foreach( $attrs as $DomAttribute )
							$node["@".$DomAttribute->nodeName] = $DomAttribute->nodeValue;
					$content = $child->textContent;
//					xmp( $content);
					if( $content)
						if( trim( $content) == $content )
//						if( !substr_count( "    \n", $content ) )
							$node ['#text'] = $content;
					if( $child->hasChildNodes() )
						$this->_toArray_rec( $child, $node );
					$array[$child->nodeName] = $node;
				}
				$child =& $child->nextSibling;
			}
		}
	}
}
?>