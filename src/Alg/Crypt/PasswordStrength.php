<?php

declare(strict_types=1);

/**
 *	Calculates a Score for the Strength of a Password.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Crypt;

/**
 *	Calculates a Score for the Strength of a Password.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class PasswordStrength
{
	/**	@var	array	$badWords		List of unsecure words */
	public static $badWords	= [
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
	];
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
		// Password too short
		if( $length < $min )
			$score	-= 100;
		// Password Short
		else if( $length >= $min && $length <= $min + 2 )
			$score += 6;
		// Password Medium
		else if( $length >= $min + 3 && $length <= $min + 4 )
			$score += 12;
		// Password Large
		else if( $length >= $min + 5 )
			$score += 18;

		//  --  CASE SENSE  --  //
		// at least one lower case letter
		if( preg_match( "/[a-z]/", $password ) )
			$score	+= 1;
		// at least one upper case letter
		if( preg_match( "/[A-Z]/", $password ) )
			$score	+= 5;

		//  --  NUMBERS  --  //
		// at least one number
		if( preg_match( "/\d+/", $password ) )
			$score	+= 5;
		// at least three numbers
		if( preg_match( "/(.*[0-9].*[0-9].*[0-9])/", $password ) )
			$score	+= 7;

		//  --  SPECIAL CHARACTERS  --  //
		// at least one special character
		if( preg_match( "/.[!,@,#,$,%,^,&,*,?,_,~]/", $password ) )
			$score	+= 5;
		// at least two special characters
		if( preg_match( "/(.*[!@#$%^&*?_~].*[!@#$%^&*?_~])/", $password ) )
			$score	+= 7;

		//  --  COMBINATION  --  //
		// both upper and lower case
		if( preg_match( "/([a-z].*[A-Z])|([A-Z].*[a-z])/", $password ) )
			$score	+= 2;
		// both letters and numbers
		if( preg_match( "/[a-z]/i", $password ) && preg_match( "/\d/", $password ) )
			$score	+= 3;
		$regEx	= "/([a-z0-9].*[!@#$%^&*?_~])|([!@#$%^&*?_~].*[a-z0-9])/i";
		// letters, numbers, and special characters
		if( preg_match( $regEx, $password ) )
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
