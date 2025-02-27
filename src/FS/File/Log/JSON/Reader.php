<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for Log Files containing JSON Serials.
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

use Exception;

/**
 *	Reader for Log Files containing JSON Serials.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Log_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader
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
	 *	Returns List of parsed Lines.
	 *	@access		public
	 *	@param		bool		$reverse		Flag: revert List
	 *	@param		int			$limit			Optional: limit List
	 *	@return		array
	 *	@throws		Exception
	 */
	public function getList( bool $reverse = FALSE, int $limit = 0 ): array
	{
		return static::read($this->fileName, $reverse, $limit);
	}

	/**
	 *	Reads and returns List of parsed Lines statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of Log File
	 *	@param		bool		$reverse		Flag: revert List
	 *	@param		int			$limit			Optional: limit List
	 *	@return		array
	 *	@throws		Exception
	 */
	public static function read( string $fileName, bool $reverse = FALSE, int $limit = 0 ): array
	{
		$data	= [];
		if( !file_exists( $fileName ) )
			throw new Exception( 'Log File "'.$fileName.'" is not existing.' );
		$lines		= file( $fileName );
		foreach( $lines as $line ) {
			$line	= trim( $line );
			if( !$line )
				continue;
			$data[]	= json_decode( $line, TRUE );
		}
		if( $reverse )
			$data	= array_reverse( $data );
		if( $limit )
			$data	= array_slice( $data, 0, $limit );
		return $data;
	}
}
