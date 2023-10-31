<?php
namespace CeusMedia\Common\ADT;

use Countable;
use InvalidArgumentException;

class Collection implements Countable
{
	public array $list;

	public function __construct( array $list = [] )
	{
		$this->list		= $list;
	}

	public function count(): int
	{
		return count( $this->list );
	}

	public function getKeys(): array
	{
		return array_keys( $this->list );
	}

	public function getValues(): array
	{
		return array_values( $this->list );
	}

	public function raise( int $index, int $steps = 1 ): self
	{
		$steps	= abs( $steps );
		if( $steps && $index > 0 && $index < count( $this ) ){
			$swap	= $this->list[$index - 1];
			$this->list[$index - 1]	= $this->list[$index];
			$this->list[$index]		= $swap;
			$this->raise( --$index, --$steps );
		}
		return $this;
	}

	public function sink( int $index, int $steps = 1 ): self
	{
		$steps	= abs( $steps );
		if( $steps && $index >= 0 && $index < count( $this ) -1 ){
			$swap	= $this->list[$index + 1];
			$this->list[$index + 1]	= $this->list[$index];
			$this->list[$index]		= $swap;
			$this->sink( ++$index, --$steps );
		}
		return $this;
	}
}
