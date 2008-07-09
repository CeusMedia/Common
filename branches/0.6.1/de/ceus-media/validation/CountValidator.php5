<?php
import( 'de.ceus-media.validation.Validator' );
/**
 *	@package	validation
 *	@extends	Validator
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	@package	validation
 *	@extends	Validator
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 *	@todo		Code Documentation
 */
class CountValidator extends Validator
{
	/**
	 *	Indicates wheter a Strings length is between a minimum and a maximum.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@param		int		$min			minimum length
	 *	@param		int		$max		maximum length
	 *	@return		bool
	 */
	public function hasLength( $string, $min = false, $max = false )
	{
		if( $min) $min = $this->hasMin( $string, $min );
		else $min = true;
		if( $max )
		{
			if( $min > $max )
				$max = false;
			else
				$max = $this->hasMax( $string, $max );
		}
		else $max = true;
		return ( $min && $max );
	}

	/**
	 *	Indicates wheter a Strings length smaller than a maximum.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@param		int		$max		maximum length
	 *	@return		bool
	 */
	public function hasMax( $string, $max )
	{
		$size = $this->getSize( $string );
		if( $size <= $max )
			return true;
		return false;
	}

	/**
	 *	Indicates wheter a Strings length is larger than a minimum.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@param		int		$min			minimum length
	 *	@return		bool
	 */
	public function hasMin( $string, $min )
	{
		$size = $this->getSize( $string );
		if( $size >= $min )
			return true;
		return false;
	}

	/**
	 *	Validates a String by a validation class and proves length with minimum and maximum.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@param		string	$string		Validation class
	 *	@param		int		$min			minimum length
	 *	@param		int		$max		maximum length
	 *	@return		bool
	 */
	public function validate( $string, $class, $min, $max )
	{
		$class_method = "is".strtoupper( $class );
		if( $class && method_exists( $this, $class_method ) )
			$valid = $this->$class_method( $string );
		else
			$valid = true;

		if( $min || $max )
			$length = $this->hasLength( $string, $min, $max );
		else
			$length = true;

		$is_valid = $valid && $length;
		return $is_valid;
	}
	
	public function validate2( $string, $feature, $value )
	{
		$valid = true;
		switch( $feature )
		{
			case 'class':
				$class_method = "is".strtoupper( $value );
				if( $value && method_exists( $this, $class_method ) )
					$valid = $this->$class_method( $string );
				break;
			case 'mandatory':
				$valid = $this->hasMin( $string, 1 );
				break;
			case 'minlength':
				$valid = $this->hasMin( $string, $value );
				break;
			case 'maxlength':
				$valid = $this->hasMax( $string, $value );
				break;
		}
		return $valid;
	}
}
?>