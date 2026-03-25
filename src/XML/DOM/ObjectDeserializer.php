<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Deserializer for XML into a Data Object.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2025 Christian Würker
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
 *	@copyright		2007-2025 Christian Würker
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
	 *	@return		mixed
	 *	@throws		Exception
	 */
	public static function deserialize( string $xml )
	{
		$parser	= new Parser();
		$tree	= $parser->parse( $xml );
		$class	= stripslashes( $tree->getAttribute( 'class' ) );
		if( !class_exists( $class ) )
			throw new Exception( 'Class "'.$class.'" has not been loaded, yet.' );
		$object	= new $class();
		self::deserializeVarsRec( $tree->getChildren(), $object );
		return $object;
	}

	/**
	 *	Adds nested Vars to an Element by their Type while supporting nested Arrays.
	 *	@access		protected
	 *	@param		array		$children		Array of Vars to add
	 *	@param		mixed		$element		current Position in Object
	 *	@return		void
	 */
	protected static function deserializeVarsRec( array $children, &$element )
	{
		foreach( $children as $child ){
			$name		= $child->getAttribute( 'name' );
			$varType	= $child->getNodeName();
			if( is_object( $element ) ){
				if( !isset( $element->$name ) )
					$element->$name	= NULL;
				$pointer	=& $element->$name;
			}
			else{
				if( !isset( $element->$name ) )
					$element[$name]	= NULL;
				$pointer	=& $element[$name];
			}

			switch( $varType ){
				case 'boolean':
					$pointer	= (bool) $child->getContent();
					break;
				case 'string':
					$pointer	= utf8_decode( $child->getContent() );
					break;
				case 'integer':
					$pointer	= (int) $child->getContent();
					break;
				case 'double':
					$pointer	= (double) $child->getContent();
					break;
				case 'array':
					$pointer	= [];
					self::deserializeVarsRec( $child->getChildren(), $pointer );
					break;
				case 'object':
					$class		= $child->getAttribute( 'class' );
					$pointer	= new $class();
					self::deserializeVarsRec( $child->getChildren(), $pointer );
					break;
				default:
					$pointer	= NULL;
					break;
			}
		}
	}
}
