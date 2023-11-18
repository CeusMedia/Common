<?php

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT;

use Countable;
use InvalidArgumentException;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
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
