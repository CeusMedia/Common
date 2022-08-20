<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for Log File.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Log
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Log;

use CeusMedia\Common\FS\File\Reader as FileReader;

/**
 *	Reader for Log File.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Log
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader
{
	/**	@var		string		$fileName		URI of file with absolute path */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of File
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->fileName = $fileName;
	}

	/**
	 *	Reads a Log File and returns Lines.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName	Name of Log File
	 *	@param		int			$offset		Offset from Start or End
	 *	@param		int			$limit		Amount of Entries to return
	 *	@return		array
	 */
	public static function load( string $fileName, int $offset = 0, int $limit = 0 ): array
	{
		$file	= new FileReader( $fileName );
		$lines	= $file->readArray();
		if( $offset !== 0 && $limit !==  0 )
			$lines	= array_slice( $lines, abs( $offset ), $limit );
		else if( $offset !== 0 )
			$lines	= array_slice( $lines, abs( $offset ) );
		return $lines;
	}

	/**
	 *	Reads Log File and returns Lines.
	 *	@access		public
	 *	@param		int			$offset		Offset from Start or End
	 *	@param		int			$limit		Amount of Entries to return
	 *	@return		array
	 */
	public function read( int $offset = 0, int $limit = 0 ): array
	{
		return $this->load( $this->fileName, $offset, $limit );
	}
}
