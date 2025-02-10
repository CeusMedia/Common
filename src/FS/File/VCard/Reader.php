<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reads and parses vCard Strings from File or URL to vCard Data Object.
 *
 *	Copyright (c) 2010-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_VCard
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\VCard;

use CeusMedia\Common\ADT\VCard;
use CeusMedia\Common\FS\File\Reader as FileReader;

/**
 *	Reads and parses vCard Strings from File or URL to vCard Data Object.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_VCard
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Doc
 */
class Reader
{
	/**
	 *	Reads and parses vCard File to vCard Object and converts between Charsets.
	 *	@access		public
	 *	@static
	 *	@param		string			$fileName		Path of file to read
	 *	@param		string|NULL		$charsetIn		Charset to convert from
	 *	@param		string|NULL		$charsetOut		Charset to convert to
	 *	@return		VCard
	 */
	public function readFile( string $fileName, ?string $charsetIn = NULL, ?string $charsetOut = NULL ): VCard
	{
		$parser	= new Parser;
		return $parser->parse( FileReader::load( $fileName ), $charsetIn, $charsetOut );
	}
}
