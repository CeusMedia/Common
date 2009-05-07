<?php
/**
 *	Converts a String into UTF-8.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.10.2007
 *	@version		0.1
 */
/**
 *	Converts a String into UTF-8.
 *	@package		alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.10.2007
 *	@version		0.1
 */
class Alg_StringUnicoder
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
		$unicoded	= utf8_encode( utf8_decode( $string ) );
		return $unicoded == $string;
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