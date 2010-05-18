<?php
/**
 *	Parses a XML Document to a Tree of XML_DOM_Nodes.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		XML.DOM
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Parses a XML Document to a Tree of XML_DOM_Nodes.
 *	@category		cmClasses
 *	@package		XML.DOM
 *	@extends		ADT_OptionObject
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_SyntaxValidator
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class XML_DOM_Parser extends ADT_OptionObject
{
	/**	@var	DOMDocument		$document		DOM Document */
	protected $document			= NULL;
	/**	@var	array			$attributes		List of DOM Document Options */
	protected $attributes	= array(
		"version",
		"encoding",
		"standalone",
		"type",
		"compression",
		"charset"
	);

	/**
	 *	Returns DOM Document.
	 *	@access		public
	 *	@return		DOMDocument
	 */
	public function getDocument()
	{
		return $this->document;
	}

	/**
	 *	Loads XML String into DOM Document Object before parsing.
	 *	@access		public
	 *	@param		string		$xml			XML to be parsed
	 *	@return		void
	 */	
	protected function loadXml( $xml )
	{
		$xsv	= new XML_DOM_SyntaxValidator;
		if( !$xsv->validate( $xml ) )
			throw new Exception( "XML Document is not valid:".$xsv->getErrors() );
		$this->document	=& $xsv->getDocument();
		$this->clearOptions();
		foreach( $this->attributes as $attribute )
			if( isset( $this->document->$attribute ) )
				$this->setOption( $attribute, $this->document->$attribute );
	}

	/**
	 *	Parses XML String to XML Tree.
	 *	@access		public
	 *	@param		string		$xml			XML to parse
	 *	@return		XML_DOM_Node
	 */
	public function & parse( $xml )
	{
		$this->loadXml( $xml );
		$root	= $this->document->firstChild;
		while( $root->nodeType == XML_COMMENT_NODE )
			$root	= $root->nextSibling;
		
		$tree	=& new XML_DOM_Node( $root->nodeName );
		if( $root->hasAttributes())
		{
			$attributeNodes	= $root->attributes;
			foreach( $attributeNodes as $attributeNode )
				$tree->setAttribute( $attributeNode->nodeName, $attributeNode->nodeValue );
		}
		$this->parseRecursive( $root, $tree );
		return $tree;
	}	

	/**
	 *	Parses XML File to XML Tree recursive.
	 *	@access		protected
	 *	@param		DOMElement		$root		DOM Node Element
	 *	@param		XML_DOM_Node	$tree		Parent XML Node
	 *	@return		bool
	 */
	protected function parseRecursive( &$root, &$tree )
	{
		$nodes = array();
		if( $child = $root->firstChild )
		{
			while( $child )
			{
				$attributes	= $child->hasAttributes()? $child->attributes : array();
				switch( $child->nodeType )
				{
					case XML_ELEMENT_NODE:
						$node =& new XML_DOM_Node( $child->nodeName );
						if( !$this->parseRecursive( $child, $node ) )
						{
	#						$node->setContent( utf8_decode( $child->textContent ) );
							$node->setContent( $child->textContent );
						}
						foreach( $attributes as $attribute)
							$node->setAttribute( $attribute->nodeName, stripslashes( $attribute->nodeValue ) );
						$tree->addChild( $node );
						break;
					case XML_TEXT_NODE:
						if( strlen( trim( $content = $child->textContent ) ) )
						{
							return false;
						}
						else if( isset( $attributes['type'] ) && preg_match( "/.*ml/i", $attributes['type'] ) )
						{
							return false;
						}
						break;
					case XML_CDATA_SECTION_NODE:
						$tree->setContent( stripslashes( $child->textContent ) );
						break;
					default:
						break;
				}
				$child = $child->nextSibling;
			}
		}
		return true;
	}
}
?>