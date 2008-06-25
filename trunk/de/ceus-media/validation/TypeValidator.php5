<?php
/**
 *	Validation of single Characters.
 *	@package		alg.validation
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
	/**	@var		string		$regexDigit			Regular expression of validation class 'digit' */
	protected $regexDigit		= '^[0-9]{1}$';
	/**	@var		string		$regexLetter		Regular expression of validation class 'letter' */
	protected $regexLetter		= '^[a-zäöüßâáàêéèîíìôóòûúù]{1}$';
	/**	@var		string		$regexComma			Regular expression of validation class 'comma' */
	protected $regexComma		= '^[,]{1}$';
	/**	@var		string		$regexDot			Regular expression of validation class 'dot' */
	protected $regexDot			= '^[.]{1}$';
	/**	@var		string		$regexColon			Regular expression of validation class 'colon' */
	protected $regexColon		= '^[:]{1}$';
	/**	@var		string		$regexHyphen		Regular expression of validation class 'hyphen' */
	protected $regexHyphen		= '^[-]{1}$';
	/**	@var		string		$regexUnderscore	Regular expression of validation class 'underscore' */
	protected $regexUnderscore	= '^[_]{1}$';
	/**	@var		string		$regexSlash			Regular expression of validation class 'slash' */
	protected $regexSlash		= '^[/\]{1}$';
	/**	@var		string		$regexPlus			Regular expression of validation class 'plus' */
	protected $regexPlus		= '^[+]{1}$';
	/**	@var		string		$regexAt			Regular expression of validation class 'at' */
	protected $regexAt			= '^[@]{1}$';
	/**	@var		string		$regexSpace			Regular expression of validation class 'space' */
	protected $regexSpace		= '^[ ]{1}$';
	/**	@var		string		$regexDayDate		Regular expression of validation class 'daydate' */
	protected $regexDayDate		= '^((([0-2][0-9]{1})|([3]{1}[0-1]{1})).(([0]{0,1}[1-9]{1})|(1[0-2]{1})).([0-9]{4}))*$';
	/**	@var		string		$regexMonthDate		Regular expression of validation class 'monthdate' */
	protected $regexMonthDate	= '^((([0]?[1-9])|(1[0-2])).([0-9]{4}))*$';
	/**	@var		string		$regexEmail			Regular expression of validation class 'email' */
	protected $regexEmail		= '^([a-z0-9äöü_.-]{1,})@([a-z0-9äöü_.-]{1,})[.]([a-z0-9]{2,4})$';

	/**
	 *	Indicates wheter a character is of validation class 'at'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isAT( $char )
	{
		return ereg( $this->regexAt, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'comma'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isCOMMA( $char )
	{
		return ereg( $this->regexComma, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'daydate'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isDAYDATE( $char )
	{
		return ereg( $this->regexDayDate, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'digit'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isDIGIT( $char )
	{
		return ereg( $this->regexDigit, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'dot'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isDOT( $char )
	{
		return ereg( $this->regexDot, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'colon'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isCOLON( $char )
	{
		return ereg( $this->regexColon, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'hypen'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isHYPHEN( $char )
	{
		return ereg( $this->regexHyphen, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'underscore'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isUNDERSCORE( $char )
	{
		return ereg( $this->regexUnderscore, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'letter'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isLETTER( $char )
	{
		return eregi( $this->regexLetter, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'monthdate'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isMONTHDATE( $char )
	{
		return ereg( $this->regexMonthDate, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'plus'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isPLUS( $char )
	{
		return ereg( $this->regexPlus, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'space'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isSPACE( $char )
	{
		return ereg( $this->regexSpace, $char );
	}

	/**
	 *	Indicates wheter a character is of validation class 'slash'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isSLASH( $char )
	{
		return ereg( $this->regexSlash, $char );
	
	}

	/**
	 *	Indicates wheter a character is of validation class 'email'.
	 *	@access		public
	 *	@param		string	$char		Character to be proved
	 *	@return		bool
	 */
	public function isEMAIL( $char )
	{
		return eregi( $this->regexEmail, $char );
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