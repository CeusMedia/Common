<?php
/**
 *	Calculates a Score for the Strength of a Password.
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
 *	@package		Alg.Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.02.2008
 *	@version		$Id$
 */
/**
 *	Calculates a Score for the Strength of a Password.
 *	@category		cmClasses
 *	@package		Alg.Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.02.2008
 *	@version		$Id$
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
	 *	@static
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
	 *	@static
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
	 *	@static
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