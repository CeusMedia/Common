<?php
/**
 *	Trimmer for Strings, supporting cutting to the right and central cutting for too long Strings.
 *
 *	Copyright (c) 2009-2018 Christian Würker (ceusmedia.de)
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
 *	@copyright		2009-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	Trimmer for Strings, supporting cutting to the right and central cutting for too long Strings.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 *	@version		$Id$
 */
class Alg_Text_Trimmer
{
	static protected function strlen( $string, $encoding = NULL )
	{
		if( !function_exists( 'mb_strlen' ) )
			return strlen( utf8_decode( $string ) );
		$encoding	= $encoding ? $encoding : mb_internal_encoding();
		return mb_strlen( $string, $encoding );
	}

	static protected function substr( $string, $start, $length = NULL, $encoding = NULL )
	{
		if( !function_exists( 'mb_substr' ) )
			return utf8_encode( substr( utf8_decode( $string ), $start, $length ) );
		$encoding	= $encoding ? $encoding : mb_internal_encoding();
		return mb_substr( $string, $start, $length, $encoding );
	}

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
	static public function trim( $string, $length = 0, $mask = "...", $fromLeft = FALSE, $encoding = "UTF-8" )
	{
		$string		= trim( (string) $string );
		if( (int) $length < 1 || self::strlen( $string, $encoding ) <= $length )
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
	static public function trimCentric( $string, $length = 0, $mask = "...", $encoding = "UTF-8" )
	{
		$string	= trim( (string) $string );
		if( (int) $length < 1 || self::strlen( $string, $encoding ) <= $length )
			return $string;
		$maskLength	= preg_match( '/^&.*;$/', $mask ) ? 1 : self::strlen( $mask, $encoding );
		if( $maskLength >= $length )
			throw new InvalidArgumentException( 'Lenght must be greater than '.$maskLength );
		$range	= ( $length - $maskLength ) / 2;
		$length	= self::strlen( $string, $encoding ) - floor( $range );
		$left	= self::substr( $string, 0, ceil( $range ), $encoding );
		$right	= self::substr( $string, -floor( $range ), $length, $encoding );
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
	static public function trimLeft( $string, $length = 0, $mask = "...", $encoding = "UTF-8" )
	{
		return self::trim( $string, $length, $mask, TRUE, $encoding );
	}
}
