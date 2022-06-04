<?php
declare( strict_types = 1 );

/**
 *	JSON Implementation for building JSON Code.
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
 *	@package		CeusMedia_Common_ADT_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			14.05.2006
 */
namespace CeusMedia\Common\ADT\JSON;

use InvalidArgumentException;

/**
 *	JSON Implementation for building JSON Code.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			14.05.2006
 */
class Builder
{
	/**
	 *	Encodes Data into a representative String.
	 *	@access		public
	 *	@static
	 *	@param		mixed		$data			Data to be encoded
	 *	@return		string
	 */
	public static function encode( $data )
	{
		$builder	= new self();
		return $builder->get( NULL, $data );
	}

	/**
	 *	Escpapes Control Sings in String.
	 *	@access		private
	 *	@static
	 *	@param		string		$string			String to be escaped
	 *	@return		string
	 */
	private static function escape( $string )
	{
		$replace	= array(
			'\\'	=> '\\\\',
			'"'	=> '\"',
			'/'	=> '\/',
			"\b"	=> '\b',
			"\f"	=> '\f',
			"\n"	=> '\n',
			"\r"	=> '\r',
			"\t"	=> '\t',
			"\u"	=> '\u'
			);
		$string	= str_replace( array_keys( $replace ), array_values( $replace ), $string );
		return $string;
	}

	/**
	 *	Returns a representative String for a Data Pair.
	 *	@access		public
	 *	@param		string		$key			Key of Pair
	 *	@param		mixed		$value			Value of Pair
	 *	@param		string		$parent			Parent of Pair
	 *	@return		string
	 */
	public function get( $key, $value, $parent = NULL )
	{
		$type	= self::getType( $key, $value );
		switch( $type )
		{
			case 'object':
				$value	= '{'.self::loop( $value, $type ).'}';
				break;
			case 'array':
				$value	= '['.self::loop( $value, $type ).']';
				break;
			case 'number':
				$value	= $value;
				break;
			case 'string':
				$value	= '"'.self::escape( $value ).'"';
				break;
			case 'boolean':
				$value	= $value === TRUE ? 'true' : 'false';
				break;
			case 'null':
				$value	= 'null';
				break;
		}
		if( !is_null( $key ) && $parent != 'array' )
			$value	= '"'.$key.'":'.$value;
		return $value;
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Returns Data Type of Pair Value.
	 *	@access		private
	 *	@static
	 *	@param		string		$key			Key of Pair
	 *	@param		mixed		$value			Value of Pair
	 *	@return		string
	 */
	private static function getType( $key, $value )
	{
		if( is_object( $value ))
			$type	= 'object';
		elseif( is_array( $value ) )
			$type	= self::isAssoc( $value ) ? 'object' : 'array';
		elseif( is_int( $value ) || is_float( $value ) || is_double( $value ) )
			$type	= 'number';
		elseif( is_string( $value ) )
			$type	= 'string';
		elseif( is_bool( $value ) )
			$type	= 'boolean';
		elseif( is_null( $value ) )
			$type	= 'null';
		else
			throw new InvalidArgumentException( 'Variable "'.$key.'" is not a supported Type.' );
		return $type;
	}

	/**
	 *	Indicates whether a array is associative or not.
	 *	@access		private
	 *	@static
	 *	@param		array		$array			Array to be checked
	 *	@return		bool
	 */
	private static function isAssoc( $array )
	{
		krsort( $array, \SORT_STRING );
		return !is_numeric( key( $array ) );
	}

	/**
	 *	Loops through Data Array and returns a representative String.
	 *	@access		private
	 *	@static
	 *	@param		array		$array			Array to be looped
	 *	@param		string		$type			Data Type
	 *	@return		string
	 */
	private static function loop( $array, $type )
	{
		$builder	= new self();
		$output		= NULL;
		foreach( $array as $key => $value )
			$output	.= $builder->get( $key, $value, $type ).',';
		$output	= trim( $output, ',' );
		return $output;
	}
}
