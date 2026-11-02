<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Deserializer for XML into a Data Object.
 *
 *	Copyright (c) 2007-2026 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\DOM;

use Exception;

/**
 *	Deserializer for XML into a Data Object.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			rewrite, use ObjectFactory
 */
class ObjectDeserializer
{
	/**
	 *	Builds Object from XML of a serialized Object.
	 *	@access		public
	 *	@param		string		$xml			XML String of a serialized Object
	 *	@return		object
	 *	@throws		Exception
	 */
	public static function deserialize( string $xml ): object
	{
		$parser	= new Parser();
		$tree	= $parser->parse( $xml );
		$object	= self::createObject( $tree->getAttribute( 'class' ) ?? '' );
		self::deserializeVarsRec( $tree->getChildren(), $object );
		return $object;
	}

	/**
	 *	Adds nested Vars to an Element by their Type while supporting nested Arrays.
	 *	@access		protected
	 *	@param		array		$children		Array of Vars to add
	 *	@param		mixed		$element		current Position in Object
	 *	@return		void
	 *	@throws		Exception	if class of nested object is not loaded
	 */
	protected static function deserializeVarsRec( array $children, mixed &$element ): void
	{
		foreach( $children as $child ){
			$name	= (string) $child->getAttribute( 'name' );
			$value	= self::deserializeValue( $child );
			if( is_object( $element ) )
				$element->$name	= $value;
			else if( is_array( $element ) )
				$element[$name]	= $value;
		}
	}

	/**
	 *	Returns value of a serialized var node by its type.
	 *	@access		protected
	 *	@param		Node		$child			Node of serialized var
	 *	@return		mixed
	 *	@throws		Exception	if class of nested object is not loaded
	 */
	protected static function deserializeValue( Node $child ): mixed
	{
		switch( $child->getNodeName() ){
			case 'boolean':
				return (bool) $child->getContent();
			case 'string':
				return $child->getContent() ?? '';
			case 'integer':
				return (int) $child->getContent();
			case 'double':
				return (float) $child->getContent();
			case 'array':
				$array	= [];
				self::deserializeVarsRec( $child->getChildren(), $array );
				return $array;
			case 'object':
				$object	= self::createObject( $child->getAttribute( 'class' ) ?? '' );
				self::deserializeVarsRec( $child->getChildren(), $object );
				return $object;
			default:
				return NULL;
		}
	}

	/**
	 *	Creates an object of a serialized class name.
	 *	The serializer escapes backslashes of class names, so escaped and unescaped names are supported.
	 *	@access		protected
	 *	@param		string		$class			Serialized class name
	 *	@return		object
	 *	@throws		Exception	if class is not loaded
	 */
	protected static function createObject( string $class ): object
	{
		if( !class_exists( $class ) )
			$class	= stripslashes( $class );
		if( !class_exists( $class ) )
			throw new Exception( 'Class "'.$class.'" has not been loaded, yet.' );
		return new $class();
	}
}
