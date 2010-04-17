<?php
import( 'de.ceus-media.alg.StringUnicoder' );
/**
 *	Converts a File into UTF-8.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		
 *	@uses			Alg_StringUnicoder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.10.2007
 *	@version		0.1
 */
/**
 *	Converts a File into UTF-8.
 *	@package		
 *	@uses			Alg_StringUnicoder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.10.2007
 *	@version		0.1
 */
class File_Unicoder
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName	Name of File to unicode
	 *	@param		bool		$force		Flag: encode into UTF-8 even if UTF-8 Encoding has been detected
	 *	@return		void
	 */
	public function __construct( $fileName, $force = FALSE )
	{
		return self::convertToUnicode( $fileName, $force = FALSE );
	}

	/**
	 *	Check whether a String is encoded into UTF-8.
	 *	@access		public
	 *	@param		string		$fileName	Name of File to unicode
	 *	@return		bool
	 */
	public static function isUnicode( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( 'File "'.$fileName.'" is not existing.' );
		$string		= file_get_contents( $fileName );
		$unicoded	= Alg_StringUnicoder::convertToUnicode( $string );
		return $unicoded == $string;
	}

	/**
	 *	Converts a String to UTF-8.
	 *	@access		public
	 *	@param		string		$fileName	Name of File to unicode
	 *	@param		bool		$force		Flag: encode into UTF-8 even if UTF-8 Encoding has been detected
	 *	@return		bool
	 */
	public static function convertToUnicode( $fileName, $force = FALSE )
	{
		if( !(!$force && self::isUnicode( $fileName ) ) )
		{
			$string		= file_get_contents( $fileName );
			$unicoded	= Alg_StringUnicoder::convertToUnicode( $string );
			return (bool) file_put_contents( $fileName, $unicoded );
		}
		return FALSE;
	}
	
#	public function convert()
#	{
#	
#	}
}
?>