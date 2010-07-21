<?php
/**
 *	Base Object with options.
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
 *	@package		ADT
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.07.2005
 *	@version		$Id$
 */
/**
 *	Base Object with options.
 *	@category		cmClasses
 *	@package		ADT
 *	@implements		ArrayAccess
 *	@implements		Countable
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.07.2005
 *	@version		$Id$
 */
class ADT_OptionObject implements ArrayAccess, Countable
{
	/**	@var		array		$options		Associative Array of options */
	protected $options	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$options		Associative Array of options
	 *	@throws		InvalidArgumentException	if given map is not an array
	 *	@throws		InvalidArgumentException	if map key is an integer since associative arrays are prefered
	 *	@todo		allow integer map keys for eg. options defined by constants (which point to integer values, of course)
	 *	@return		void
	 */
	public function __construct( $options = array() )
	{
		if( !is_array( $options ) )
			throw new InvalidArgumentException( 'Initial Options must be an Array or Pairs.' );

		foreach( $options as $key => $value )
		{
			if( is_int( $key ) )
			{
				throw new InvalidArgumentException( 'Initial Options must be an associative Array of Pairs.' );
			}
		}
		$this->options	= array();
		foreach( $options as $key => $value )
			$this->options[$key] = $value;
	}

	/**
	 *	Removes all set Options.
	 *	@access		public
	 *	@return		bool
	 */
	public function clearOptions()
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
	public function count()
	{
		return count( $this->options );
	}
	
	/**
	 *	Declares a Set of Options.
	 *	@access		public
	 *	@param		array		$optionKeys		List of Option Keys
	 *	@return		void
	 */
	public function declareOptions( $optionKeys = array() )
	{
		if( !is_array( $optionKeys ) )
			throw new InvalidArgumentException( 'Option Keys must be an Array.' );
		foreach( $optionKeys as $key )
		{
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
	public function getOption( $key, $throwException = TRUE )
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
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 *	Indicated whether a option is set or not.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@return		bool
	 */
	public function hasOption( $key )
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
	public function removeOption( $key )
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
	public function setOption( $key, $value )
	{
		if( isset( $this->options[$key] ) && $this->options[$key] === $value )
			return FALSE;
		$this->options[$key] = $value;
		return TRUE;
	}
}
?>