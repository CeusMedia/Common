<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Clock implementation with Lap Support.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Time;

/**
 *	Clock implementation with Lap Support.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Clock
{
	/**	@var	float		$microTimeStart		Microtime at the Start */
	protected $microTimeStart;

	/**	@var	float		$microTimeLap		Time in micro at the end of the last since start */
	protected $microTimeLap;

	/**	@var	float		$microTimeStop		Microtime at the End */
	protected $microTimeStop;

	/**	@var	array		$laps				Array of Lap Times */
	protected $laps			= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->start();
	}

	protected static function calculateTimeSpan( float $microTimeStart, float $microTimeStop ): float
	{
		return $microTimeStop - $microTimeStart;
	}

	public function getLaps(): array
	{
		return $this->laps;
	}

	/**
	 *	Calculates the time difference between start and stop in microseconds.
	 *	@access		public
	 *	@param		int		$base		Time Base ( 0 - sec | 3 - msec | 6 - µsec)
	 *	@param		int		$round		Numbers after dot
	 *	@return		float
	 */
	public function getTime( int $base = 3, int $round = 3 ): float
	{
		$time	= $this->microTimeStop - $this->microTimeStart;
		$time	= $time * 10 ** $base;
		return round( $time, $round );
	}

	public function sleep( $seconds )
	{
		$this->usleep( (float) $seconds * 1000000 );
	}

	public function speed( $seconds )
	{
		$this->uspeed( (float) $seconds * 1000000 );
	}

	/**
	 *	Starts the watch.
	 *	@access		public
	 *	@return		void
	 */
	public function start()
	{
		$this->microTimeStart = microtime( TRUE );
	}

	/**
	 *	Stops the watch and return the time difference between start and stop.
	 *	@access		public
	 *	@param		int		$base		Time Base ( 0 - sec | 3 - msec | 6 - µsec)
	 *	@param		int		$round		Numbers after dot
	 *	@return		float
	 */
	public function stop( int $base = 3, int $round = 3 ): float
	{
		$this->microTimeStop 	= microtime( TRUE );
		return $this->getTime( $base, $round );
	}

	public function stopLap( int $base = 3, int $round = 3, ?string $label = NULL, ?string $description = NULL ): float
	{
		$microTimeLast	= $this->microTimeLap ?: $this->microTimeStart;
		$microTimeNow	= microtime( TRUE );

		$totalMicro		= round( ( $microTimeNow - $this->microTimeStart ) * 1000000 );
		$timeMicro		= round( ( $microTimeNow - $microTimeLast ) * 1000000 );

		$total			= round( $totalMicro * 10 ** ($base - 6), $round );
		$time			= round( $timeMicro * 10 ** ($base - 6), $round );

		$this->laps[]	= [
			'time'			=> $time,
			'timeMicro'		=> $timeMicro,
			'total'			=> $total,
			'totalMicro'	=> $totalMicro,
			'label'			=> $label,
			'description'	=> $description,
		];
		$this->microTimeLap	= $microTimeNow;
		return $time;
	}

	public function usleep( int $microseconds )
	{
		$seconds	= $microseconds / 1000000;
		if( ( microtime( TRUE ) - $this->microTimeStart ) >= $seconds )
			$this->microTimeStart	+= $seconds;
	}

	public function uspeed( int $microseconds )
	{
		$this->microTimeStart	-= $microseconds / 1000000;
	}
}
