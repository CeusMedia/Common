<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Converts a File into UTF-8.
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
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File;

use CeusMedia\Common\Alg\Text\Unicoder as TextUnicoder;
use Exception;

/**
 *	Converts a File into UTF-8.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Unicoder
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName	Name of File to unicode
	 *	@param		bool		$force		Flag: encode into UTF-8 even if UTF-8 Encoding has been detected
	 *	@return		void
	 *	@throws		Exception
	 */
	public function __construct( string $fileName, bool $force = FALSE )
	{
		self::convertToUnicode( $fileName, $force = FALSE );
	}

	/**
	 *	Converts a String to UTF-8.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName	Name of File to unicode
	 *	@param		bool		$force		Flag: encode into UTF-8 even if UTF-8 Encoding has been detected
	 *	@return		bool
	 *	@throws		Exception
	 */
	public static function convertToUnicode( string $fileName, bool $force = FALSE ): bool
	{
		if( !(!$force && self::isUnicode( $fileName ) ) ){
			$string		= Editor::load( $fileName );
			$encoded	= TextUnicoder::convertToUnicode( $string );
			return (bool) Editor::save( $fileName, $encoded );
		}
		return FALSE;
	}

	/**
	 *	Check whether a String is encoded into UTF-8.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName	Name of File to unicode
	 *	@return		bool
	 *	@throws		Exception
	 */
	public static function isUnicode( string $fileName ): bool
	{
		if( !file_exists( $fileName ) )
			throw new Exception( 'File "'.$fileName.'" is not existing.' );
		$string		= Editor::load( $fileName );
		$encoded	= TextUnicoder::convertToUnicode( $string );
		return $encoded === $string;
	}
}
