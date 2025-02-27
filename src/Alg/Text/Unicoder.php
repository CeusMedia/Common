<?php

declare(strict_types=1);

/**
 *	Converts a String into UTF-8.
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

/**
 *	Converts a String into UTF-8.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Unicoder
{
	/**	@var		string		$string		Unicoded String */
	protected string $string;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$string		String to unicode
	 *	@param		bool		$force		Flag: encode into UTF-8 even if UTF-8 Encoding has been detected
	 *	@return		void
	 */
	public function __construct( string $string, bool $force = FALSE )
	{
		$this->string	= self::convertToUnicode( $string, $force );
	}

	/**
	 *	Returns unicoded String.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		return $this->string;
	}

	/**
	 *	Converts a String to UTF-8.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be converted
	 *	@param		bool		$force		Flag: encode into UTF-8 even if UTF-8 Encoding has been detected
	 *	@return		string
	 */
	public static function convertToUnicode( string $string, bool $force = FALSE ): string
	{
		if( !( !$force && self::isUnicode( $string ) ) )
			$string	= utf8_encode( $string );
		return $string;
	}

	/**
	 *	Returns unicoded String.
	 *	@access		public
	 *	@return		string
	 */
	public function getString(): string
	{
		return $this->string;
	}

	/**
	 *	Check whether a String is encoded into UTF-8.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isUnicode( string $string ): bool
	{
		if( function_exists( 'mb_convert_encoding ') )
			return mb_check_encoding( $string, 'UTF-8' );

		$length = strlen( $string );
		for( $i=0; $i < $length; $i++ ){
			$c = ord( $string[$i] );
			if( $c < 0x80 ) $n = 0;												# 0bbbbbbb
			elseif( ( $c & 0xE0 ) == 0xC0 ) $n=1;								# 110bbbbb
			elseif( ( $c & 0xF0 ) == 0xE0 ) $n=2;								# 1110bbbb
			elseif( ( $c & 0xF8 ) == 0xF0 ) $n=3;								# 11110bbb
			elseif( ( $c & 0xFC ) == 0xF8 ) $n=4;								# 111110bb
			elseif( ( $c & 0xFE ) == 0xFC ) $n=5;								# 1111110b
			else return FALSE;													# Does not match any model
			for( $j=0; $j<$n; $j++ )											# n bytes matching 10bbbbbb follow ?
				if( ( ++$i == $length ) || ( ( ord( $string[$i]) & 0xC0 ) != 0x80 ) )
					return FALSE;
		}
		return TRUE;
	}
}
