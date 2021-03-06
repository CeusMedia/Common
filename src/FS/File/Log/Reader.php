<?php
/**
 *	Reader for Log File.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			28.11.2007
 */
/**
 *	Reader for Log File.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Log
 *	@uses			FS_File_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			28.11.2007
 */
class FS_File_Log_Reader
{
	/**	@var		string		$fileName		URI of file with absolute path */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName = $fileName;
	}

	/**
	 *	Reads a Log File and returns Lines.
	 *	@access		public
	 *	@static
	 *	@param		string		$uri		URI of Log File
	 *	@param		int			$offset		Offset from Start or End
	 *	@param		int			$limit		Amount of Entries to return
	 *	@return		array
	 */
	public static function load( $fileName, $offset = NULL, $limit = NULL )
	{
		$file	= new FS_File_Reader( $fileName );
		$lines	= $file->readArray();
		if( $offset !== NULL && $limit !== NULL && (int) $limit !==  0 )
			$lines	= array_slice( $lines, abs( (int) $offset ), (int) $limit );
		else if( $offset !== NULL && (int) $offset !== 0 )
			$lines	= array_slice( $lines, (int) $offset );
		return $lines;
	}

	/**
	 *	Reads Log File and returns Lines.
	 *	@access		public
	 *	@param		int			$offset		Offset from Start or End
	 *	@param		int			$limit		Amount of Entries to return
	 *	@return		array
	 */
	public function read( $offset = NULL, $limit = NULL )
	{
		return $this->load( $this->fileName, $offset, $limit );
	}
}
