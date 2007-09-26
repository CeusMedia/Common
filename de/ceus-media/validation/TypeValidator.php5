<?php
/**
 *	Validation of single Characters.
 *	@package		validation
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@author			Michael Martin <michael.martin@ceus-media.de>
 *	@version		0.4
 */
/**
 *	Validation of single Characters.
 *	@package		validation
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@author			Michael Martin <michael.martin@ceus-media.de>
 *	@version		0.4
 */
class TypeValidator
{
	/**	@var	string	$regex_digit			Regular expression of validation class 'digit' */
	var $regex_digit		= '^[0-9]{1}$';
	/**	@var	string	$regex_letter			Regular expression of validation class 'letter' */
	var $regex_letter		= '^[a-zäöüßâáàêéèîíìôóòûúù]{1}$';
	/**	@var	string	$regex_comma		Regular expression of validation class 'comma' */
	var $regex_comma		= '^[,]{1}$';
	/**	@var	string	$regex_dot			Regular expression of validation class 'dot' */
	var $regex_dot		= '^[.]{1}$';
	/**	@var	string	$regex_colon			Regular expression of validation class 'colon' */
	var $regex_colon		= '^[:]{1}$';
	/**	@var	string	$regex_hyphen		Regular expression of validation class 'hyphen' */
	var $regex_hyphen	= '^[-]{1}$';
	/**	@var	string	$regex_underscore		Regular expression of validation class 'underscore' */
	var $regex_underscore	= '^[_]{1}$';
	/**	@var	string	$regex_slash			Regular expression of validation class 'slash' */
	var $regex_slash		= '^[/\]{1}$';
	/**	@var	string	$regex_plus			Regular expression of validation class 'plus' */
	var $regex_plus		= '^[+]{1}$';
	/**	@var	string	$regex_at			Regular expression of validation class 'at' */
	var $regex_at			= '^[@]{1}$';
	/**	@var	string	$regex_space			Regular expression of validation class 'space' */
	var $regex_space		= '^[ ]{1}$';
	/**	@var	string	$regex_ddate			Regular expression of validation class 'daydate' */
	var $regex_ddate		= '^((([0-2][0-9]{1})|([3]{1}[0-1]{1})).(([0]{0,1}[1-9]{1})|(1[0-2]{1})).([0-9]{4}))*$';
	/**	@var	string	$regex_mdate			Regular expression of validation class 'monthdate' */
	var $regex_mdate		= '^((([0]?[1-9])|(1[0-2])).([0-9]{4}))*$';
	/**	@var	string	$regex_email			Regular expression of validation class 'email' */
	var $regex_email		= '^([a-z0-9äöü_.-]{1,})@([a-z0-9äöü_.-]{1,})[.]([a-z0-9]{2,4})$';

	/**
	 *	Indicates wheter a character is of validation class 'at'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isAT( $char )
	{
		return ereg( $this->regex_at, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'comma'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isCOMMA( $char )
	{
		return ereg( $this->regex_comma, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'daydate'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isDAYDATE( $char )
	{
		return ereg( $this->regex_ddate, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'digit'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isDIGIT( $char )
	{
		return ereg( $this->regex_digit, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'dot'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isDOT( $char )
	{
		return ereg( $this->regex_dot, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'colon'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isCOLON( $char )
	{
		return ereg( $this->regex_colon, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'hypen'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isHYPHEN( $char )
	{
		return ereg( $this->regex_hyphen, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'underscore'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isUNDERSCORE( $char )
	{
		return ereg( $this->regex_underscore, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'letter'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isLETTER( $char )
	{
		return eregi( $this->regex_letter, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'monthdate'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isMONTHDATE( $char )
	{
		return ereg( $this->regex_mdate, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'plus'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isPLUS( $char )
	{
		return ereg( $this->regex_plus, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'space'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isSPACE( $char )
	{
		return ereg( $this->regex_space, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'slash'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isSLASH( $char )
	{
		return ereg( $this->regex_slash, $char );
	
	}

	/**
	 *	Indicates wheter a character is of validation class 'email'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isEMAIL( $char )
	{
		return eregi( $this->regex_email, $char );
	}

	/**
	 *	Indicates wheter a character is of given validation class .
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@param		string	$class		Validation class
	 *	@return		bool
	 */
	public function validate( $char, $class )
	{
		$class_method = "is".strtoupper( $class );
		if( $class && method_exists( $this, $class_method ) )
			$valid = $this->$class_method( $char );
		else $valid = true;
		return $valid;
	}
}
?>