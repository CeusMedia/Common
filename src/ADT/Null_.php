<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Null Object (Design Pattern) Implementation as Singleton.
 *
 *	Copyright (c) 2010-2023 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT;

use CeusMedia\Common\Renderable;
use ArrayAccess;
use Countable;

/**
 *	Null Object (Design Pattern) Implementation as Singleton.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Null_ implements Countable, Renderable, ArrayAccess
{
	/**	@var	Null_|NULL		$instance		Singleton instance of Null_ */
	protected static ?Null_ $instance			= NULL;

	/**
	 *	Answers all undefined method calls by returning this null object again.
	 *	@access		public
	 *	@param		string		$name			Method name - doesn't matter at all
	 *	@param		array		$arguments		List of arguments - also doesn't matter
	 *	@return		Null_
	 */
	public function __call( string $name, array $arguments = [] ): self
	{
		return $this;
	}

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
	 *	Answers all undefined members reads by returning this null object again.
	 *	@param		string		$name			Member name - doesn't matter
	 *	@return		Null_
	 */
	public function __get( string $name )
	{
		return $this;
	}

	/**
	 *	Answers all undefined members writes by just returning FALSE without storing anything.
	 *	@param		string		$name			Member name - doesn't matter
	 *	@param		mixed		$value			Member value - doesn't matter
	 *	@return		boolean		Always FALSE
	 */
	public function __set( string $name, $value )
	{
		return FALSE;
	}

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
	 *	@return		Null_	Single instance
	 */
	public static function getInstance(): self
	{
		if( !self::$instance )
			self::$instance	= new self;
		return self::$instance;
	}

	/**
	 *	Implements interface Countable and returns always 0.
	 *	@access		public
	 *	@return		integer		0, always
	 */
	public function count(): int
	{
		return 0;
	}

	/**
	 *	Implements interface ArrayAccess and returns always FALSE.
	 *	@access		public
	 *	@return		boolean			FALSE, always
	 */
	public function offsetExists( $offset ): bool
	{
		return FALSE;
	}


	/**
	 *	Implements interface ArrayAccess and returns always self instance.
	 *	@access		public
	 *	@return		Null_		Null object, in fact self
	 */
	public function offsetGet( $offset ): self
	{
		return $this;
	}


	/**
	 *	Implements interface ArrayAccess and returns always FALSE.
	 *	@access		public
	 *	@return		boolean			FALSE, always
	 */
	public function offsetSet( $offset, $value ): bool
	{
		return TRUE;
	}


	/**
	 *	Implements interface ArrayAccess and returns always FALSE.
	 *	@access		public
	 *	@return		boolean			FALSE, always
	 */
	public function offsetUnset( $offset ): bool
	{
		return TRUE;
	}

	/**
	 *	Implements interface Renderable and returns always NULL.
	 *	@access		public
	 *	@return		string			Empty string, always
	 */
	public function render(): string
	{
		return '';
	}
}
