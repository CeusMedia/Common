<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Registry Pattern Singleton Implementation to store Objects.
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
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT;

use InvalidArgumentException;

/**
 *	Registry Pattern Singleton Implementation to store Objects.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Registry
{
	public static string $defaultPoolKey	= "REFERENCES";

	/** @var self[] $instances */
	protected static array $instances		= [];

	protected string $poolKey;

	/**
	 *	Returns registered Object statically.
	 *	@access		public
	 *	@static
	 *	@param		string			$key		Registry Key of registered Object
	 *	@param		string|NULL		$poolKey			Default: see public static $defaultPoolKey
	 *	@return		mixed
	 */
	public static function & getStatic( string $key, ?string $poolKey = NULL )
	{
		return self::getInstance( $poolKey )->get( $key );
	}

	/**
	 *	Returns Instance of Registry.
	 *	@access		public
	 *	@static
	 *	@param		string|NULL		$poolKey		Default: see public static $defaultPoolKey
	 *	@return		Registry
	 */
	public static function getInstance( ?string $poolKey = NULL ): self
	{
		$poolKey	??= static::$defaultPoolKey;
		if( !isset( self::$instances[$poolKey] ) )
			self::$instances[$poolKey]	= new self( $poolKey );
		return self::$instances[$poolKey];
	}

	/**
	 *	Returns registered Object statically.
	 *	@access		public
	 *	@static
	 *	@param		string			$key			Registry Key of registered Object
	 *	@param		mixed			$value			Object to register
	 *	@param		bool			$overwrite		Flag: overwrite already registered Objects
	 *	@param		string|NULL		$poolKey		Default: see public static $defaultPoolKey
	 *	@return		mixed
	 */
	public static function setStatic( string $key, &$value, bool $overwrite = FALSE, ?string $poolKey = NULL )
	{
		self::getInstance( $poolKey )->set( $key, $value, $overwrite );
	}

	/**
	 *	Constructor.
	 *	@access		protected
	 *	@param		string		$poolKey
	 *	@return		void
	 */
	protected function __construct( string $poolKey )
	{
		$this->poolKey = $poolKey;
		if( !( isset( $GLOBALS[$this->poolKey] ) && is_array( $GLOBALS[$this->poolKey] ) ) )
			$GLOBALS[$this->poolKey]	= [];
	}

	/**
	 *	Clears registered Object.
	 *	@access		public
	 *	@return		void
	 */
	public function clear()
	{
		foreach( $GLOBALS[$this->poolKey] as $key => $value )
			unset( $GLOBALS[$this->poolKey][$key] );
	}

	/**
	 *	Returns registered Object.
	 *	@access		public
	 *	@param		string		$key		Registry Key of registered Object
	 *	@return		mixed
	 */
	public function & get( string $key )
	{
		if( !isset( $GLOBALS[$this->poolKey][$key] ) )
			throw new InvalidArgumentException( 'No object registered by key "'.$key.'"' );
		return $GLOBALS[$this->poolKey][$key];
	}


	/**
	 *	Indicates whether a Key is registered.
	 *	@access		public
	 *	@param		string		$key		Registry Key to be checked
	 *	@return		bool
	 */
	public function has( string $key ): bool
	{
		return array_key_exists( $key, $GLOBALS[$this->poolKey] );
	}

	/**
	 *	Registers Object.
	 *	@access		public
	 *	@param		string		$key		Registry Key of registered Object
	 *	@param		mixed		$value		Object to register
	 *	@param		bool		$overwrite	Flag: overwrite already registered Objects
	 *	@return		void
	 */
	public function set( string $key, &$value, bool $overwrite = FALSE )
	{
		if( isset( $GLOBALS[$this->poolKey][$key] ) && !$overwrite )
			throw new InvalidArgumentException( 'Element "'.$key.'" is already registered.' );
		$GLOBALS[$this->poolKey][$key]	=& $value;
	}

	/**
	 *	Removes a registered Object.
	 *	@access		public
	 *	@param		string		$key		Registry Key of registered Object
	 *	@return		bool
	 */
	public function remove( string $key ): bool
	{
		if( !isset( $GLOBALS[$this->poolKey][$key] ) )
			return false;
		unset( $GLOBALS[$this->poolKey][$key] );
		return true;
	}

	/**
	 *	Denies to clone Registry.
	 *	@access		private
	 *	@return		void
	 */
	private function __clone()
	{
	}
}
