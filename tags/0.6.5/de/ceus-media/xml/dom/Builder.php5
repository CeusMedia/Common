<?php
/**
 *	Builder for XML Strings with DOM.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Builder for XML Strings with DOM.
 *	@package		xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class XML_DOM_Builder
{
	/**	@var	DOMDocument			$document		DOM Document */
	protected $document;
	
	/**
	 *	Builds XML and returns XML as string.
	 *	@access		public
	 *	@param		XML_DOM_Node	$tree			XML Tree
	 *	@param		string			$encoding		Encoding Character Set (utf-8 etc.)
	 *	@return		string
	 */
	public function build( XML_DOM_Node $tree, $encoding = "utf-8" )
	{
		$this->document = new DOMDocument( "1.0", $encoding );
		$this->document->formatOutput = true;
		$root = $this->document->createElement( $tree->getNodename() );
		$root = $this->document->appendChild( $root );
		$this->buildRecursive( $root, $tree, $encoding );
		$xml	= $this->document->saveXML();
		return $xml;
	}

	/**
	 *	Writes XML Tree to XML File recursive.
	 *	@access		protected
	 *	@param		DOMElement		$root		DOM Element
	 *	@param		XML_DOM_Node	$tree		Parent XML Node
	 *	@param		string			$encoding	Encoding Character Set (utf-8 etc.)
	 *	@return		void
	 */
	protected function buildRecursive( DOMElement $root, XML_DOM_Node $tree, $encoding )
	{
		foreach( $tree->getAttributes() as $key => $value )
		{
			$value	= addslashes( $value );
			if( $encoding == "utf-8" && utf8_encode( utf8_decode( $value ) ) != $value )
				$value	= utf8_encode( $value );
			$root->setAttribute( $key, $value );
		}
		if( $tree->hasChildren() )
		{
			$children =& $tree->getChildren();
			foreach( $children as $child )
			{
				$element = $this->document->createElement( $child->getNodename() );
				$this->buildRecursive( $element, $child, $encoding );
				$element = $root->appendChild( $element );
			}
		}
		else if( $tree->hasContent() )
		{
			$text	= (string) $tree->getContent();
			$text	= addslashes( $text );
			if( $encoding == "utf-8" && utf8_encode( utf8_decode( $text ) ) != $text )
				$text	= utf8_encode( $text );
			$text	= $this->document->createTextNode( $text );
			$text	= $root->appendChild( $text );
		}
	}
}
?>