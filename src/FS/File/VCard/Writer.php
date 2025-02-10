<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Writes vCard String from vCard Data Object to a File.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\VCard;

use CeusMedia\Common\ADT\VCard;
use CeusMedia\Common\FS\File\Writer as FileWriter;

/**
 *	Writes vCard String from vCard Data Object to a File.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_VCard
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Writer
{
	/**	@var		string		$fileName		File Name of VCard File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of VCard File.
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->fileName	= $fileName;
	}

	/**
	 *	Saves a vCard Object to a File statically and returns Number of written Bytes.
	 *	@access		public
	 *	@static
	 *	@param		string			$fileName		Path of file to save vCard Object to
	 *	@param		VCard			$card			vCard Object
	 *	@param		string|NULL		$charsetIn		Charset to convert from
	 *	@param		string|NULL		$charsetOut		Charset to convert to
	 *	@return		int
	 */
	public static function save( string $fileName, VCard $card, ?string $charsetIn = NULL, ?string $charsetOut = NULL ): int
	{
		return FileWriter::save( $fileName, Builder::build( $card, $charsetIn, $charsetOut ) );
	}

	/**
	 *	Writes a vCard Object to the set up File and returns Number of written Bytes.
	 *	@access		public
	 *	@param		VCard			$card			vCard Object
	 *	@param		string|NULL		$charsetIn		Charset to convert from
	 *	@param		string|NULL		$charsetOut		Charset to convert to
	 *	@return		int
	 */
	public function write( VCard $card, ?string $charsetIn = NULL, ?string $charsetOut = NULL ): int
	{
		return static::save($this->fileName, $card, $charsetIn, $charsetOut);
	}
}
