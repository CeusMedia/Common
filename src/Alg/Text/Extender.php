<?php
/**
 *	String extender.
 *
 *	Copyright (c) 2015-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Text;

/**
 *	String extender.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Extender
{
	public static string $encoding	= 'UTF-8';

	public static function extend( string $text, int $toLength, bool $fromLeft = FALSE, string $withString = ' ' ): string
	{
		if( !function_exists( 'mb_strlen' ) )
			return str_pad( $text, $toLength, $withString, $fromLeft ? STR_PAD_LEFT : STR_PAD_RIGHT );
		$textLength			= mb_strlen( $text, self::$encoding );
		$withStringLength	= mb_strlen( $withString, self::$encoding );
		if( !$toLength || !$withStringLength || $toLength <= $textLength )
			return $text;
		$repeat	= (int) ceil( max( 0, $textLength - $withStringLength ) + $toLength );
		if( $fromLeft ){
			$result	= str_repeat( $withString, $repeat );
			$pos	= $toLength - ( ( $textLength - $withStringLength ) + $withStringLength );
			$result	= mb_substr( $result, 0, $pos, self::$encoding ).$text;
		}
		else{
			$result	= $text.str_repeat( $withString, $repeat );
			$result	= mb_substr( $result, 0, $toLength, self::$encoding );
		}
		return $result;
	}

	public static function extendCentric( string $text, int $toLength, string $withString = ' ' ): string
	{
		if( !function_exists( 'mb_strlen' ) )
			return str_pad( $text, $toLength, $withString, STR_PAD_BOTH );
		$textLength			= mb_strlen( $text );
		$withStringLength	= mb_strlen( $withString );
		if( !$toLength || !$withStringLength || $toLength <= $textLength )
			return $text;

		$length	= ( $toLength - $textLength ) / 2;
		$repeat	= (int) ceil( $length / $withStringLength );
		$left	= mb_substr( str_repeat( $withString, $repeat ), 0, (int) floor( $length ) );
		$right	= mb_substr( str_repeat( $withString, $repeat ), 0, (int) ceil( $length ) );
		return $left.$text.$right;
	}
}
