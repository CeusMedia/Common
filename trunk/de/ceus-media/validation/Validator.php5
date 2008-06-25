<?php
import( "de.ceus-media.validation.TypeValidator");
/**
 *	Validation of strings against validation classes.
 *	@package	validation
 *	@extends	TypeValidator
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@author		Michael Martin <michael.martin@ceus-media.de>
 *	@version		0.4
 */
/**
 *	Validation of strings against validation classes.
 *	@package	validation
 *	@extends	TypeValidator
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@author		Michael Martin <michael.martin@ceus-media.de>
 *	@version		0.4
 */
class Validator extends TypeValidator
{
	/**
	 *	Indicates wheter a string is of validation class 'alpha'.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@return		bool
	 */
	public function isALPHA( $string )
	{
		$size = $this->getSize( $string );
		for( $i=0; $i<$size; $i++ )
		{
			$sub_string = substr( $string, $i, 1 );
			$is_letter	= $this->isLETTER( $sub_string );
			if( !$is_letter )
				return false;
		}
		return true;
	}

	/**
	 *	Indicates wheter a string is of validation class 'alphanumeric'.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@return		bool
	 */
	public function isALPHANUMERIC( $string )
	{
		$size = $this->getSize( $string );
		for( $i=0; $i<$size; $i++ )
		{
			$sub_string = substr( $string, $i, 1 );
			$is_digit	= $this->isDIGIT	( $sub_string );
			$is_letter	= $this->isLETTER	( $sub_string );
			$is_valid = $is_digit || $is_letter;
			if( !$is_valid )
				return false;
		}
		return true;
	}

	/**
	 *	Indicates wheter a string is of validation class 'alphanumerichyphen'.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@return		bool
	 */
	public function isALPHANUMERICHYPHEN( $string )
	{
		$size = $this->getSize( $string );
		for( $i=0; $i<$size; $i++ )
		{
			$sub_string	= substr( $string, $i, 1 );
			$is_digit		= $this->isDIGIT	( $sub_string );
			$is_letter		= $this->isLETTER	( $sub_string );
			$is_hyphen	= $this->isHYPHEN	( $sub_string );
			$is_valid = $is_digit || $is_letter || $is_hyphen;
			if( !$is_valid )
				return false;
		}
		return true;
	}

	/**
	 *	Indicates wheter a string is of validation class 'alphanumericspace'.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@return		bool
	 */
	public function isALPHANUMERICSPACE( $string )
	{
		$size = $this->getSize( $string );
		for( $i=0; $i<$size; $i++ )
		{
			$sub_string	= substr( $string, $i, 1 );
			$is_digit		= $this->isDIGIT	( $sub_string );
			$is_letter		= $this->isLETTER	( $sub_string );
			$is_space	= $this->isSPACE	( $sub_string );
			$is_valid = $is_digit || $is_letter || $is_space;
			if( !$is_valid )
				return false;
		}
		return true;
	}

	/**
	 *	Indicates wheter a string is of validation class 'alphanumericsymbol'.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@return		bool
	 */
	public function isALPHANUMERICSYMBOL( $string )
	{
		$size = $this->getSize( $string );
		for( $i=0; $i<$size; $i++ )
		{
			$sub_string = substr( $string, $i, 1 );
			$is_digit		= $this->isDIGIT	( $sub_string );
			$is_letter		= $this->isLETTER	( $sub_string );
			$is_symbol	= $this->isSYMBOL	( $sub_string );
			$is_space	= $this->isSPACE	( $sub_string );
			$is_valid = $is_digit || $is_letter || $is_symbol || $is_space;
			if( !$is_valid )
				return false;
		}
		return true;
	}

	/**
	 *	Indicates wheter a string is of validation class 'dotnumeric'.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@return		bool
	 */
	public function isDOTNUMERIC( $string )
	{
		$found = false;
		$size = $this->getSize( $string );
		for( $i=0; $i<$size; $i++ )
		{
			$sub_string	= substr( $string, $i, 1 );
			$is_digit		= $this->isDIGIT	( $sub_string );
			$is_dot		= $this->isDOT	( $sub_string );
			$is_hyphen	= $this->isHYPHEN	( $sub_string );
			if( $is_dot)
			{
				if( $found )
					$is_dot = false;
				else
					$found = true;
			}
			$is_valid = $is_digit || $is_dot || $is_hyphen;
			if( !$is_valid )
				return false;
		}
		return true;
	}

