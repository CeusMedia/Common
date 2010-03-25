<?php
/**
 *	Deserializer for XML into a Data Object.
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
 *	@package		xml.dom
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.12.2005
 *	@version		$Id$
 */
import( 'de.ceus-media.xml.dom.Parser' );
/**
 *	Deserializer for XML into a Data Object.
 *	@category		cmClasses
 *	@package		xml.dom
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.12.2005
 *	@version		$Id$
 *	@todo			rewrite, use ObjectFactory
 */
class XML_DOM_ObjectDeserializer
{
	/**
	 *	Builds Object from XML of a serialized Object.
	 *	@access		public
	 *	@param		string		$xml			XML String of a serialized Object
	 *	@return		mixed
	 */
	public static function deserialize( $xml, $strict = TRUE )
	{
		$parser	= new XML_DOM_Parser();
		$tree	= $parser->parse( $xml );
		$class	= $tree->getAttribute( 'class' );
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
	 *	@return		string
	 */
	protected static function deserializeVarsRec( $children, &$element )
	{
		foreach( $children as $child )
		{
			$name		= $child->getAttribute( 'name' );
			$vartype	= $child->getNodeName();
			if( is_object( $element ) )
			{
				if( !isset( $element->$name ) )
					$element->$name	= NULL;
				$pointer	=& $element->$name;
			}
			else
			{
				if( !isset( $element->$name ) )
					$element[$name]	= NULL;
				$pointer	=& $element[$name];
			}
			
			switch( $vartype )
			{
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
					$pointer	= array();
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
?>