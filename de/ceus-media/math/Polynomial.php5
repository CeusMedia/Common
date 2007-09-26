<?php
import( 'de.ceus-media.math.Formula' );
/**
 *	Polynomial.
 *	@package		math
 *	@uses			Formula
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	@package		math
 *	@uses			Formula
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Polynomial
{
	/**	@var	array		$_coefficients		Array of coefficients starting with highest potency */
	var $_coefficients = array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array	$coefficients		Array of coefficients starting with highest potenc
	 *	@return		void
	 */
	public function __construct( $coefficients = array() )
	{
		if( is_array( $coefficients ) && count( $coefficients ) )
			$this->setCoefficients( $coefficients );
	}

	/**
	 *	Sets the coefficients.
	 *	@access		public
	 *	@param		array	$coefficients		Array of coefficients starting with highest potency
	 *	@return		void
	 */
	function setCoefficients( $coefficients )
	{
		$this->_coefficients = $coefficients;
	}

	/**
	 *	Calculates value with a given x with Horner-Scheme and returns the value.
	 *	@access		public
	 *	@param		mixed	$x				X-Value
	 *	@return		mixed
	 */
	function getValue( $x )
	{
		$y = 0;
		for( $i = $this->getRank() - 1; $i >= 0; $i-- )
			$y	= $this->_coefficients[$i] + $y * $x;
		return $y;
	}
	
	/**
	 *	Returns the Rank of the Polynomial.
	 *	@access		public
	 *	@return		int
	 */
	function getRank()
	{
		return count( $this->_coefficients );
	}

	/**
	 *	Returns Polynomial as a representative string.
	 *	@access		public
	 *	@return		string
	 */
	function toString()
	{
		$string	= "";
		if( $this->getRank() == 0)
			trigger_error("Polynomial: No polynomial coefficients given", E_USER_ERROR );
		for( $i = $this->getRank() - 1; $i >= 0; $i-- )
		{
			$a = $this->_coefficients[$i];
			if( $a != 0 )
			{
				$sign = $this->_getSign( $a );
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
	 *	@return		Formula
	 *	@since		15.09.2006
	 */
	function toFormula()
	{
		$expression	= "";
		for( $i = $this->getRank() - 1; $i >= 0; $i-- )
		{
			$a = $this->_coefficients[$i];
			if( $a != 0 )
			{
				$sign = $this->_getSign( $a );
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
		$formula	= new Formula( $expression, "x" );
		return $formula;
	}
	
	/**
	 *	Returns Sign of Coefficient.
	 *	@access		public
	 *	@param		float			$value			Value to get Sign of
	 *	@return		string
	 *	@since		15.09.2006
	 */
	function _getSign( $value )
	{
		if( (float)$value < 0 )
			return "-";
		return "+";
	}
}
?>