	/**
	 *	Indicates wheter a string is of validation class 'floatnumeric'.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@return		bool
	 */
	public function isFLOATNUMERIC( $string )
	{
		$found = false;
		$size = $this->getSize( $string );
		for( $i=0; $i<$size; $i++ )
		{
			$sub_string	= substr( $string, $i, 1 );
			$is_digit		= $this->isDIGIT	( $sub_string );
			$is_dot		= $this->isDOT	( $sub_string );
			$is_comma	= $this->isCOMMA	( $sub_string );
			$is_hyphen	= $this->isHYPHEN	( $sub_string );
			if( $is_comma || $is_dot )
			{
				if( $found )
					$is_comma = $is_dot = false;
				else
					$found = true;
			}
			$is_valid = $is_digit || $is_dot || $is_comma || $is_hyphen;
			if( !$is_valid )
				return false;
		}
		return true;
	}

	/**
	 *	Indicates wheter a string is of validation class 'numeric'.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@return		bool
	 */
	public function isID( $string )
	{
		$size = $this->getSize( $string );
		for( $i=0; $i<$size; $i++ )
		{
			$sub_string = substr( $string, $i, 1 );
			$is_digit			= $this->isDIGIT			( $sub_string );
			$is_letter			= $this->isLETTER			( $sub_string );
			$is_dot			= $this->isDOT			( $sub_string );
			$is_hyphen		= $this->isHYPHEN			( $sub_string );
			$is_underscore	= $this->isUNDERSCORE	( $sub_string );
			$is_colon			= $this->isCOLON			( $sub_string );
			$is_at			= $this->isAT				( $sub_string );
			$is_valid = $is_digit || $is_letter || $is_dot || $is_hyphen || $is_underscore || $is_colon || $is_at;
			if( !$is_valid )
				return false;
		}
		return true;
	}

	/**
	 *	Indicates wheter a string is of validation class 'numeric'.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@return		bool
	 */
	public function isNUMERIC( $string )
	{
		$size = $this->getSize( $string );
		for( $i=0; $i<$size; $i++ )
		{
			$sub_string	= substr( $string, $i, 1 );
			$is_digit		= $this->isDIGIT	( $sub_string );
			$is_hyphen	= $this->isHYPHEN	( $sub_string );
			$is_valid = $is_digit || $is_hyphen;
			if( !$is_valid )
				return false;
		}
		return true;
	}

	/**
	 *	Indicates wheter a string is of validation class 'numericsymbol'.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@return		bool
	 */
	public function isNUMERICSYMBOL( $string )
	{
		$size = $this->getSize( $string );
		for( $i=0; $i<$size; $i++ )
		{
			$sub_string = substr( $string, $i, 1 );
			$is_digit		= $this->isDIGIT	( $sub_string );
			$is_hyphen	= $this->isHYPHEN	( $sub_string );
			$is_symbol	= $this->isSYMBOL	( $sub_string );
			$is_valid = $is_digit || $is_hyphen || $is_symbol;
			if( !$is_valid )
				return false;
		}
		return true;
	}

	/**
	 *	Indicates wheter a string is of validation class 'symbol'.
	 *	@access		public
	 *	@param		string	$string		String to be proved
	 *	@return		bool
	 */
	public function isSYMBOL( $string )
	{
		$size = $this->getSize( $string );
		if( $size )
		{
			for( $i=0; $i<$size; $i++ )
			{
				$sub_string = substr( $string, $i, 1 );
				$is_plus		= $this->isPLUS	( $sub_string );
				$is_dot		= $this->isDOT	( $sub_string );
				$is_comma	= $this->isCOMMA	( $sub_string );
				$is_hyphen	= $this->isHYPHEN	( $sub_string );
				$is_slash		= $this->isSLASH	( $sub_string );
				$is_valid = $is_plus || $is_dot || $is_comma || $is_hyphen || $is_slash;
				if( !$is_valid )
					return false;
			}
			return true;
		}
		return false;
	}

	/**
	 *	Returns the size of a string.
	 *	@access		public
	 *	@param		string	$string		String
	 *	@return		int
	 */
	public function getSize( $string )
	{
		return strlen( $string );
	}
}
?>