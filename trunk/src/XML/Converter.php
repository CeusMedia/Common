<?php
/**
 *	Converts XML strings statically to plain objects (stdClass), trees of nodes (XML_DOM_Node), JSON etc.
 *
 *	Copyright (c) 2010-2012 Christian Würker (ceusmedia.com)
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
 *	@package		XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Ceus Media
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.6
 *	@version		$Id$
 */
/**
 *	Converts XML to plain objects (stdClass), trees of nodes (XML_DOM_Node), JSON etc.
 *
 *	@category		cmClasses
 *	@package		XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Ceus Media
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.6
 *	@version		$Id$
 */
class XML_Converter
{
	/**
	 *	Converts a XML string to a tree of plain objects and returns JSON string.
	 *	@static
	 *	@access		public
	 *	@param		string		$xml		XML string
	 *	@return		string		JSON representation of XML string
	 */
	public static function toJson( $xml )
	{
		$object	= self::toPlainObject( $xml );
		return json_encode( $object );
	}

	/**
	 *	Converts a XML string to a tree of plain objects (stdClass).
	 *	@static
	 *	@access		public
	 *	@param		string		$xml		XML string
	 *	@return		object
	 */
	public static function toPlainObject( $xml )
	{
		$parser	= new XML_DOM_Parser();
		$document	= $parser->parse( $xml );
		$rootNode	= array_shift( $document->getChildren() );
		$rootName	= $rootNode->getNodeName();
		$object->$rootName	= new stdClass();
		self::convertToObjectRecursive( $rootNode, $object->$rootName );
		return $object;
	}

	/**
	 *	Converts DOM node to tree of objects recursively and in-situ.
	 *	@static
	 *	@access		protected
	 *	@param		DOMNode		$node		DOM node to convert
	 *	@param		object		$object		Tree for objects
	 *	@return		void
	 */
	protected static function convertToObjectRecursive( $node, $object )
	{
		$object->children	= new stdClass();
		$object->attributes	= new stdClass();
		foreach( $node->getChildren() as $childNode )
		{
			$childObject	= new stdClass();
			$nodeName		= $childNode->getNodeName();
			$object->children->$nodeName	= $childObject;
			self::convertToObjectRecursive( $childNode, $childObject );
		}
		if( $node->getAttributes() )
		{
			foreach( $node->getAttributes() as $key => $value )
				$object->attributes->$key	= $value;
		}
		$object->content = $node->getContent();
	}
}
?>