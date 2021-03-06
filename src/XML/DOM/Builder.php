<?php
/**
 *	Builder for XML Strings with DOM.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Builder for XML Strings with DOM.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class XML_DOM_Builder
{
	/**
	 *	Builds XML and returns XML as string.
	 *	@static
	 *	@access		public
	 *	@param		XML_DOM_Node	$tree			XML Tree
	 *	@param		string			$encoding		Encoding Character Set (utf-8 etc.)
	 *	@return		string							Rendered tree as XML string
	 */
	static public function build( XML_DOM_Node $tree, $encoding = "utf-8", $namespaces = array() )
	{
		$document	= new DOMDocument( "1.0", $encoding );
		$document->formatOutput = TRUE;
		$root		= $document->createElement( $tree->getNodename() );
		foreach( $namespaces as $prefix => $namespace )
			$root->setAttribute( "xmlns:".$prefix, $namespace );
		$root		= $document->appendChild( $root );
		self::buildRecursive( $document, $root, $tree, $encoding );
		return $document->saveXML();
	}

	/**
	 *	Writes XML Tree to XML File recursive.
	 *	@static
	 *	@access		protected
	 *	@param		DOMElement		$document	DOM Document
	 *	@param		DOMElement		$root		DOM Element
	 *	@param		XML_DOM_Node	$tree		Parent XML Node
	 *	@param		string			$encoding	Encoding Character Set (utf-8 etc.)
	 *	@return		void
	 */
	static protected function buildRecursive( DOMDocument $document, DOMElement $root, XML_DOM_Node $tree, $encoding )
	{
		foreach( $tree->getAttributes() as $key => $value )
		{
			$root->setAttribute( $key, $value );
		}
		if( $tree->hasChildren() )
		{
			$children = $tree->getChildren();
			foreach( $children as $child )
			{
				$element = $document->createElement( $child->getNodename() );
				self::buildRecursive( $document, $element, $child, $encoding );
				$element = $root->appendChild( $element );
			}
		}
		else if( $tree->hasContent() )
		{
			$text	= $tree->getContent();
			$text	= $document->createTextNode( $text );
			$text	= $root->appendChild( $text );
		}
	}
}
