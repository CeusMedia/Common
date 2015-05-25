<?php
/**
 *	Resolution of Formula Products within a compact Interval.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			24.04.2006
 *	@version		$Id$
 */
/**
 *	Resolution of Formula Products within a compact Interval.
 *	@category		cmClasses
 *	@package		Math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			24.04.2006
 *	@version		$Id$
 */
class Math_FormulaProduct
{
	/**	@var		Math_Formula			$formula		Formula */
	protected $formula;
	/**	@var		Math_CompactInterval	$interval		Interval */
	protected $interval;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Math_Formula			$formula		Formula within Product
	 *	@param		Math_CompactInterval	$interval		Interval of Product
	 *	@return		void
	 */
	public function __construct( $formula, $interval )
	{
		if( !is_a( $formula, 'Math_Formula' ) )
			throw new InvalidArgumentException( 'No Formula Object given.' );
		if( !is_a( $interval, 'Math_CompactInterval' ) )
			throw new InvalidArgumentException( 'No Interval Object given.' );
		$this->formula	= $formula;
		$this->interval	= $interval;
	}
	
	/**
	 *	Calculates Product of given Formula within given compact Interval and Parameters.
	 *	@access		public
	 *	@return		mixed
	 */
	public function calculate()
	{
		$arguments	= func_get_args();
		for( $i=$this->interval->getStart(); $i<=$this->interval->getEnd(); $i++ )
		{
			$params	= array( $i );
			foreach( $arguments as $argument )
				$params[]	= $argument;
			$value	= call_user_func_array( array( &$this->formula, 'getValue' ), $params );

			if( !isset( $product ) )
				$product	= $value;
			else
				$product	*= $value;
		}
		return $product;
	}
}
?>