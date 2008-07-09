<?php
/**
 *	Calculates a Score for the Strength of a Password.
 *	@package		alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
/**
 *	Calculates a Score for the Strength of a Password.
 *	@package		alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
class Alg_Crypt_PasswordStrength
{
	/**	@var	array	$badWords		List of unsecure words */
	public static $badWords	= array(
		"password",
		"password1",
		"sex",
		"god",
		"123456",
		"123",
		"abc123",
		"liverpool",
		"letmein",
		"qwerty",
		"monkey"
	);
	/**	@var	int		$minLength		... */	
	public static $minLength	= 6;

	/**
	 *	Calculates and returns Score for the Strength of a Password (max 56).
	 *	@access		public
	 *	@param		string		$password
	 *	@return		int			between -300 and +56
	 */
	public static function getScore( $password )
	{
		$score	= 0;

		//  --  LENGTH  --  //
		$length	= strlen( $password );
		$min	= self::$minLength;
		if( $length < $min )															// Password too short
			$score	-= 100;
		else if( $length >= $min && $length <= $min + 2 ) 								// Password Short
			$score += 6;
		else if( $length >= $min + 3 && $length <= $min + 4 )							// Password Medium
			$score += 12;
		else if( $length >= $min + 5 )													// Password Large
			$score += 18;

		//  --  CASE SENSE  --  //
		if( preg_match( "/[a-z]/", $password ) )										// at least one lower case letter
			$score	+= 1;
		if( preg_match( "/[A-Z]/", $password ) )										// at least one upper case letter
			$score	+= 5;

		//  --  NUMBERS  --  //
		if( preg_match( "/\d+/", $password ) )											// at least one number
			$score	+= 5;
		if( preg_match( "/(.*[0-9].*[0-9].*[0-9])/", $password ) )						// at least three numbers
			$score	+= 7;

		//  --  SPECIAL CHARACTERS  --  //
		if( preg_match( "/.[!,@,#,$,%,^,&,*,?,_,~]/", $password ) )						// at least one special character
			$score	+= 5;
		if( preg_match( "/(.*[!@#$%^&*?_~].*[!@#$%^&*?_~])/", $password ) )				// at least two special characters
			$score	+= 7;

		//  --  COMBINATION  --  //
		if( preg_match( "/([a-z].*[A-Z])|([A-Z].*[a-z])/", $password ) )				// both upper and lower case
			$score	+= 2;
		if( preg_match( "/[a-z]/i", $password ) && preg_match( "/\d/", $password ) )	// both letters and numbers
			$score	+= 3;
		$regEx	= "/([a-z0-9].*[!@#$%^&*?_~])|([!@#$%^&*?_~].*[a-z0-9])/i";
		if( preg_match( $regEx, $password ) )											// letters, numbers, and special characters
			$score	+= 3;

		//  --  BAD WORDS  --  //
		if( in_array( strtolower( $password ), self::$badWords ) ) 
			$score -= 200;
		return $score;
	}
	
	/**
	 *	Calculates and returns the Strength of a Password (max 100).
	 *	@access		public
	 *	@param		string		$password
	 *	@return		int			between -300 and +100
	 */
	public static function getStrength( $password )
	{
		$score	= self::getScore( $password );
		return self::normaliseScore( $score );
	}
	
	/**
	 *	Calculates an Integer between -300 and +100 for a calculated Score.
	 *	@access		public
	 *	@param		int			$score
	 *	@return		int			between -300 and +100
	 */
	public static function normaliseScore( $score )
	{
		if( $score > 0 )
			$score	= round( $score * ( 100 / 56 ) );
		return $score;
	}
}
?>