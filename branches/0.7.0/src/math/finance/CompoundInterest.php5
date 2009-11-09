<?php
/**
 *	Calculator for Compound Interest.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		math.finance
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			19.12.2007
 *	@version		0.1
 */
/**
 *	Calculator for Compound Interest.
 *	@category		cmClasses
 *	@package		math.finance
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			19.12.2007
 *	@version		0.1
 */
class Math_Finance_CompoundInterest
{
	/**	@var		float		$amount			Amount to calculate with */
	protected $amount;
	/**	@var		float		$interest		Interest per Period */
	protected $interest;
	/**	@var		float		$period			Number of Periods */
	protected $periods;

	/**
	 *	Calculates Present Amount from Future Amount statically.
	 *	@access		public
	 *	@static
	 *	@param		float		$amount			Amount to calculate with
	 *	@param		float		$interest		Interest per Period
	 *	@param		int			$periods		Number of Periods
	 *	@return		float
	 */
	public static function calculateFutureAmount( $amount, $interest, $periods )
	{
		if( (int) $periods < 1 )
			throw new InvalidArgumentException( "Periods must be at least 1." );
		$result	= $amount * pow( ( 1 + $interest / 100 ), (int) $periods );
		return $result;
	}

	/**
	 *	Calculates Future Amount from Present Amount statically.
	 *	@access		public
	 *	@static
	 *	@param		float		$amount			Amount to calculate with
	 *	@param		float		$interest		Interest per Period
	 *	@param		int			$periods		Number of Periods
	 *	@return		float
	 */
	public static function calculatePresentAmount( $amount, $interest, $periods )
	{
		if( (int) $periods < 1 )
			throw new InvalidArgumentException( "Periods must be at least 1." );
		$result	= (float) $amount / pow( ( 1 + $interest / 100 ), (int) $periods );
		return $result;
	}

	/**
	 *	Calculates Future Amount from Present Amount statically.
	 *	@access		public
	 *	@static
	 *	@param		float		$amount			Amount to calculate with
	 *	@param		float		$interest		Interest per Period
	 *	@param		int			$periods		Number of Periods
	 *	@return		float
	 */
	public static function calculateInterest( $presentAmount, $futureAmount, $periods )
	{
		if( (int) $periods < 1 )
			throw new InvalidArgumentException( "Periods must be at least 1." );
		$i	= self::root( $futureAmount / $presentAmount, $periods ) - 1;
		$result	=  $i * 100;
		return $result;
	}
	
	/**
	 *	Calculates Periods needed to reach Future Amount from Present Amount statically using the 70+x rule.
	 *	@access		public
	 *	@static
	 *	@param		float		$amount			Amount to calculate with
	 *	@param		float		$interest		Interest per Period
	 *	@param		int			$periods		Number of Periods
	 *	@return		float
	 */
/*	public static function calculatePeriods( $presentAmount, $futureAmount, $interest )
	{
		$correct	= ( $interest - 2 ) / 3;
		$periods	= ( 70 + $correct ) / $interest;
		return $result;
	}*/
	
	/**
	 *	Returns Amount.
	 *	@access		public
	 *	@return		float
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 *	Returns Interest.
	 *	@access		public
	 *	@return		float
	 */
	public function getInterest()
	{
		return $this->interest;
	}

	/**
	 *	Sets Number of Periods.
	 *	@access		public
	 *	@return		int
	 */
	public function getPeriods()
	{
		return $this->periods;
	}

	/**
	 *	Calculates and returns Future Amount from Present Amount.
	 *	@access		public
	 *	@return		float
	 */
	public function getFutureAmount( $change = FALSE )
	{
		$result	= self::calculateFutureAmount( $this->amount, $this->interest, $this->periods );
		if( $change )
			$this->amount	= $result;
		return $result;
	}

	/**
	 *	Calculates and returns Interest from Future Amount.
	 *	@access		public
	 *	@return		float
	 */
	public function getInterestFromFutureAmount( $futureAmount, $change = FALSE )
	{
		$result	= self::calculateInterest( $this->amount, $futureAmount, $this->periods );
		if( $change )
			$this->periods	= round( $result );
		return $result;
	}

	/**
	 *	Calculates and returns Interest from Future Amount.
	 *	@access		public
	 *	@return		float
	 */
/*	public function getPeriodsFromFutureAmount( $futureAmount, $change = FALSE )
	{
		$result	= self::calculatePeriods( $this->amount, $futureAmount, $this->interest );
		if( $change )
			$this->periods	= ceil( $result );
		return $result;
	}
*/
	/**
	 *	Calculates and returns Present Amount from Future Amount.
	 *	@access		public
	 *	@return		float
	 */
	public function getPresentAmount( $change = FALSE )
	{
		$result	= self::calculatePresentAmount( $this->amount, $this->interest, $this->periods );
		if( $change )
			$this->amount	= $result;
		return $result;
	}

	/**
	 *	Calculates Root of Period Dimension.
	 *	@access		protected
	 *	@static
	 *	@param		float		$amount			Amount
	 *	@param		int			$periods		Number of Periods
	 *	@return		float
	 */
	protected static function root( $amount, $periods )
	{
		$sign	= ( $amount < 0 && $periods % 2 > 0 ) ? -1 : 1;
		$value	= pow( abs( $amount ), 1 / $periods );
		return $sign * $value;
	}

	/**
	 *	Sets Amount to calculate with.
	 *	@access		public
	 *	@param		float		$amount			Amount to calculate with
	 *	@return		void
	 */
	public function setAmount( $amount )
	{
		$this->amount	= $amount;
	}

	/**
	 *	Sets Interest per Period.
	 *	@access		public
	 *	@param		float		$interest		Interest per Period
	 *	@return		void
	 */
	public function setInterest( $interest )
	{
		$this->interest	= $interest;
	}

	/**
	 *	Sets Number of Periods.
	 *	@access		public
	 *	@param		int			$periods		Number of Periods
	 *	@return		void
	 */
	public function setPeriods( $periods )
	{
		if( (int) $periods < 1 )
			throw new InvalidArgumentException( "Periods must be at least 1." );
		$this->periods	= (int) $periods;
	}
}
?>