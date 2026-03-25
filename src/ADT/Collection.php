<?php

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT;

use ArrayIterator;
use CeusMedia\Common\ADT\Collection\Dictionary;
use CeusMedia\Common\ADT\Collection\LevelMap;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Collection implements Countable, IteratorAggregate
{
	/** @var array $items */
	protected array $items;

	public function __construct( array $list = [] )
	{
		$this->items	= $list;
	}

	/**
	 *	Returns number of items within collection.
	 *	@return		int
	 */
	public function count(): int
	{
		return count( $this->items );
	}

	/**
	 *	Applies function to filter collection.
	 *	This means, the original collection will be modified.
	 *	The given function needs to indicate, which item is to be kept.
	 *	@param		callable		$function	Filter function to apply to collection items
	 *	@param		int				$mode		One of (0,ARRAY_FILTER_USE_KEY,ARRAY_FILTER_USE_BOTH), default: 0
	 *	@return		self
	 */
	public function filter( callable $function, int $mode = 0 ): self
	{
		$this->items	= array_filter( $this->items, $function, $mode );
		return $this;
	}

	/**
	 *	Applies function to filter collection.
	 *	This means, the original collection will be modified.
	 *	The given function needs to indicate, which item is to be kept.
	 *	Supports method chaining.
	 *	@param		callable		$function	Filter function to apply to collection items
	 *	@param		int				$mode		One of (0,ARRAY_FILTER_USE_KEY,ARRAY_FILTER_USE_BOTH), default: 0
	 *	@return		self
	 */
	public function filterToCopy( callable $function, int $mode = 0 ): self
	{
		return new Collection( array_filter( $this->items, $function, $mode ) );
	}

	/**
	 *	Returns collection items as array.
	 *	@return		array
	 */
	public function getArray(): array
	{
		return $this->items;
	}

	/**
	 *	Returns collection items as dictionary (which implements ArrayAccess, Countable and Iterator).
	 *	@return		Dictionary
	 */
	public function getDictionary(): Dictionary
	{
		return new Dictionary( $this->items );
	}

	/**
	 *	Returns collection items as traversable iterator.
	 *	@return		Traversable
	 */
	public function getIterator(): Traversable
	{
		return new ArrayIterator( $this->items );
	}

	/**
	 *	Returns collection items as traversable iterator.
	 *	@param		string		$divider		Level dividing sign
	 *	@return		LevelMap
	 */
	public function getLevelMap( string $divider = '.' ): LevelMap
	{
		return new LevelMap( $this->items, $divider );
	}

	/**
	 *	Returns list of item keys.
	 *	@return		array
	 */
	public function getKeys(): array
	{
		return array_keys( $this->items );
	}

	/**
	 *	Returns list of item values.
	 *	@return		array
	 */
	public function getValues(): array
	{
		return array_values( $this->items );
	}

	/**
	 *	Applies a mapping function to collection items in order to modify them.
	 *	This means, the callback function needs to pass item by reference to apply a modification.
	 *	Thus, will return the original collection after modifications.
	 *	@param		callable	$function	Mapping function to apply to collection items
	 *	@param		mixed		$arg		Optional argument for callback function
	 *	@return		self
	 */
	public function map( callable $function, mixed $arg = null ): self
	{
		array_walk( $this->items, $function, $arg );
		return $this;
	}

	/**
	 *	Applies mapping function to translate collection into a new collection.
	 *	This means, the original collection will NOT be modified.
	 *	Thus, will return a clone with modifications.
	 *	@param		callable	$function	Mapping function to apply to collection items
	 *	@return		self
	 */
	public function mapToCopy( callable $function ): self
	{
		return new Collection( array_map( $function, $this->items ) );
	}

	public function raise( int $index, int $steps = 1 ): self
	{
		$steps	= abs( $steps );
		if( $steps && $index > 0 && $index < count( $this ) ){
			$swap	= $this->items[$index - 1];
			$this->items[$index - 1]	= $this->items[$index];
			$this->items[$index]		= $swap;
			$this->raise( --$index, --$steps );
		}
		return $this;
	}

	public function sink( int $index, int $steps = 1 ): self
	{
		$steps	= abs( $steps );
		if( $steps && $index >= 0 && $index < count( $this ) -1 ){
			$swap	= $this->items[$index + 1];
			$this->items[$index + 1]	= $this->items[$index];
			$this->items[$index]		= $swap;
			$this->sink( ++$index, --$steps );
		}
		return $this;
	}
}
