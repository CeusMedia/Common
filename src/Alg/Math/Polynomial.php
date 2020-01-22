<?php
/**
 *	Polynomial.
 *
 *	Copyright (c) 2007-2018 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 */
/**
 *	Polynomial.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Math
 *	@uses			Alg_Math_Formula
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 */
class Alg_Math_Polynomial
{
	/**	@var		array		$_coefficients		Array of coefficients starting with highest potency */
	protected $coefficients = array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$coefficients		Array of coefficients starting with highest potenc
	 *	@return		void
	 */
	public function __construct( $coefficients = array() )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Math"',
			 	'https://packagist.org/packages/ceus-media/math'
			) );
		if( is_array( $coefficients ) && count( $coefficients ) )
			$this->setCoefficients( $coefficients );
	}

	/**
	 *	Returns Polynomial as a representative string.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		$string	= "";
		if( $this->getRank() == 0)
			trigger_error("Polynomial: No polynomial coefficients given", E_USER_ERROR );
		for( $i = $this->getRank() - 1; $i >= 0; $i-- )
		{
			$a = $this->coefficients[$i];
			if( $a != 0 )
			{
				$sign = $this->getSign( $a );
				if( $i )
				{
					if( abs( $a ) == 1 )
					{
						if( $string || $a == -1 )
							$string	.= $sign;
					}
					else
						$string	.= $string ? $sign.abs( $a )."*" : $a."*";
					$string	.= "x<sup>".$i."</sup>";
				}
				else
					$string	.= $string ? $sign.abs( $a ) : $a;
			}
		}
		return $string;
	}

	/**
	 *	Returns Formula Object of Polynomial.
	 *	@access		public
	 *	@return		Alg_Math_Formula
	 *	@since		15.09.2006
	 */
	public function getFormula()
	{
		$expression	= "";
		for( $i = $this->getRank() - 1; $i >= 0; $i-- )
		{
			$a = $this->coefficients[$i];
			if( $a != 0 )
			{
				$sign = $this->getSign( $a );
				if( $i )
				{
					if( abs( $a ) == 1 )
					{
						if( $expression || $a == -1 )
							$expression	.= $sign;
					}
					else
						$expression	.= $expression ? $sign.abs( $a )."*" : $a."*";
					$expression	.= "pow(x,".$i.")";
				}
				else
					$expression	.= $expression ? $sign.abs( $a ) : $a;
			}
		}
		$formula	= new Alg_Math_Formula( $expression, "x" );
		return $formula;
	}

	/**
	 *	Returns the Rank of the Polynomial.
	 *	@access		public
	 *	@return		int
	 */
	public function getRank()
	{
		return count( $this->coefficients );
	}

	/**
	 *	Returns Sign of Coefficient.
	 *	@access		protected
	 *	@param		float			$value			Value to get Sign of
	 *	@return		string
	 *	@since		15.09.2006
	 */
	protected function getSign( $value )
	{
		if( (float)$value < 0 )
			return "-";
		return "+";
	}

	/**
	 *	Calculates value with a given x with Horner-Scheme and returns the value.
	 *	@access		public
	 *	@param		mixed		$x				X-Value
	 *	@return		mixed
	 */
	public function getValue( $x )
	{
		$y = 0;
		for( $i = $this->getRank() - 1; $i >= 0; $i-- )
			$y	= $this->coefficients[$i] + $y * $x;
		return $y;
	}

	/**
	 *	Sets the coefficients.
	 *	@access		public
	 *	@param		array		$coefficients		Array of coefficients starting with highest potency
	 *	@return		void
	 */
	public function setCoefficients( $coefficients )
	{
		$this->coefficients = $coefficients;
	}
}
