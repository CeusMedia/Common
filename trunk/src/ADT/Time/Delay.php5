<?php
/**
 *
 *	Copyright (c) 2010 Christian Würker (ceusmedia.com)
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
 *	@package		ADT.Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	@category		cmClasses
 *	@package		ADT.Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class ADT_Time_Delay
{
	protected $seconds;
	protected $time;
	protected $numberRuns	= 0;
	protected $numberChecks	= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$msec		Delay in milliseconds
	 *	@return		void
	 */
	public function __construct( $msec )
	{
		if( !is_int( $msec ) )
			throw new InvalidArgumentException( 'Delay must be integer' );
		if( $msec < 1 )
			throw new InvalidArgumentException( 'Delay must be at least 1 ms' );
		$this->seconds	= (int) $msec / 1000;
		$this->restart();
	}

	/**
	 *	Returns the number of checks.
	 *	@access		public
	 *	@return		int						Number of checks
	 */
	public function getNumberChecks()
	{
		return $this->numberChecks;
	}

	/**
	 *	Returns the number of runs.
	 *	@access		public
	 *	@return		int						Number of runs
	 */
	public function getNumberRuns()
	{
		return $this->numberRuns;
	}
	
	/**
	 *	Returns set start timestamp.
	 *	@access		public
	 *	@return		float					Timestamp of start
	 */
	public function getStartTime()
	{
		return $this->time;
	}

	/**
	 *	Indicates whether Delay still has not passed.
	 *	@access		public
	 *	@return		bool
	 */
	public function isActive()
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
	public function isReached()
	{
		return !$this->isActive();
	}

	/**
	 *	Reset the start to 'now'.
	 *	@access		public
	 *	@param		bool		$force		Flag: reset also if Delay is still active
	 *	@return		float					Timestamp of start just set
	 */
	public function restart( $force = FALSE )
	{
		if( $this->isActive() && !$force )
			throw RuntimeException( 'Delay is still active' );
		$this->time = microtime( TRUE );
		$this->numberRuns++;
		return $this->getStartTime();
	}
}
?>