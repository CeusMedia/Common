<?php

/**
 *	Implementation of a priority queue using an array with sections.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_Collection
 *	@author			Chistian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
namespace CeusMedia\Common\ADT\Collection;

use OutOfBoundsException;

/**
 *	Implementation of a priority queue using an array with sections.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Collection
 *	@author			Chistian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class PriorityQueue implements \Countable
{
	protected int $defaultPriority;

	protected array $items = [];

	/**
	 *	Constructor.
	 *	Can receive list of initial items.
	 *	Can be used to set the default priority.
	 *
	 *	@param		array			$list					List of initial items
	 *	@param		int				$defaultPriority		Default priority to set, default: 5
	 */
	public function __construct( array $list = [], int $defaultPriority = 5 )
	{
		$this->setDefaultPriority( $defaultPriority );
		foreach( $list as $item )
			$this->enqueue( $item );
	}

	/**
	 *	Clears all queued items.
	 *	@access		public
	 *	@return		self
	 */
	public function clear(): self
	{
		$this->items = [];
		return $this;
	}

	/**
	 *	Returns number of queued items.
	 *	@return		int
	 */
	public function count(): int
	{
		return count( $this->items, COUNT_RECURSIVE ) - count( array_keys( $this->items ) );
	}

	/**
	 *	Returns the item with the highest priority after remove it from the queue.
	 *	@return mixed
	 */
	public function dequeue(): mixed
	{
		$priorities	= array_keys( $this->items );
		if( [] === $priorities )
			throw new OutOfBoundsException( 'Queue is empty' );
		ksort( $priorities );
		$result	= NULL;
		foreach( $priorities as $priority ){
			foreach( $this->items[$priority] as $item ){
				$item	= array_shift( $this->items[$priority] );
				if( [] === $this->items[$priority] )
					unset( $this->items[$priority] );
				$result	= $item;
				break 2;
			}
		}
		return $result;
	}

	/**
	 *	@param		mixed		$item
	 *	@param		int|float|NULL		$priority
	 *	@return		static
	 */
	public function enqueue( $item, int|float $priority = NULL ): static
	{
		$priority	= $priority ?? $this->defaultPriority;
		if( !array_key_exists( $priority, $this->items ) ){
			$this->items[$priority]	= [];
			ksort( $this->items );
		}
		$this->items[$priority][]	= $item;
		return $this;
	}

	/**
	 *	@param		int|NULL		$priority
	 *	@return		bool
	 */
	public function has( int $priority = NULL ): bool
	{
		if( NULL === $priority )
			return [] !== $this->items;
		if( array_key_exists( $priority, $this->items ) )
			return [] !== $this->items[$priority];
		return FALSE;
	}

	/**
	 *	Sets priority to assume when enqueueing items without priority explicitly given.
	 *	@param		int			$priority
	 *	@return		static
	 */
	public function setDefaultPriority( int $priority ): static
	{
		$this->defaultPriority	= $priority;
		return $this;
	}

	/**
	 *	Returns a list of priorities and their items.
	 *	@return		array
	 */
	public function toArray(): array
	{
		return $this->items;
	}

	/**
	 *	Returns a list of items (ordered by priority).
	 *	@return		array
	 */
	public function toList(): array
	{
		$list	= [];
		$priorities	= array_keys( $this->items );
		foreach( $priorities as $priority )
			foreach( $this->items[$priority] as $item )
				$list[]	= $item;
		return $list;
	}
}