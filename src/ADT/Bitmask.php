<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Implementation of a bitmask.
 *
 *	Copyright (c) 2018-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2018-2025 Christian Würker
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
 *	@copyright		2018-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Bitmask implements \Stringable
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
	 *	@param		self|int		$bits
	 */
	public function __construct( self|int $bits = 0 )
	{
		$this->set( $bits );
	}

	/**
	 *	Implements Stringable interface.
	 *	@return		string
	 */
	public function __toString(): string
	{
		return (string) $this->get();
	}

	/**
	 *	Sets bits or a bit in bitmask.
	 *	@param		self|int		$bits
	 *	@return		static
	 */
	public function add( self|int $bits ): static
	{
		$this->bits |= ( is_object( $bits ) ? $bits->get() : $bits );
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
	 *	Checks whether a bit is or bits are set or not.
	 *	@param		self|int		$bits		Bit(s) to check
	 *	@return		bool
	 */
	public function has( self|int $bits ): bool
	{
		return (bool)( $this->bits & ( is_object( $bits ) ? $bits->get() : $bits ) );
	}

	/**
	 *	Removes a bit, bits or a bitmask from bitmask.
	 *	@param		self|int		$bits		Bit, bits or bitmask to remove
	 *	@return		static
	 */
	public function remove( self|int $bits ): static
	{
		$bits		= is_object( $bits ) ? $bits->get() : $bits;
		$this->bits	^= ( $this->bits & $bits );
		return $this;
	}

	/**
	 *	Sets bits of bitmask by integer.
	 *	@param		self|int		$bits
	 *	@return		static
	 */
	public function set( self|int $bits ): static
	{
		$this->bits	= ( is_object( $bits ) ? $bits->get() : $bits );
		return $this;
	}

	/**
	 *	Returns new bitmask with added bits.
	 *	@param		self|int		$bits
	 *	@return		static
	 */
	public function with( self|int $bits ): static
	{
		$mask	= clone $this;
		$mask->add( is_object( $bits ) ? $bits->get() : $bits );
		return $mask;
	}

	/**
	 *	Returns new bitmask with removed bits.
	 *	@param		self|int		$bits
	 *	@return		static
	 */
	public function without( self|int $bits ): static
	{
		$mask	= clone $this;
		$mask->remove( $bits );
		return $mask;
	}
}
