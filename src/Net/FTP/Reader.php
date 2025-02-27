<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for FTP Connections.
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
 *	@package		CeusMedia_Common_Net_FTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\FTP;

/**
 *	Reader for FTP Connections.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_FTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader
{
	/**	@var		array			$fileTypes		List of File Types (dir,file,link) */
	protected $fileTypes	= [
		'd'	=> "dir",
		'-'	=> "file",
		'l'	=> "link"
	];

	/**	@var		Connection		$connection		FTP Connection Object */
	protected $connection;

	/**
	 *	Constructor
	 *	@access		public
	 *	@param		Connection		$connection		FTP Connection Object
	 *	@return		void
	 */
	public function __construct( Connection $connection )
	{
		$this->connection	= $connection;
	}

	/**
	 *	Transfers a File from FTP Server.
	 *	@access		public
	 *	@param		string			$fileName		Name of Remove File
	 *	@param		string|NULL		$target			Name of Target File
	 *	@return		bool
	 */
	public function getFile( string $fileName, ?string $target ): bool
	{
		$this->connection->checkConnection();
		if( $target === NULL || strlen( trim( $target ) ) === 0 )
			$target	= $fileName;
		return @ftp_get( $this->connection->getResource(), $target, $fileName, $this->connection->mode );
	}

	/**
	 *	Returns Array of Files in Path [and nested Folders].
	 *	@access		public
	 *	@param		string		$path			Path
	 *	@param		bool		$recursive		Scan Folders recursive (default: FALSE)
	 *	@return		array
	 */
	public function getFileList( string $path = "", bool $recursive = FALSE ): array
	{
		$this->connection->checkConnection();
		$results	= [];
		$list		= $this->getList( $path, $recursive );
		foreach( $list as $entry )
			if( !preg_match( "@/?[.]{1,2}$@", $entry['name'] ) )
				if( !$entry['isdir'] )
					$results[]	= $entry;
		return $results;
	}

	/**
	 *	Returns Array of Folders in Path [and nested Folders].
	 *	@access		public
	 *	@param		string		$path			Path
	 *	@param		bool		$recursive		Scan Folders recursive (default: FALSE)
	 *	@return		array
	 */
	public function getFolderList( string $path = "", bool $recursive = FALSE ): array
	{
		$this->connection->checkConnection();
		$results	= [];
		$list		= $this->getList( $path, $recursive );
		foreach( $list as $entry )
			if( !preg_match( "@/?[.]{1,2}$@", $entry['name'] ) )
				if( $entry['isdir'] )
					$results[]	= $entry;
		return $results;
	}

	/**
	 *	Returns a List of all Folders and Files of a Path on FTP Server.
	 *	@access		public
	 *	@param		string		$path			Path
	 *	@param		bool		$recursive		Scan Folders recursive (default: FALSE)
	 *	@return		array
	 */
	public function getList( string $path = "", bool $recursive = FALSE ): array
	{
		$this->connection->checkConnection();
		$parsed	= [];
		if( !$path )
			$path	= $this->getPath();
		$list	= ftp_rawlist( $this->connection->getResource(), $path );
		if( is_array( $list ) ){
			foreach( $list as $current ){
				$data	= $this->parseListEntry( $current );
				if( count( $data ) ){
					$parsed[]	= $data;
					if( $recursive && $data['isdir'] && $data['name'] != "." && $data['name'] != ".." ){
						$nested	= $this->getList( $path."/".$data['name'], TRUE );
						foreach( $nested as $entry ){
							$entry['name']	= $data['name']."/".$entry['name'];
							$parsed[]	= $entry;
						}
					}
				}
			}
		}
		return $parsed;
	}

	/**
	 *	Returns current Path on Server.
	 *	@access		public
	 *	@return		string
	 */
	public function getPath(): string
	{
		return $this->connection->getPath();
	}

	/**
	 *	Returns permissions string as octal string.
	 *	So, "drwxrw-r--" will become "0764".
	 *	Also supports sticky bits.
	 *	@param		string		$permissions
	 *	@return		string
	 */
	public function getPermissionsAsOctal( string $permissions ): string
	{
		$mode	= 0;
		if( $permissions[1] == 'r' ) $mode += 0400;
		if( $permissions[2] == 'w' ) $mode += 0200;
		if( $permissions[3] == 'x' ) $mode += 0100;
		else if( $permissions[3] == 's' ) $mode += 04100;
		else if( $permissions[3] == 'S' ) $mode += 04000;

		if( $permissions[4] == 'r' ) $mode += 040;
		if( $permissions[5] == 'w' ) $mode += 020;
		if( $permissions[6] == 'x' ) $mode += 010;
		else if( $permissions[6] == 's' ) $mode += 02010;
		else if( $permissions[6] == 'S' ) $mode += 02000;

		if( $permissions[7] == 'r' ) $mode += 04;
		if( $permissions[8] == 'w' ) $mode += 02;
		if( $permissions[9] == 'x' ) $mode += 01;
		else if( $permissions[9] == 't' ) $mode += 01001;
		else if( $permissions[9] == 'T' ) $mode += 01000;
		return sprintf( '0%o', $mode );
	}

	/**
	 *	Parsed List Entry.
	 *	@access		protected
	 *	@param		string		$entry		Entry of List
	 *	@return		array
	 */
	protected function parseListEntry( string $entry ): array
	{
		$data	= [];
		$parts	= preg_split("/\s+/", $entry, 9 );
		if( $parts[0] == "total" )
			return [];
		$data['isdir']			= $parts[0][0] === "d";
		$data['islink']			= $parts[0][0] === "l";
		$data['isfile']			= $parts[0][0] === "-";
		$data['permissions']	= $parts[0];
		$data['number']			= $parts[1];
		$data['owner']			= $parts[2];
		$data['group']			= $parts[3];
		$data['size']			= $parts[4];
		$data['month']			= $parts[5];
		$data['day']			= $parts[6];
		$data['time']			= $parts[7];
		$data['name']			= $parts[8];
		if( preg_match( "/:/", $data['time'] ) )
			$data['year']		= date( "Y" );
		else{
			$data['year']		= $data['time'];
			$data['time']		= "00:00";
		}
		$data['timestamp']		= strtotime( $data['day']." ".$data['month']." ".$data['year']." ".$data['time'] );
		$data['datetime']		= date( "c", $data['timestamp'] );
		$data['type']			= $this->fileTypes[$parts[0][0]];
		$data['type_short']		= $data['type'][0];
		$data['octal']			= $this->getPermissionsAsOctal( $parts[0] );
		$data['raw']			= $entry;
		return $data;
	}

	/**
	 *	Searchs for File in Path [and nested Folders] [with regular Expression].
	 *	@access		public
	 *	@param		string		$fileName			Name of File to find
	 *	@param		bool		$recursive			Scan Folders recursive (default: false)
	 *	@param		bool		$regular			Search with regular Expression (default: false)
	 *	@return		array
	 */
	public function searchFile( string $fileName = "", bool $recursive = FALSE, bool $regular = FALSE ): array
	{
		$this->connection->checkConnection();
		$results	= [];
		$list		= $this->getFileList( $this->getPath(), $recursive );
		foreach( $list as $entry ){
			if( !$entry['isdir'] ){
				if( $regular ){
					if( preg_match( $fileName, $entry['name'] ) )
						$results[]	= $entry;
				}
				else if( basename( $entry['name'] ) == $fileName )
					$results[]	= $entry;
			}
		}
		return $results;
	}

	/**
	 *	Searchs for Folder in Path [and nested Folders] [with regular Expression].
	 *	@access		public
	 *	@param		string		$folderName			Name of Folder to find
	 *	@param		bool		$recursive			Scan Folders recursive (default: FALSE)
	 *	@param		bool		$regular			Search with regular Expression (default: FALSE)
	 *	@return		array
	 */
	public function searchFolder( string $folderName = "", bool $recursive = FALSE, bool $regular = FALSE ): array
	{
		$this->connection->checkConnection();
		$results	= [];
		$list		= $this->getFolderList( $this->getPath(), $recursive );
		foreach( $list as $entry ){
			if( $entry['isdir'] ){
				if( $regular ){
					if( preg_match( $folderName, $entry['name'] ) )
						$results[]	= $entry;
				}
				else if( basename( $entry['name'] ) == $folderName )
					$results[]	= $entry;
			}
		}
		return $results;
	}

	/**
	 *	Sets current Path on Server.
	 *	@access		public
	 *	@param		string		$path		Path to go to
	 *	@return		bool
	 */
	public function setPath( string $path ): bool
	{
		return $this->connection->setPath( $path );
	}
}
