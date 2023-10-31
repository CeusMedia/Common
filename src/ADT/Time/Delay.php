<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
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
 *	@package		CeusMedia_Common_ADT_Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\Time;

use RangeException;
use RuntimeException;

/**
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Delay
{
	protected float $seconds;
	protected float $time;
	protected int $numberRuns	= 0;
	protected int $numberChecks	= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$msec		Delay in milliseconds
	 *	@return		void
	 *	@throws		RangeException
	 */
	public function __construct( int $msec )
	{
		if( $msec < 1 )
			throw new RangeException( 'Delay must be at least 1 ms' );
		$this->seconds	= (float) $msec / 1000;
		$this->restart();
	}

	/**
	 *	Returns the number of checks.
	 *	@access		public
	 *	@return		int						Number of checks
	 */
	public function getNumberChecks(): int
	{
		return $this->numberChecks;
	}

	/**
	 *	Returns the number of runs.
	 *	@access		public
	 *	@return		int						Number of runs
	 */
	public function getNumberRuns(): int
	{
		return $this->numberRuns;
	}

	/**
	 *	Returns set start timestamp.
	 *	@access		public
	 *	@return		float					Timestamp of start
	 */
	public function getStartTime(): float
	{
		return $this->time;
	}

	/**
	 *	Indicates whether Delay still has not passed.
	 *	@access		public
	 *	@return		bool
	 */
	public function isActive(): bool
	{
		$this->numberChecks++;
		$time	= microtime( TRUE ) - $this->time;
		return $time < $this->seconds;
	}

	/**
	 *	Indicates whether Delay has passed.
	 *	@access		public
	 *	@return		bool
	 */
	public function isReached(): bool
	{
		return !$this->isActive();
	}

	/**
	 *	Reset the start to 'now'.
	 *	@access		public
	 *	@param		bool		$force		Flag: reset also if Delay is still active
	 *	@return		float					Timestamp of start just set
	 *	@throws		RuntimeException		if delay is already/still active
	 */
	public function restart( bool $force = FALSE ): float
	{
		if( $this->isActive() && !$force )
			throw new RuntimeException( 'Delay is still active' );
		$this->time = microtime( TRUE );
		$this->numberRuns++;
		return $this->getStartTime();
	}
}
