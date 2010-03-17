<?php
/**
 *	Registry Pattern Singleton Implementation to store Objects.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		adt
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2007
 *	@version		0.6
 */
/**
 *	Registry Pattern Singleton Implementation to store Objects.
 *	@category		cmClasses
 *	@package		adt
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2007
 *	@version		0.6
 */
class ADT_Registry
{
	protected static $instance	= NULL;
	protected $poolKey	= "REFERENCES";

	/**
	 *	Constructor.
	 *	@access		protected
	 *	@return		void
	 */
	protected function __construct( $poolKey )
	{
		$this->poolKey = $poolKey;
		if( !( isset( $GLOBALS[$this->poolKey] ) && is_array( $GLOBALS[$this->poolKey] ) ) )
			$GLOBALS[$this->poolKey]	= array();
	}

	/**
	 *	Denies to clone Registry.
	 *	@access		private
	 *	@return		void
	 */
	private function __clone() {}
	
	/**
	 *	Cleares registered Object.
	 *	@access		public
	 *	@return		void
	 */
	public function clear()
	{
		foreach( $GLOBALS[$this->poolKey] as $key => $value )
			unset( $GLOBALS[$this->poolKey][$key] );
	}

	/**
	 *	Returns Instance of Registry.
	 *	@access		public
	 *	@static
	 *	@return		Registry
	 */
	public static function getInstance( $poolKey = "REFERENCES" )
	{
		if( self::$instance === NULL )
			self::$instance	= new self( $poolKey );
		return self::$instance;		
	}

	/**
	 *	Returns registered Object.
	 *	@access		public
	 *	@param		string		$key		Registry Key of registered Object
	 *	@return		mixed
	 */
	public function & get( $key )
	{
		if( !isset( $GLOBALS[$this->poolKey][$key] ) )
			throw new InvalidArgumentException( 'No Object registered with Key "'.$key.'"' );
		return $GLOBALS[$this->poolKey][$key];
	}
	
	/**
	 *	Returns registered Object statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$key		Registry Key of registered Object
	 *	@return		mixed
	 */
	public static function & getStatic( $key )
	{
		return self::getInstance()->get( $key );
	}
	
	/**
	 *	Indicates whether a Key is registered.
	 *	@access		public
	 *	@param		string		$key		Registry Key to be checked
	 *	@return		bool
	 */
	public function has( $key )
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
	public function set( $key, &$value, $overwrite = false )
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
	public function remove( $key )
	{
		if( !isset( $GLOBALS[$this->poolKey][$key] ) )
			return false;
		unset( $GLOBALS[$this->poolKey][$key] );
		return true;	
	}
}  
?>