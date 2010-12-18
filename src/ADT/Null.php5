<?php
/**
 *	Null Object (Design Pattern) Implementation.
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Null Object (Design Pattern) Implementation.
 *	@category		cmClasses
 *	@package		ADT
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class ADT_Null implements Countable, Renderable, ArrayAccess
{
	/**	@var	ADT_Null		$instance		Singleton instance of ADT_Null */
	protected static $instance	= NULL;
	
	/**
	 *	Cloning is disabled.
	 *	@access		private
	 *	@return		void
	 */
	private function __clone() {}
	
	/**
	 *	Constructor, disabled.
	 *	@access		protected
	 *	@return		void
	 */
	protected function __construct() {}

	/**
	 *	Returns an empty string.
	 *	@access		public
	 *	@return		string		Empty string, always
	 */

	public function __toString()
	{
		return '';
	}

	/**
	 *	Returns single instance statically.
	 *	@access		public
	 *	@static
	 *	@return		ADT_Null	Single instance
	 */
	public static function getInstance()
	{
		if( !self::$instance )
			self::$instance	= new ADT_Null;
		return self::$instance;
	}

	/**
	 *	Implements interface Countable and returns always 0.
	 *	@access		public
	 *	@return		integer		0, always
	 */
	public function count()
	{
		return 0;
	}

	/**
	 *	Implements interface ArrayAccess and returns always FALSE.
	 *	@access		public
	 *	@return		boolean			FALSE, always
	 */
	public function offsetExists( $key )
	{
		return FALSE;
	}


	/**
	 *	Implements interface ArrayAccess and returns always self instance.
	 *	@access		public
	 *	@return		ADT_Null		Null object, infact self
	 */
	public function offsetGet( $key )
	{
		return self;
	}


	/**
	 *	Implements interface ArrayAccess and returns always FALSE.
	 *	@access		public
	 *	@return		boolean			FALSE, always
	 */
	public function offsetSet( $key, $value )
	{
		return FALSE;
	}


	/**
	 *	Implements interface ArrayAccess and returns always FALSE.
	 *	@access		public
	 *	@return		boolean			FALSE, always
	 */
	public function offsetUnset( $key )
	{
		return FALSE;
	}

	/**
	 *	Implements interface Renderable and returns always NULL.
	 *	@access		public
	 *	@return		string			Empty string, always
	 */
	public function render()
	{
		return '';
	}
}
?>