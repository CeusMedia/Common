<?php
/**
 *	Clock implementation with Lap Support.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		alg.time
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Clock implementation with Lap Support.
 *	@category		cmClasses
 *	@package		alg.time
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class Alg_Time_Clock
{
	/**	@var	string		$microtimeStart		Microtime at the Start */
	protected $microtimeStart;
	/**	@var	string		$microtimeLap		Time in micro at the end of the last since start */
	protected $microtimeLap;
	/**	@var	string		$microtimeStop		Microtime at the End */
	protected $microtimeStop;
	/**	@var	array		$laps				Array of Lap Times */
	protected $laps			= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->start();
	}

	public function getLaps()
	{
		return $this->laps;	
	}

	/**
	 *	Starts the watch.
	 *	@access		public
	 *	@return		void
	 */
	public function start()
	{
		$this->microtimeStart = microtime();
	}

	/**
	 *	Stops the watch and return the time difference between start and stop.
	 *	@access		public
	 *	@param		int		$base		Time Base ( 0 - sec | 3 - msec | 6 - µsec)
	 *	@param		int		$round		Numbers after dot
	 *	@return		string
	 */
	public function stop( $base = 3, $round = 3 )
	{
		$this->microtimeStop 	= microtime();
		return $this->getTime( $base, $round );
	}

	public function stopLap( $base = 3, $round = 3, $label = NULL )
	{
		$microtimeLast	= $this->microtimeLap ? $this->microtimeLap : $this->microtimeStart;
		$microtimeNow	= microtime();

		$totalMicro		= round( $this->calculateTimeSpan( $this->microtimeStart, $microtimeNow ) * 1000000 );
		$timeMicro		= round( $this->calculateTimeSpan( $microtimeLast, $microtimeNow ) * 1000000 );
		
		$total			= round( $totalMicro * pow( 10, $base - 6 ), $round );
		$time			= round( $timeMicro * pow( 10, $base - 6 ), $round );
		
		$this->laps[]	= array(
			'time'			=> $time,
			'timeMicro'		=> $timeMicro,
			'total'			=> $total,
			'totalMicro'	=> $totalMicro,
			'label'			=> $label
		);
		$this->microtimeLap	= $microtimeNow;
		return $time;
	}

	/**
	 *	Calculates the time difference between start and stop in microseconds.
	 *	@access		public
	 *	@param		int		$base		Time Base ( 0 - sec | 3 - msec | 6 - µsec)
	 *	@param		int		$round		Numbers after dot
	 *	@return		string
	 */
	public function getTime( $base = 3, $round = 3 )
	{
		$time	= $this->calculateTimeSpan( $this->microtimeStart, $this->microtimeStop );
		$time	= $time * pow( 10, $base );
		$time	= round( $time, $round );
		return $time;
	}
	
	protected function calculateTimeSpan( $microtimeStart, $microtimeStop )
	{
		$start	= explode( ' ', $microtimeStart );
		$end	= explode( ' ', $microtimeStop );
		$sec	= $end[1] - $start[1];
		$msec	= $end[0] - $start[0];
		$time	= (float) $sec + $msec;
		return $time;
	}
}
?>