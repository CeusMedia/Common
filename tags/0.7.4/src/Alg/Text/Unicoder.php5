<?php
/**
 *	Converts a String into UTF-8.
 *
 *	Copyright (c) 2009-2010 Christian Würker (ceus-media.de)
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
 *	@package		Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	Converts a String into UTF-8.
 *	@category		cmClasses
 *	@package		Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
class Alg_Text_Unicoder
{
	/**	@var		string		$string		Unicoded String */
	protected $string;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$string		String to unicode
	 *	@param		bool		$force		Flag: encode into UTF-8 even if UTF-8 Encoding has been detected
	 *	@return		void
	 */
	public function __construct( $string, $force = FALSE )
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
	 *	Check whether a String is encoded into UTF-8.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isUnicode( $string )
	{
		if( function_exists( 'mb_convert_encoding' ) ){
			$s1	= mb_convert_encoding( $string, 'UTF-8', 'UTF-8' );
			$s2	= mb_convert_encoding( $string, 'UTF-8', 'UTF-8' );
			return $string === $s2;
		}
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
		return FALSE;
	}

	/**
	 *	Converts a String to UTF-8.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be converted
	 *	@param		bool		$force		Flag: encode into UTF-8 even if UTF-8 Encoding has been detected
	 *	@return		string
	 */
	public static function convertToUnicode( $string, $force = FALSE )
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
	public function getString()
	{
		return $this->string;
	}
}
?>