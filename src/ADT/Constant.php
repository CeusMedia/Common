<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Sets and gets constant values.
 *	List all constants with a given prefix.
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
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT;

use InvalidArgumentException;
use RangeException;
use RuntimeException;

/**
 *	Sets and gets constant values.
 *	List all constants with a given prefix.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Constant
{
	/**
	 *	Returns the Value of a set Constant, throws Exception otherwise.
	 *	@access		public
	 *	@static
	 *	@param		string		$key		Name of Constant to return
	 *	@return		mixed
	 *	@throws		InvalidArgumentException
	 *	@todo		finish impl
	 */
	public static function get( string $key )
	{
		$key	= strtoupper( $key );
		if( self::has( $key ) )
			return constant( $key );
		throw new InvalidArgumentException( 'Constant "'.$key.'" is not set' );
	}

	/**
	 *	Returns a Map of defined Constants.
	 *	@access		public
	 *	@static
	 *	@param		string|NULL		$prefix			...
	 *	@param		boolean			$excludePrefix	Flag: ...
	 *	@return		array
	 *	@throws		InvalidArgumentException
	 */
	public static function getAll( ?string $prefix = NULL, bool $excludePrefix = FALSE ): array
	{
		$prefix	= strtoupper( $prefix );
		$length	= strlen( $prefix );
		if( $length	< 2 )
			throw new InvalidArgumentException( 'Prefix "'.$prefix.'" is to short.' );
		$map	= get_defined_constants();
		if( $prefix ){
			foreach( $map as $key => $value ){
				if( $key[0] !== $prefix[0] )
					unset( $map[$key] );
				else if( $key[1] !== $prefix[1] )
					unset( $map[$key] );
				else if( substr( $key, 0, $length ) !== $prefix )
					unset( $map[$key] );
#				remark( $prefix." - ".$key." - ".(int)isset( $map[$key] ) );
			}
		}
		if( $excludePrefix ){
			if( substr( $excludePrefix, 0, $length ) !== $prefix )
				$excludePrefix	= $prefix.$excludePrefix;
			foreach( $map as $key => $value ){
				if( substr( $key, 0, strlen( $excludePrefix ) ) === $excludePrefix )
					unset( $map[$key] );
			}
		}
		return $map;
	}

	public static function getKeyByValue( ?string $prefix, $value )
	{
		$constants	= static::getAll( $prefix );
		$list		= array();
		foreach( $constants as $constantKey => $constantValue )
			if( $constantValue === $value )
				$list[]	= $constantKey;
		if( count( $list ) === 0 ){
			$message	= 'Constant value "%s" is not defined within prefix "%s"';
			throw new RangeException( sprintf( $message, $value, $prefix ) );
		}
		if( count( $list ) > 1 ){
			$message	= 'Constant value "%s" is ambiguous within prefix "%s"';
			throw new RangeException( sprintf( $message, $value, $prefix ) );
		}
		return $list[0];
	}

	/**
	 *	Indicates whether a Constant has been set by its Name.
	 *	@access		public
	 *	@static
	 *	@param		string		$key		Name of Constant to check
	 *	@return		bool
	 */
	public static function has( string $key ): bool
	{
		$key	= strtoupper( $key );
		return defined( $key );
	}

	/**
	 *	Sets a Constant.
	 *	@access		public
	 *	@static
	 *	@param		string		$key		Name of Constant to set
	 *	@param		mixed		$value		Value of Constant to set
	 *	@param		bool		$strict		Flag: set only if unset
	 *	@return		bool
	 *	@throws		RuntimeException		if Constant has already been set
	 */
	public static function set( string $key, $value, bool $strict = TRUE ): bool
	{
		$key	= strtoupper( $key );
		if( defined( $key ) && $strict )
			throw new RuntimeException( 'Constant "'.$key.'" is already defined.' );
		return define( $key, $value );
	}
}
