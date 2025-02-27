<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Parses an XML Document to a Tree of XML_DOM_Nodes.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\DOM;

use CeusMedia\Common\ADT\OptionObject;
use DOMDocument;
use DOMNode;
use Exception;

/**
 *	Parses an XML Document to a Tree of XML_DOM_Nodes.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Parser extends OptionObject
{
	/**	@var	DOMDocument|NULL		$document		DOM Document */
	protected ?DOMDocument $document	= NULL;

	/**	@var	array			$attributes		List of DOM Document Options */
	protected array $attributes			= [
		"version",
		"encoding",
		"standalone",
		"type",
		"compression",
		"charset"
	];

	/**
	 *	Returns DOM Document.
	 *	@access		public
	 *	@return		DOMDocument
	 */
	public function getDocument(): DOMDocument
	{
		return $this->document;
	}

	/**
	 *	Loads XML String into DOM Document Object before parsing.
	 *	@access		public
	 *	@param		string		$xml			XML to be parsed
	 *	@return		static
	 *	@throws		Exception
	 */
	protected function loadXml( string $xml ): static
	{
		$xsv	= new SyntaxValidator;
		if( !$xsv->validate( $xml ) )
			throw new Exception( "XML Document is not valid: ".$xsv->getErrors() );

		$this->document	= $xsv->getDocument();
		$this->clearOptions();
		foreach( $this->attributes as $attribute )
			if( isset( $this->document->$attribute ) )
				$this->setOption( $attribute, $this->document->$attribute );
		return $this;
	}

	/**
	 *	Parses XML String to XML Tree.
	 *	@access		public
	 *	@param		string		$xml			XML to parse
	 *	@return		Node
	 *	@throws		Exception
	 */
	public function parse( string $xml ): Node
	{
		$this->loadXml( $xml );
		$root	= $this->document->firstChild;
		while( $root->nodeType === XML_COMMENT_NODE )
			$root	= $root->nextSibling;

		$tree	= new Node( $root->nodeName );
		if( $root->hasAttributes())
			foreach( $root->attributes as $attributeNode )
				$tree->setAttribute( $attributeNode->nodeName, $attributeNode->nodeValue );

		$this->parseRecursive( $root, $tree );
		return $tree;
	}

	/**
	 *	Parses XML File to XML Tree recursive.
	 *	@access		protected
	 *	@param		DOMNode			$root		DOM Node Element
	 *	@param		Node			$tree		Parent XML Node
	 *	@return		bool
	 */
	protected function parseRecursive( DOMNode  $root, Node $tree ): bool
	{
		foreach( $root->childNodes as $child ){
			$attributes	= $child->hasAttributes()? $child->attributes : [];
			switch( $child->nodeType ){
				case XML_ELEMENT_NODE:
					$node = new Node( $child->nodeName );
					if( !$this->parseRecursive( $child, $node ) )
						$node->setContent( $child->textContent );
					foreach( $attributes as $attribute)
						$node->setAttribute( $attribute->nodeName, stripslashes( $attribute->nodeValue ) );
					$tree->addChild( $node );
					break;
				case XML_TEXT_NODE:
					if( '' !== trim( $child->textContent ) )
						return FALSE;
					else if( isset( $attributes['type'] ) && preg_match( "/.*ml/i", $attributes['type'] ) )
						return FALSE;
					break;
				case XML_CDATA_SECTION_NODE:
					$tree->setContent( stripslashes( $child->textContent ) );
					break;
			}
		}
		return TRUE;
	}
}
