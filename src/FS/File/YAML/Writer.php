<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	YAML Writer based on Spyc.
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
 *	@package		CeusMedia_Common_FS_File_YAML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\YAML;

use CeusMedia\Common\FS\File\Writer as FileWriter;

/**
 *	YAML Writer based on Spyc.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_YAML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Writer
{
	/**	@var		string		$fileName		File Name of YAML File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of YAML File.
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->fileName	= $fileName;
	}

	/**
	 *	Writes Data into YAML File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of YAML File.
	 *	@param		array		$data			Array to write into YAML File
	 *	@return		int
	 */
	public static function save( string $fileName, array $data ): int
	{
		return FileWriter::save( $fileName, Spyc::YAMLDump( $data ) );
	}

	/**
	 *	Writes Data into YAML File.
	 *	@access		public
	 *	@param		array		$data			Array to write into YAML File
	 *	@return		int
	 */
	public function write( array $data ): int
	{
		return self::save( $this->fileName, $data );
	}
}
