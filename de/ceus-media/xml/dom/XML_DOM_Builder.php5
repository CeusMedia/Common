<?php
/**
 *	Builder for XML Strings with DOM.
 *	@package	xml
 *	@subpackage	dom
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Builder for XML Strings with DOM.
 *	@package	xml
 *	@subpackage	dom
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class XML_DOM_Builder
{
	/**	@var	DOMDocument	_doc		DOM Document */
	var $_doc;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Builds XML and returns XML as string.
	 *	@access		public
	 *	@param		XML_DOM_Node	tree		XML Tree
	 *	@param		string			encoding	Encoding Type
	 *	@return		string
	 */
	function build( $tree, $encoding = "utf-8" )
	{
		$this->_doc = new DOMDocument( "1.0" );
		$this->_doc->formatOutput = true;
		$root = $this->_doc->createElement( $tree->getNodename() );
		$root = $this->_doc->appendChild( $root );
		$this->_build_rec( $root, $tree, $encoding );
		$xml	= $this->_doc->saveXML();
		if( $encoding )
		{
			$xml	= explode( "\n", $xml );
			array_shift( $xml );
			array_unshift( $xml, '<?xml version="1.0" encoding="'.$encoding.'"?>' );
			$xml	= implode( "\n", $xml );
		}
		return $xml;
	}

	/**
	 *	Writes XML Tree to XML File recursive.
	 *	@access		private
	 *	@param		resource			root		DOM Document Element
	 *	@param		XML_DOM_Node	tree		Parent XML Node
	 *	@return		void
	 */
	function _build_rec( $root, $tree, $encoding )
	{
		$attrs = $tree->getAttributes();
		foreach( $attrs as $attr_key => $attr_value )
			$root->setAttribute( $attr_key, utf8_encode( $attr_value ) );
		if( method_exists( $tree, 'hasChildren' ) && $tree->hasChildren() )
		{
			$children =& $tree->getChildren();
			foreach( $children as $child )
			{
				$element = $this->_doc->createElement( $child->getNodename() );
				$this->_build_rec( $element, $child, $encoding );
				$element = $root->appendChild( $element );
			}
		}
		else if( $tree->hasContent() )
		{
			$content	= utf8_encode( (string)$tree->getContent() );
			$text = $this->_doc->createTextNode( $content );
			$text = $root->appendChild( $text );
		}
	}
	
	/**
	 *	Writes Array to XML File.
	 *	@access		public
	 *	@param		array		array		Structures associative array
	 *	@return		void
	 */
	function buildFromArray ($array)
	{
		$this->_doc = domxml_new_doc("1.0");
		foreach ($array as $key => $value)
		{
			if (is_array ($value))
			{
				$root = $this->_doc->createElement($key);
				$root = $this->_doc->appendChild($root);
				$this->_buildFromArray_rec ($root, $value);
			}
		}
		return $this->_doc->dump_mem(true);
	}

	/**
	 *	Writes Array to XML File recursive.
	 *	@access		private
	 *	@param		DOMElement	root			DOM Node Element
	 *	@param		array		array		Structures associative array
	 *	@return		void
	 */
	function _buildFromArray_rec ($root, $array)
	{
		if (count ($array))
		{
			foreach ($array as $key => $value)
			{
				if (is_array ($value))
				{
					$element = $this->_doc->createElement ($key);
					$element = $root->appendChild ($element);
					$this->_buildFromArray_rec ($element, $value);
				}
				else 
				{
					$prefix = substr ($key, 0, 1);
					$cut	= substr($key, 1);

					if ($prefix == "#")
					{
						$text = $this->_doc->createTextNode($value);
						$text = $root->appendChild($text);
					}
					else if ($prefix == "@")
					{
						$root->set_attribute ($cut, $value);	
					}
				}
			}
		}
	}
}
?>