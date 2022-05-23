<?php
/**
 *	JAVA like StringBuffer Implementation.
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
 */

namespace CeusMedia\Common\ADT;

use RangeException;

/**
 *	JAVA like StringBuffer Implementation.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class StringBuffer implements \Countable, \Iterator
{
	/**	@var		string		$buffer			internal String */
	private $buffer;

	/**	@var		int			$position		Iterator position */
	private $position = 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$buffer			initial String in StringBuffer
	 *	@return		void
	 */
	public function __construct( $buffer = '' )
	{
		$this->buffer = $buffer;
	}

	/**
	 *	Returns the Size of the String.
	 *	@access		public
	 *	@return		StringBuffer
	 */
	public function append( string $string ): self
	{
		$this->buffer	.= $string;
		return $this;
	}

	/**
	 *	Returns the Size of the String.
	 *	@access		public
	 *	@return		int
	 */
	public function count(): int
	{
		return strlen( $this->buffer );
	}

	/**
	 *	Returns  a Character at the current position.
	 *	@access		public
	 *	@return		string
	 */
	public function current(): string
	{
		return $this->buffer[$this->position];
	}

	/**
	 *	Deletes a Character at a given Position.
	 *	@access		public
	 *	@param		int			$position			Position to delete
	 *	@return		self
	 */
	public function deleteCharAt( int $position ): self
	{
		$string	= "";
		for( $i = 0; $i < $this->count(); $i++ )
			if( $position != $i )
				$string .= $this->buffer[$i];
		$this->buffer = $string;
		if( $position == $this->position )
			$this->position++;
		return $this;
	}

	/**
	 *	Returns the Character at a given Position.
	 *	@access		public
	 *	@param		int			$position			Position
	 *	@return		string
	 *	@throws		RangeException
	 */
	public function getCharAt( int $position ): string
	{
		if( !$this->valid() )
			throw new RangeException( 'Invalid position' );
		return $this->buffer[$position];
	}

	/**
	 *	Returns the current Position of the internal position.
	 *	@access		public
	 *	@return		int
	 */
	public function key(): int
	{
		return $this->position;
	}

	/**
	 *	Inserts a String at a given Position.
	 *	@access		public
	 *	@param		int			$position		Position to insert to
	 *	@param		string		$string			String to insert
	 *	@return		self
	 */
	public function insert( int $position, string $string ): self
	{
		if( $position <= $this->count() && $position >=0 ){
			if( $position < $this->position )
				$this->position = $this->position + strlen( $string );
			$left	= substr( $this->toString(), 0, $position );
			$right	= substr( $this->toString(), $position );
			$this->buffer = $left.$string.$right;
		}
		return $this;
	}

	public function next()
	{
		++$this->position;
	}

	/**
	 *	Resets buffer, position.
	 *	@access		public
	 *	@param		string		$buffer			new initial String in StringBuffer
	 *	@return		self
	 */
	public function reset( string $buffer = '' ): self
	{
		$this->buffer	= $buffer;
		$this->rewind();
		return $this;
	}

	/**
	 *	Resets position.
	 *	@access		public
	 *	@return		void
	 */
	public function rewind()
	{
		$this->position	= 0;
	}

	/**
	 *	Sets the Character at a given Position.
	 *	@access		public
	 *	@param		int			$position		Position to set to
	 *	@param		string		$character		Character to set
	 *	@return		self
	 */
	public function setCharAt( int $position, string $character ): self
	{
		if( $position <= $this->count() && $position >= 0 )
			$this->buffer[$position] = $character;
		return $this;
	}

	/**
	 *	Returns the current String.
	 *	@access		public
	 *	@return		string
	 */
	public function toString(): string
	{
		return $this->buffer;
	}

	public function valid(): bool
	{
		return isset( $this->buffer[$this->position] );
	}
}
