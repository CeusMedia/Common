<?php

declare(strict_types=1);

/**
 *	Trimmer for Strings, supporting cutting to the right and central cutting for too long Strings.
 *
 *	Copyright (c) 2009-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Text;

use InvalidArgumentException;

/**
 *	Trimmer for Strings, supporting cutting to the right and central cutting for too long Strings.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Trimmer
{
	/**
	 *	Trims String and cuts to the right if too long, also adding a mask string.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be trimmed
	 *	@param		int			$length		Length of String to be at most
	 *	@param		string		$mask		Mask String to append after cut
	 *	@param  	boolean		$fromLeft	Flag: trim left instead of right (default)
	 *	@param  	string		$encoding	Encoding of string
	 *	@return		string
	 */
	public static function trim( string $string, int $length = 0, string $mask = "...", bool $fromLeft = FALSE, string $encoding = "UTF-8" ): string
	{
		$string		= trim( $string );
		if( $length < 1 || self::strlen( $string, $encoding ) <= $length )
			return $string;
		$maskLength	= preg_match( '/^&.*;$/', $mask ) ? 1 : self::strlen( $mask, $encoding );
		if( $length < $maskLength )
			throw new InvalidArgumentException( 'Max length must be greater than mask length' );
		$range	= $length - $maskLength;
		if( $fromLeft )
			$string	= $mask.self::substr( $string, -$range, NULL, $encoding );
		else
			$string	= self::substr( $string, 0, $range, $encoding ).$mask;
		return $string;
	}

	/**
	 *	Trims String and cuts to the right if too long, also adding a mask string.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be trimmed
	 *	@param		int			$length		Length of String to be at most
	 *	@param		string		$mask		Mask String to append after cut
	 *	@param  	string		$encoding	Encoding of string
	 *	@return		string
	 */
	public static function trimCentric( string $string, int $length = 0, string $mask = "...", string $encoding = "UTF-8" ): string
	{
		$string	= trim( $string );
		if( $length === 0 || self::strlen( $string, $encoding ) <= $length )
			return $string;
		$maskLength	= preg_match( '/^&.*;$/', $mask ) ? 1 : self::strlen( $mask, $encoding );
		if( $maskLength >= $length )
			throw new InvalidArgumentException( 'Length must be greater than '.$maskLength );
		$range	= ( $length - $maskLength ) / 2;
		$length	= self::strlen( $string, $encoding ) - (int) floor( $range );
		$left	= self::substr( $string, 0, (int) ceil( $range ), $encoding );
		$right	= self::substr( $string, (int) -floor( $range ), $length, $encoding );
		return $left.$mask.$right;
	}

	/**
	 *	@access		public
	 *	@param		string		$string		String to be trimmed
	 *	@param		int			$length		Length of String to be at most
	 *	@param		string		$mask		Mask String to append after cut
	 *	@param  	string		$encoding	Encoding of string
	 *	@return		string
	 */
	public static function trimLeft( string $string, int $length = 0, string $mask = "...", string $encoding = "UTF-8" ): string
	{
		return self::trim( $string, $length, $mask, TRUE, $encoding );
	}

	protected static function strlen( string $string, ?string $encoding = NULL ): int
	{
		if( !function_exists( 'mb_strlen' ) )
			return strlen( utf8_decode( $string ) );
		$encoding	= $encoding ?: mb_internal_encoding();
		return mb_strlen( $string, $encoding );
	}

	protected static function substr( string $string, int $start, ?int $length = NULL, ?string $encoding = NULL ): string
	{
		if( !function_exists( 'mb_substr' ) )
			return utf8_encode( substr( utf8_decode( $string ), $start, $length ) );
		$encoding	= $encoding ?: mb_internal_encoding();
		return mb_substr( $string, $start, $length, $encoding );
	}
}
