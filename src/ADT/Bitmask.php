<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Handler for bitmask.
 *
 *	Copyright (c) 2018-2023 Christian Würker (ceusmedia.de)
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
 *	@copyright		2018-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT;

/**
 *	Handler for bitmask.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

class Bitmask
{
	protected int $bits	= 0;

	public function __construct( int $bits = 0 )
	{
		$this->set( $bits );
	}

	public function add( int $bit ): self
	{
		$this->bits |= $bit;
		return $this;
	}

	public function get(): int
	{
		return $this->bits;
	}

	public function has( int $bit ): bool
	{
		return (bool)( $this->bits & $bit );
	}

	public function remove( int $bit ): self
	{
		$this->bits	^= $bit;
		return $this;
	}

	public function set( int $bits ): self
	{
		$this->bits	= $bits;
		return $this;
	}
}
