<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Writer for Log Files containing JSON Serials.
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
 *	@package		CeusMedia_Common_FS_File_Log_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Log\JSON;

use CeusMedia\Common\ADT\JSON\Encoder as JsonEncoder;

/**
 *	Writer for Log Files containing JSON Serials.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Log_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Writer
{
	/**	@var		string		$fileName		File Name of Log File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Log File
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->fileName	= $fileName;
	}

	/**
	 *	Adds Data to Log File.
	 *	@access		public
	 *	@param		array		$data			Data Array to note
	 *	@return		bool
	 */
	public function note( array $data ): bool
	{
		return self::noteData( $this->fileName, $data );
	}

	/**
	 *	Adds Data to Log File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of Log File
	 *	@param		array		$data			Data Array to note
	 *	@return		bool
	 */
	public static function noteData( string $fileName, array $data ): bool
	{
		$data	= array_merge( ['timestamp' => time()], $data );
		$serial	= JsonEncoder::create()->encode( $data )."\n";
		if( !file_exists( dirname( $fileName ) ) )
			mkDir( dirname( $fileName ), 0700, TRUE );
		return error_log( $serial, 3, $fileName );
	}
}
