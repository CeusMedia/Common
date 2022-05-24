<?php
/**
 *	Serializer for Data Object into XML.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			26.12.2005
 */

namespace CeusMedia\Common\XML\DOM;

/**
 *	Serializer for Data Object into XML.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			26.12.2005
 */
class ObjectSerializer
{
	/**
	 *	Builds XML String from an Object.
	 *	@access		public
	 *	@static
	 *	@param		mixed		$object		Object to serialize
	 *	@param		string		$encoding	Encoding Type
	 *	@return		string
	 */
	public static function serialize( $object, $encoding = "utf-8" )
	{
		$root	= new Node( "object" );
		$root->setAttribute( 'class', get_class( $object ) );
		$vars	= get_object_vars( $object );
		self::serializeVarsRec( $vars, $root );
		$builder	= new Builder();
		$serial		= $builder->build( $root, $encoding );
		return $serial;
	}

	/**
	 *	Adds XML Nodes to a XML Tree by their Type while supporting nested Arrays.
	 *	@access		protected
	 *	@static
	 *	@param		array		$array		Array of Vars to add
	 *	@param		Node		$node		current XML Tree Node
	 *	@return		string
	 */
	protected static function serializeVarsRec( $array, &$node )
	{
		foreach( $array as $key => $value)
		{
			switch( gettype( $value ) )
			{
				case 'NULL':
					$child	= new Node( "null" );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'boolean':
					$child	= new Node( "boolean", (int) $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'string':
					$child	= new Node( "string", $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'integer':
					$child	= new Node( "integer", $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'double':
					$child	= new Node( "double", $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'array':
					$child	= new Node( "array" );
					$child->setAttribute( "name", $key );
					self::serializeVarsRec( $value, $child );
					$node->addChild( $child );
					break;
				case 'object':
					$child	= new Node( "object" );
					$child->setAttribute( "name", $key );
					$child->setAttribute( "class", get_class( $value ) );
					$vars	= get_object_vars( $value );
					self::serializeVarsRec( $vars, $child );
					$node->addChild( $child );
					break;
			}
		}
	}
}
