<?php
/**
 *	Serializer for Data Object into XML.
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
 *	@since			26.12.2005
 *	@version		$Id$
 */
/**
 *	Serializer for Data Object into XML.
 *	@category		cmClasses
 *	@package		XML.DOM
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.12.2005
 *	@version		$Id$
 */
class XML_DOM_ObjectSerializer
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
		$root	= new XML_DOM_Node( "object" );
		$root->setAttribute( 'class', get_class( $object ) );
		$vars	= get_object_vars( $object );
		self::serializeVarsRec( $vars, $root );
		$builder	= new XML_DOM_Builder();
		$serial		= $builder->build( $root, $encoding );
		return $serial;
	}
	
	/**
	 *	Adds XML Nodes to a XML Tree by their Type while supporting nested Arrays.
	 *	@access		protected
	 *	@static
	 *	@param		array			$array		Array of Vars to add
	 *	@param		XML_DOM_Node	$node		current XML Tree Node
	 *	@return		string
	 */
	protected static function serializeVarsRec( $array, &$node )
	{
		foreach( $array as $key => $value)
		{
			switch( gettype( $value ) )
			{
				case 'NULL':
					$child	=& new XML_DOM_Node( "null" );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'boolean':
					$child	=& new XML_DOM_Node( "boolean", (int) $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'string':
					$child	=& new XML_DOM_Node( "string", $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'integer':
					$child	=& new XML_DOM_Node( "integer", $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'double':
					$child	=& new XML_DOM_Node( "double", $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'array':
					$child	=& new XML_DOM_Node( "array" );
					$child->setAttribute( "name", $key );
					self::serializeVarsRec( $value, $child );
					$node->addChild( $child );
					break;
				case 'object':
					$child	=& new XML_DOM_Node( "object" );
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
?>