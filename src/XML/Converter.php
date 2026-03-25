<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpUnused */

/**
 *	Converts XML strings statically to plain objects (stdClass), trees of nodes (XML_DOM_Node), JSON etc.
 *
 *	Copyright (c) 2010-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2025 Ceus Media
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML;

use CeusMedia\Common\ADT\JSON\Encoder as JsonEncoder;
use CeusMedia\Common\Exception\Conversion as ConversionException;
use CeusMedia\Common\XML\DOM\Parser;
use CeusMedia\Common\XML\DOM\Node as DomNode;
#use DOMNode;
use Exception;
use stdClass;

/**
 *	Converts XML to plain objects (stdClass), trees of nodes (XML_DOM_Node), JSON etc.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2025 Ceus Media
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Converter
{
	/**
	 *	Converts an XML string to a tree of plain objects and returns JSON string.
	 *	@static
	 *	@access		public
	 *	@param		string		$xml		XML string
	 *	@return		string		JSON representation of XML string
	 *	@throws		Exception
	 *	@throws		ConversionException
	 */
	public static function toJson( string $xml ): string
	{
		$object	= self::toPlainObject( $xml );
		return JsonEncoder::create()->encode( $object );
	}

	/**
	 *	Converts an XML string to a tree of plain objects (stdClass).
	 *	@static
	 *	@access		public
	 *	@param		string		$xml		XML string
	 *	@return		object
	 *	@throws		Exception
	 */
	public static function toPlainObject( string $xml ): object
	{
		$parser		= new Parser();
		$document	= $parser->parse( $xml );
		$rootName	= $document->getNodeName();
		$object		= (object) [
			$rootName => new stdClass()
		];
		self::convertToObjectRecursive( $document, $object->$rootName );
		return $object;
	}

	/**
	 *	Converts DOM node to tree of objects recursively and in-situ.
	 *	@static
	 *	@access		protected
	 *	@param		DomNode		$node		DOM node to convert
	 *	@param		object		$object		Tree for objects
	 *	@return		void
	 */
	protected static function convertToObjectRecursive( DomNode $node, object $object )
	{
		$object->children	= new stdClass();
		$object->attributes	= new stdClass();
		foreach( $node->getChildren() as $childNode ) {
			$childObject	= new stdClass();
			$nodeName		= $childNode->getNodeName();
			$object->children->$nodeName	= $childObject;
			self::convertToObjectRecursive( $childNode, $childObject );
		}
		if( $node->getAttributes() ) {
			foreach( $node->getAttributes() as $key => $value )
				$object->attributes->$key	= $value;
		}
		$object->content = $node->getContent();
	}
}
