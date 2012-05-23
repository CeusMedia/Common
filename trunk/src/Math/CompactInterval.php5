<?php
/**
 *	Compact Interval (closed on both sides).
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@package		Math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Compact Interval (closed on both sides).
 *	@category		cmClasses
 *	@package		Math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class Math_CompactInterval
{
	/**	@var		mixed		$end		End of Interval */
	protected $end;
	/**	@var		string		$name		Name of Interval (default I) */
	protected $name				= "I";
	/**	@var		mixed		$start		Start of Interval */
	protected $start;	
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed		$start		Start of Interval
	 *	@param		mixed		$end		End of Interval
	 */
	public function __construct( $start, $end, $name = NULL )
	{
		$this->setStart( $start );
		$this->setEnd( $end );
		if( $name )
			$this->name	= $name;
	}
	
	/**
	 *	Returns distance between Start and End.
	 *	@access		public
	 *	@param		mixed		$start		Start of Interval
	 *	@param		mixed		$end		End of Interval
	 */
	public function getDiameter()
	{
		return abs( $this->end - $this->start );	
	}

	/**
	 *	Returns Start of Interval.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getStart()
	{
		return $this->start;
	}
	
	/**
	 *	Returns End of Interval.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getEnd()
	{
		return $this->end;
	}
	
	/**
	 *	Sets Start of Interval.
	 *	@access		public
	 *	@param		mixed		$start		Start of Interval
	 *	@return		void
	 */
	public function setStart( $start )
	{
		if( $this->end && $start > $this->end )
			throw new InvalidArgumentException( 'Start of Interval cannot be greater than End.' );
		$this->start	= $start;	
	}
	
	/**
	 *	Sets End of Interval.
	 *	@access		public
	 *	@param		mixed		$end		End of Interval
	 *	@return		void
	 */
	public function setEnd( $end )
	{
		if( $this->start && $end < $this->start )
			throw new InvalidArgumentException( 'End of Interval cannot be lower than Start.' );
		$this->end	= $end;	
	}

	/**
	 *	Returns Interval as mathematical String.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		return $this->name."[".$this->start.";".$this->end."]";
	}
}
?>