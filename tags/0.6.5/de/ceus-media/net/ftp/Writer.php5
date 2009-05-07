<?php
/**
 *	Writer for FTP Connections.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		net.ftp
 *	@uses			Net_FTP_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.07.2008
 *	@version		0.6
 */
import( 'de.ceus-media.net.ftp.Reader' );
/**
 *	Writer for FTP Connections.
 *	@package		net.ftp
 *	@uses			Net_FTP_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.07.2008
 *	@version		0.6
 */
class Net_FTP_Writer
{
	/**	@var		Net_FTP_Connection	$connection		FTP Connection Object */
	protected $ftp;

	/**
	 *	Constructor
	 *	@access		public
	 *	@param		Net_FTP_Connection	$connection		FTP Connection Object
	 *	@return		void
	 */
	public function __construct( $connection )
	{
		$this->ftp	= $connection;
	}

	/**
	 *	Changes Rights of File or Folders on FTP Server.
	 *	@access		public
	 *	@param		string		$fileName		Name of File to change Rights for
	 *	@param		int			$mode			Mode of Rights (i.e. 755)	
	 *	@return		bool
	 */
	public function changeRights( $fileName, $mode )
	{
		$this->ftp->checkConnection();
		return (bool) @ftp_chmod( $this->ftp->getResource(), $mode, $fileName );
	}
	
	/**
	 *	Copies a File on FTP Server.
	 *	@access		public
	 *	@param		string		$from			Name of Source File
	 *	@param		string		$to				Name of Target File
	 *	@return		bool
	 */
	public function copyFile( $from, $to )
	{
		$this->ftp->checkConnection();
		$temp	= uniqid( time() ).".temp";
		$reader	= new Net_FTP_Reader( $this->ftp );
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
	 *	@param		string		$from			Name of Source File
	 *	@param		string		$to				Name of Target File
	 *	@return		bool
	 */
	public function copyFolder( $from, $to )
	{
		$this->ftp->checkConnection();
		$this->createFolder( $to );
		$reader	= new Net_FTP_Reader( $this->ftp );
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
	 *	@param		string		$folderName		Name of Folder to be created
	 *	@return		bool
	 */
	public function createFolder( $folderName )
	{
		$this->ftp->checkConnection();
		return (bool) @ftp_mkdir( $this->ftp->getResource(), $folderName );
	}
	
	/**
	 *	Returns current Path on Server.
	 *	@access		public
	 *	@return		string
	 */
	public function getPath()
	{
		return $this->ftp->getPath();
	}

	/**
	 *	Copies a File on FTP Server.
	 *	@access		public
	 *	@param		string		$from			Name of Source File
	 *	@param		string		$to				Name of Target File
	 *	@return		bool
	 */
	public function moveFile( $from, $to )
	{
		$this->ftp->checkConnection();
		return @ftp_rename( $this->ftp->getResource(), $from, $to );
	}
	
	/**
	 *	Copies a Folder on FTP Server [recursive].
	 *	@access		public
	 *	@param		string		$from			Name of Source File
	 *	@param		string		$to				Name of Target File
	 *	@return		bool
	 */
	public function moveFolder( $from, $to )
	{
		$this->ftp->checkConnection();
		if( ftp_size( $this->ftp->getResource(), $from ) != -1 )
			throw new RuntimeException( 'Folder "'.$from.'" is not existing.' );
		return @ftp_rename( $this->ftp->getResource(), $from, $to );
	}
	
	/**
	 *	Transferes a File onto FTP Server.
	 *	@access		public
	 *	@param		string		$fileName		Name of Local File
	 *	@param		string		$target			Name of Target File
	 *	@return		bool
	 */
	public function putFile( $fileName, $target )
	{
		$this->ftp->checkConnection();
		return @ftp_put( $this->ftp->getResource(), $target, $fileName, $this->ftp->mode );
	}
	
	/**
	 *	Removes a File.
	 *	@access		public
	 *	@param		string		$fileName		Name of File to be removed
	 *	@return		bool
	 */
	public function removeFile( $fileName )
	{
		$this->ftp->checkConnection();
		return @ftp_delete( $this->ftp->getResource(), $fileName );
	}
	
	/**
	 *	Removes a Folder.
	 *	@access		public
	 *	@param		string		$folderName		Name of Folder to be removed
	 *	@return		bool
	 */
	public function removeFolder( $folderName )
	{
		$this->ftp->checkConnection();
		$reader	= new Net_FTP_Reader( $this->ftp );
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
		return @ftp_rmdir( $this->ftp->getResource(), $folderName );
	}

	/**
	 *	Renames a File on FTP Server.
	 *	@access		public
	 *	@param		string		$from			Name of Source File
	 *	@param		string		$to				Name of Target File
	 *	@return		bool
	 */
	public function renameFile( $from, $to )
	{
		$this->ftp->checkConnection();
		return @ftp_rename( $this->ftp->getResource(), $from, $to );
	}

	/**
	 *	Sets current Path on Server.
	 *	@access		public
	 *	@param		string		$path			Path to go to
	 *	@return		bool
	 */
	public function setPath( $path )
	{
		return $this->ftp->setPath( $path );
	}
}
?>