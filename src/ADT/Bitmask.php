<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Implementation of a bitmask.
 *
 *	Copyright (c) 2018-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT;

/**
 *	Implementation of a bitmask.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Bitmask
{
	/** @var	int		$bits */
	protected int $bits	= 0;

	/**
	 *	Construct statically by an array of bits.
	 *	@param		array		$bits
	 *	@return		self
	 */
	public static function fromArray( array $bits = [] ): self
	{
		$instance	= new self();
		foreach( $bits as $bit )
			$instance->add( $bit );
		return $instance;
	}

	/**
	 *	Constructor.
	 *	@param int $bits
	 */
	public function __construct( int $bits = 0 )
	{
		$this->set( $bits );
	}

	/**
	 *	Sets a bit in bitmask.
	 *	@param		int		$bit
	 *	@return		self
	 */
	public function add( int $bit ): self
	{
		$this->bits |= $bit;
		return $this;
	}

	/**
	 *	Get all bits of bitmask as integer.
	 *	@return		int
	 */
	public function get(): int
	{
		return $this->bits;
	}

	/**
	 *	Checks whether bit is set or not.
	 *	@param		int		$bit		Bit to check
	 *	@return		bool
	 */
	public function has( int $bit ): bool
	{
		return (bool)( $this->bits & $bit );
	}

	/**
	 *	Removes bit from bitmask.
	 *	@param		int		$bit		Bit to remove
	 *	@return		self
	 */
	public function remove( int $bit ): self
	{
		$this->bits	^= $bit;
		return $this;
	}

	/**
	 *	Sets bits of bitmask by integer.
	 *	@param		int		$bits
	 *	@return		self
	 */
	public function set( int $bits ): self
	{
		$this->bits	= $bits;
		return $this;
	}
}
