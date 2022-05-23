<?php
/**
 *	Base Object with options.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			18.07.2005
 */
namespace CeusMedia\Common\ADT;

use InvalidArgumentException;
use OutOfRangeException;

/**
 *	Base Object with options.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@implements		ArrayAccess
 *	@implements		Countable
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			18.07.2005
 */
class OptionObject implements \ArrayAccess, \Countable
{
	/**	@var		array		$options		Associative Array of options */
	protected $options	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$defaults		Associative Array of options
	 *	@param		array		$settings		...
	 *	@throws		InvalidArgumentException	if given map is not an array
	 *	@throws		InvalidArgumentException	if map key is an integer since associative arrays are prefered
	 *	@todo		allow integer map keys for eg. options defined by constants (which point to integer values, of course)
	 *	@return		void
	 */
	public function __construct( array $defaults = array(), array $settings = array() )
	{
		foreach( $defaults as $key => $value )
			if( is_int( $key ) )
				throw new InvalidArgumentException( 'Default options must be an associative array of pairs.' );
		foreach( $settings as $key => $value )
			if( is_int( $key ) )
				throw new InvalidArgumentException( 'Settings must be an associative array of pairs.' );

		$this->options	= array_merge( $defaults, $settings );
	}

	/**
	 *	Removes all set Options.
	 *	@access		public
	 *	@return		bool
	 */
	public function clearOptions(): bool
	{
		if( !count( $this->options ) )
			return FALSE;
		$this->options	= array();
		return TRUE;
	}

	/**
	 *	Returns the Number of Options.
	 *	@access		public
	 *	@return		int
	 */
	public function count(): int
	{
		return count( $this->options );
	}

	/**
	 *	Declares a Set of Options.
	 *	@access		public
	 *	@param		array		$optionKeys		List of Option Keys
	 *	@return		void
	 */
	public function declareOptions( array $optionKeys = array() )
	{
		foreach( $optionKeys as $key ){
			if( !is_string( $key ) )
				throw new InvalidArgumentException( 'Option Keys must be an Array List of Strings.' );
			$this->options[$key]	= NULL;
		}
	}

	/**
	 *	Returns an Option Value by Option Key.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@param		bool		$throwException	Flag: throw Exception is key is not set, NULL otherwise
	 *	@throws		OutOfRangeException			if key is not set and $throwException is true
	 *	@return		mixed
	 */
	public function getOption( string $key, bool $throwException = TRUE )
	{
		if( !$this->hasOption( $key ) )
		{
			if( $throwException )
				throw new OutOfRangeException( 'Option "'.$key.'" is not defined' );
			return NULL;
		}
		return $this->options[$key];
	}

	/**
	 *	Returns associative Array of all set Options.
	 *	@access		public
	 *	@return		array
	 */
	public function getOptions(): array
	{
		return $this->options;
	}

	/**
	 *	Indicated whether a option is set or not.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@return		bool
	 */
	public function hasOption( string $key ): bool
	{
		return array_key_exists( (string) $key, $this->options );
	}

	/**
	 *	Indicates whether a Key is existing.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@return		bool
	 */
	public function offsetExists( $key )
	{
		return $this->hasOption( $key );
	}

	/**
	 *	Return a Value of Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key			Option key
	 *	@return		mixed
	 */
	public function offsetGet( $key )
	{
		return $this->getOption( $key );
	}

	/**
	 *	Sets Value of Key in Dictionary.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@param		string		$value			Option Value
	 *	@return		void
	 */
	public function offsetSet( $key, $value )
	{
		return $this->setOption( $key, $value );
	}

	/**
	 *	Removes a Value from Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@return		void
	 */
	public function offsetUnset( $key )
	{
		return $this->removeOption( $key );
	}

	/**
	 *	Removes an option by its key.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@return		bool
	 */
	public function removeOption( string $key ): bool
	{
		if( !$this->hasOption( $key ) )
			return FALSE;
		unset( $this->options[$key] );
		return TRUE;
	}

	/**
	 *	Sets an options.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@param		mixed		$value			Option Value
	 *	@return		bool
	 */
	public function setOption( string $key, $value ): bool
	{
		if( isset( $this->options[$key] ) && $this->options[$key] === $value )
			return FALSE;
		$this->options[$key] = $value;
		return TRUE;
	}
}
