<?php
/**
 *	Writer for FTP Connections.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@category		cmClasses
 *	@package		Net.FTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.07.2008
 *	@version		$Id$
 */
/**
 *	Writer for FTP Connections.
 *	@category		cmClasses
 *	@package		Net.FTP
 *	@uses			Net_FTP_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.07.2008
 *	@version		$Id$
 */
class Net_FTP_Writer
{
	/**	@var		Net_FTP_Connection	$connection		FTP connection object */
	protected $connection;

	/**
	 *	Constructor
	 *	@access		public
	 *	@param		Net_FTP_Connection	$connection		FTP connection object
	 *	@return		void
	 */
	public function __construct( Net_FTP_Connection $connection )
	{
		$this->connection	= $connection;
	}

	/**
	 *	Changes Rights of File or Folders on FTP Server.
	 *	@access		public
	 *	@param		string		$fileName		Name of file to change rights for
	 *	@param		integer		$mode			Mode of rights (i.e. 0755)
	 *	@return		integer		Set permissions as integer
	 *	@throws		RuntimeException if impossible to change rights
	 */
	public function changeRights( $fileName, $mode )
	{
		if( !is_int( $mode ) )
			throw new InvalidArgumentException( 'Mode must be an integer, recommended to be given as octal value' );
		$this->connection->checkConnection();
		$result = @ftp_chmod( $this->connection->getResource(), $mode, $fileName );
		if( FALSE === $result )
			throw new RuntimeException( 'Changing rights for "'.$fileName.'" is not possible' );
		return $result;
	}
	
	/**
	 *	Copies a File on FTP Server.
	 *	@access		public
	 *	@param		string		$from			Name of source file
	 *	@param		string		$to				Name of target file
	 *	@return		boolean
	 */
	public function copyFile( $from, $to )
	{
		$this->connection->checkConnection();
		$temp	= uniqid( time() ).".temp";
		$reader	= new Net_FTP_Reader( $this->connection );
		$reader->setPath( $this->getPath() );
		if( !$reader->getFile( $from, $temp ) )
			throw new RuntimeException( 'File "'.$from.'" could not be received.' );
		if( !$this->putFile( $temp, $to ) )
		{
			unlink( $temp );
			throw new RuntimeException( 'File "'.$from.'" could not be stored.' );
		}
		unlink( $temp );
		return TRUE;
	}
	
	/**
	 *	Copies a Folder on FTP Server [recursive].
	 *	@access		public
	 *	@param		string		$from			Name of source file
	 *	@param		string		$to				Name of target file
	 *	@return		boolean
	 */
	public function copyFolder( $from, $to )
	{
		$this->connection->checkConnection();
		$this->createFolder( $to );
		$reader	= new Net_FTP_Reader( $this->connection );
		$list	= $reader->getList( $from, TRUE );
		foreach( $list as $entry )
		{
			if( $entry['isdir'] )
				$this->createFolder( $to."/".$entry['name'] );
			else
				$this->copyFile( $from."/".$entry['name'], $to."/".$entry['name'] );
		}
		return TRUE;
	}
	
	/**
	 *	Creates a Folder on FTP Server.
	 *	@access		public
	 *	@param		string		$folderName		Name of folder to be created
	 *	@return		boolean
	 */
	public function createFolder( $folderName )
	{
		$this->connection->checkConnection();
		return (bool) @ftp_mkdir( $this->connection->getResource(), $folderName );
	}
	
	/**
	 *	Returns current Path on Server.
	 *	@access		public
	 *	@return		string
	 */
	public function getPath()
	{
		return $this->connection->getPath();
	}

	/**
	 *	Copies a File on FTP Server.
	 *	@access		public
	 *	@param		string		$from			Name of source file
	 *	@param		string		$to				Name of target file
	 *	@return		boolean
	 */
	public function moveFile( $from, $to )
	{
		$this->connection->checkConnection();
		return @ftp_rename( $this->connection->getResource(), $from, $to );
	}
	
	/**
	 *	Copies a Folder on FTP Server [recursive].
	 *	@access		public
	 *	@param		string		$from			Name of source folder
	 *	@param		string		$to				Name of target folder
	 *	@return		boolean
	 */
	public function moveFolder( $from, $to )
	{
		$this->connection->checkConnection();
		if( ftp_size( $this->connection->getResource(), $from ) != -1 )
			throw new RuntimeException( 'Folder "'.$from.'" is not existing.' );
		return @ftp_rename( $this->connection->getResource(), $from, $to );
	}
	
	/**
	 *	Transferes a File onto FTP Server.
	 *	@access		public
	 *	@param		string		$fileName		Name of local file
	 *	@param		string		$target			Name of target file
	 *	@return		boolean
	 */
	public function putFile( $fileName, $target )
	{
		$this->connection->checkConnection();
		return @ftp_put( $this->connection->getResource(), $target, $fileName, $this->connection->mode );
	}
	
	/**
	 *	Removes a File.
	 *	@access		public
	 *	@param		string		$fileName		Name of file to be removed
	 *	@return		boolean
	 */
	public function removeFile( $fileName )
	{
		$this->connection->checkConnection();
		return @ftp_delete( $this->connection->getResource(), $fileName );
	}
	
	/**
	 *	Removes a Folder.
	 *	@access		public
	 *	@param		string		$folderName		Name of folder to be removed
	 *	@return		boolean
	 */
	public function removeFolder( $folderName )
	{
		$this->connection->checkConnection();
		$reader	= new Net_FTP_Reader( $this->connection );
		$list	= $reader->getList( $folderName );
		foreach( $list as $entry )
		{
			if( $entry['name'] != "." && $entry['name'] != ".." )
			{
				if( $entry['isdir'] )
					$this->removeFolder( $folderName."/".$entry['name'], TRUE );
				else
					$this->removeFile( $folderName."/".$entry['name'] );
			}
		}
		return @ftp_rmdir( $this->connection->getResource(), $folderName );
	}

	/**
	 *	Renames a File on FTP Server.
	 *	@access		public
	 *	@param		string		$from			Name of source file
	 *	@param		string		$to				Name of target file
	 *	@return		boolean
	 */
	public function renameFile( $from, $to )
	{
		$this->connection->checkConnection();
		return @ftp_rename( $this->connection->getResource(), $from, $to );
	}

	/**
	 *	Sets current Path on Server.
	 *	@access		public
	 *	@param		string		$path			Path to go to
	 *	@return		boolean
	 */
	public function setPath( $path )
	{
		return $this->connection->setPath( $path );
	}
}
?>