<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Client for FTP Connections.
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

use Exception;
use FTP\Connection as FtpConnection;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Client for FTP Connections.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_FTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Client
{
	/**	@var		Connection		$connection		FTP Connection Object */
	protected Connection $connection;

	/**	@var		Reader			$reader			FTP Reader Object */
	protected Reader $reader;

	/**	@var		Writer			$writer			FTP Writer Object */
	protected Writer $writer;

	/**
	 *	Constructor, opens FTP Connection.
	 *	@access		public
	 *	@param		string			$host			Host Name
	 *	@param		integer|NULL	$port			Service Port
	 *	@param		string|NULL		$path			...
	 *	@param		string|NULL		$username		Username
	 *	@param		string|NULL		$password		Password
	 *	@return		void
	 */
	public function __construct( string $host, ?int $port = 21, ?string $path = NULL, ?string $username = NULL, ?string $password = NULL )
	{
		try{
			$port	??= 21;
			$this->connection	= new Connection( $host, $port );
			$this->connection->checkConnection( TRUE, FALSE );
			if( $username && $password )
				$this->connection->login( $username, $password );
			$this->connection->checkConnection();
			if( $path )
				if( !$this->connection->setPath( $path ) )
					throw new InvalidArgumentException( 'Path "'.$path.'" not existing' );
			$this->reader		= new Reader( $this->connection );
			$this->writer		= new Writer( $this->connection );
		}
		catch( Exception $e ){
			throw new RuntimeException( 'FTP connection failed ', 0, $e );
		}
	}

	/**
	 *	Destructor, closes FTP Connection.
	 *	@access		public
	 *	@return		void
	 */
	public function __destruct()
	{
		$this->connection->close( TRUE );
	}

	/**
	 *	Changes Rights of File or Folders on FTP Server.
	 *	@access		public
	 *	@param		string		$fileName		Name of File to change Rights for
	 *	@param		int			$mode			Mode of Rights (i.e. 0755)
	 *	@return		bool
	 */
	public function changeRights( string $fileName, int $mode ): bool
	{
		$this->writer->changeRights( $fileName, $mode );
		return TRUE;
	}

	/**
	 *	Copies a File on FTP Server.
	 *	@access		public
	 *	@param		string		$from			Path of source file
	 *	@param		string		$to				Path of target file
	 *	@return		bool
	 */
	public function copyFile( string $from, string $to ): bool
	{
		return $this->writer->copyFile( $from, $to );
	}

	/**
	 *	Copies a Folder on FTP Server [recursive].
	 *	@access		public
	 *	@param		string		$from			Path of source file
	 *	@param		string		$to				Path of target file
	 *	@return		bool
	 */
	public function copyFolder( string $from, string $to ): bool
	{
		return $this->writer->copyFolder( $from, $to );
	}

	/**
	 *	Creates a Folder on FTP Server.
	 *	@access		public
	 *	@param		string		$folderName		Path of folder to be created
	 *	@return		bool
	 */
	public function createFolder( string $folderName ): bool
	{
		return $this->writer->createFolder( $folderName );
	}

	/**
	 *	Transfers a File from FTP Server.
	 *	@access		public
	 *	@param		string			$globalFile		Path of remote file
	 *	@param		string|NULL		$localFile		Path of local target file
	 *	@return		bool
	 */
	public function getFile( string $globalFile, ?string $localFile = NULL ): bool
	{
		return $this->reader->getFile( $globalFile, $localFile ?? '' );
	}

	/**
	 *	Returns Array of Files in Path [and nested Folders].
	 *	@access		public
	 *	@param		string		$path			Path
	 *	@param		bool		$recursive		Scan Folders recursive (default: FALSE)
	 *	@return		array
	 */
	public function getFileList( string $path = '', bool $recursive = FALSE ): array
	{
		return $this->reader->getFileList( $path, $recursive );
	}

	/**
	 *	Returns Array of Folders in Path [and nested Folders].
	 *	@access		public
	 *	@param		string		$path			Path
	 *	@param		bool		$recursive		Scan Folders recursive (default: false)
	 *	@return		array
	 */
	public function getFolderList( string $path = '', bool $recursive = FALSE ): array
	{
		return $this->reader->getFolderList( $path, $recursive );
	}

	/**
	 *	Returns a List of all Folders and Files of a Path on FTP Server.
	 *	@access		public
	 *	@param		string		$path			Path
	 *	@param		bool		$recursive		Scan Folders recursive (default: FALSE)
	 *	@return		array
	 */
	public function getList( string $path = '', bool $recursive = FALSE ): array
	{
		return $this->reader->getList( $path, $recursive );
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

	public function getPermissionsAsOctal( string $permissions ): string
	{
		return $this->reader->getPermissionsAsOctal( $permissions );
	}

	public function getResource(): FtpConnection
	{
		return $this->connection->getResource();
	}

	public function isConnected(): bool
	{
		return $this->connection->checkConnection( TRUE, TRUE, FALSE );
	}

	/**
	 *	Copies a File on FTP Server.
	 *	@access		public
	 *	@param		string		$from			Name of Source File
	 *	@param		string		$to				Name of Target File
	 *	@return		bool
	 */
	public function moveFile( string $from, string $to ): bool
	{
		return $this->writer->moveFile( $from, $to );
	}

	/**
	 *	Copies a Folder on FTP Server [recursive].
	 *	@access		public
	 *	@param		string		$from			Name of Source File
	 *	@param		string		$to				Name of Target File
	 *	@return		bool
	 */
	public function moveFolder( string $from, string $to ): bool
	{
		return $this->writer->moveFolder( $from, $to );
	}

	/**
	 *	Transfers a File onto FTP Server.
	 *	@access		public
	 *	@param		string		$localFile		Name of Local File
	 *	@param		string		$globalFile		Name of Target File
	 *	@return		bool
	 */
	public function putFile( string $localFile, string $globalFile ): bool
	{
		return $this->writer->putFile( $localFile, $globalFile );
	}

	/**
	 *	Removes a File.
	 *	@access		public
	 *	@param		string		$fileName		Name of File to be removed
	 *	@return		bool
	 */
	public function removeFile( string $fileName ): bool
	{
		return $this->writer->removeFile( $fileName );
	}

	/**
	 *	Removes a Folder.
	 *	@access		public
	 *	@param		string		$folderName		Name of Folder to be removed
	 *	@return		bool
	 */
	public function removeFolder( string $folderName ): bool
	{
		return $this->writer->removeFolder( $folderName );
	}

	/**
	 *	Renames a File on FTP Server.
	 *	@access		public
	 *	@param		string		$from			Name of Source File
	 *	@param		string		$to				Name of Target File
	 *	@return		bool
	 */
	public function renameFile( string $from, string $to ): bool
	{
		return $this->writer->renameFile( $from, $to );
	}

	/**
	 *	Searchs for File in Path [and nested Folders] [with regular Expression].
	 *	@access		public
	 *	@param		string		$fileName			Name of File to find
	 *	@param		bool		$recursive			Scan Folders recursive (default: FALSE)
	 *	@param		bool		$regular			Search with regular Expression (default: FALSE)
	 *	@return		array
	 */
	public function searchFile( string $fileName, bool $recursive = FALSE, bool $regular = FALSE ): array
	{
		return $this->reader->searchFile( $fileName, $recursive, $regular );
	}

	/**
	 *	Searchs for Folder in Path [and nested Folders] [with regular Expression].
	 *	@access		public
	 *	@param		string		$folderName			Name of Folder to find
	 *	@param		bool		$recursive			Scan Folders recursive (default: FALSE)
	 *	@param		bool		$regular			Search with regular Expression (default: FALSE)
	 *	@return		array
	 */
	public function searchFolder( string $folderName, bool $recursive = FALSE, bool $regular = FALSE ): array
	{
		return $this->reader->searchFolder( $folderName, $recursive, $regular );
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
