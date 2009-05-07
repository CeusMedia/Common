<?php
import( 'de.ceus-media.net.ftp.Basic' );
/**
 *	Extended FTP Connection.
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
 *	@extends		Net_FTP_Basic
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.03.2006
 *	@version		0.6
 */
/**
 *	Extended FTP Connection.
 *	@package		net.ftp
 *	@extends		Net_FTP_Basic
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.03.2006
 *	@version		0.6
 *	@deprecated		in 0.7
 */
class Net_FTP_Extended extends Net_FTP_Basic
{
	/**
	 *	Copies a File on FTP Server.
	 *	@access		public
	 *	@param		string		$from		Name of Source File
	 *	@param		string		$to			Name of Target File
	 *	@return		bool
	 */
	public function copyFile( $from, $to )
	{
		if( $this->checkConnection() )
		{
			$temp	= uniqid( time() ).".temp";
			if( $this->getFile( $from, $temp ) )
			{
				if( $this->putFile( $temp, $to ) )
				{
					unlink( $temp );
					return true;
				}
				unlink( $temp );
				trigger_error( "File '".$from."' could not be stored", $this->error );
				return false;
			}
			trigger_error( "File '".$from."' could not be received", $this->error );
			return false;
		}
		return false;
	}
	
	/**
	 *	Copies a Folder on FTP Server [recursive].
	 *	@access		public
	 *	@param		string		$from			Name of Source File
	 *	@param		string		$to				Name of Target File
	 *	@param		bool		$recursive		Copy Folder Content recursive (default: true)
	 *	@return		bool
	 */
	public function copyFolder( $from, $to, $recursive = true )
	{
		if( $this->checkConnection() )
		{
			$this->createFolder( $to );
			if( $recursive )
			{
				$list	= $this->getList( $from, true );
				foreach( $list as $entry )
				{
					if( $entry['isdir'] )
						$this->createFolder( $to."/".$entry['name'] );
					else
						$this->copyFile( $from."/".$entry['name'], $to."/".$entry['name'] );
				}
			}
			return true;
		}
		return false;
	}
	
	/**
	 *	Returns Array of Files in Path [and nested Folders].
	 *	@access		public
	 *	@param		string		$path			Path
	 *	@param		bool		$recursive		Scan Folders recursive (default: false)
	 *	@return		array
	 */
	public function getFiles( $path = "", $recursive = false )
	{
		if( $this->checkConnection() )
		{
			$results	= array();
			$list		= $this->getList( $path, $recursive );
			foreach( $list as $entry )
			{
				if( !preg_match( "@/?[.]{1,2}$@", $entry['name'] ) )
				{
					if( !$entry['isdir'] )
						$results[]	= $entry;
				}
			}
			return $results;
		}
		return array();
	}
	
	/**
	 *	Returns Array of Folders in Path [and nested Folders].
	 *	@access		public
	 *	@param		string		$path			Path
	 *	@param		bool		$recursive		Scan Folders recursive (default: false)
	 *	@return		array
	 */
	public function getFolders( $path = "", $recursive = false )
	{
		if( $this->checkConnection() )
		{
			$results	= array();
			$list		= $this->getList( $path, $recursive );
			foreach( $list as $entry )
			{
				if( !preg_match( "@/?[.]{1,2}$@", $entry['name'] ) )
				{
					if( $entry['isdir'] )
						$results[]	= $entry;
				}
			}
			return $results;
		}
		return array();
	}
	
	/**
	 *	Returns a List of all Folders an Files of a Path on FTP Server.
	 *	@access		public
	 *	@param		string		$path			Path
	 *	@param		bool		$recursive		Scan Folders recursive (default: false)
	 *	@return		array
	 */
	public function getList( $path = "", $recursive = false )
	{
		if( $this->checkConnection() )
		{
			$parsed	= array();
			if( !$path )
				$path	= $this->getPath();
			$list	= ftp_rawlist( $this->conn, $path );
			foreach( $list as $current )
			{
				$data	= $this->parseListEntry( $current );
				if( count( $data ) )
				{
					$parsed[]	= $data;
					if( $recursive && $data['isdir'] && $data['name'] != "." && $data['name'] != ".." )
					{
						$nested	= $this->getList( $path."/".$data['name'], true );
						foreach( $nested as $entry )
						{
							$entry['name']	= $data['name']."/".$entry['name'];
							$parsed[]	= $entry;
						}
					}
				}
			}
			return $parsed;
		}
		return array();
	}

	/**
	 *	Copies a File on FTP Server.
	 *	@access		public
	 *	@param		string		$from		Name of Source File
	 *	@param		string		$to			Name of Target File
	 *	@return		bool
	 */
	public function moveFile( $from, $to )
	{
		if( $this->checkConnection() )
		{
			if( @ftp_rename( $this->conn, $from, $to ) )
				return true;
			trigger_error( "File '".$from."' could not be moved", $this->error );
			return false;
		}
		return false;
	}
	
	/**
	 *	Copies a Folder on FTP Server [recursive].
	 *	@access		public
	 *	@param		string		$from			Name of Source File
	 *	@param		string		$to				Name of Target File
	 *	@param		bool		$recursive		Copy Folder Content recursive (default: true)
	 *	@return		bool
	 */
	public function moveFolder( $from, $to )
	{
		if( $this->checkConnection() )
		{
			if( ftp_rename( $this->conn, $from, $to ) )
				return true;
			trigger_error( "Folder '".$from."' could not be moved", $this->error );
			return false;
		}
		return false;
	}
	
	/**
	 *	Removes a Folder.
	 *	@access		public
	 *	@param		string		$foldername		Name of Folder to be removed
	 *	@param		bool		$recursive		Remove recursive (default: false)
	 *	@return		bool
	 */
	public function removeFolder( $foldername, $recursive = false )
	{
		if( $this->checkConnection() )
		{
			if( $recursive )
			{
				$list	= $this->getList( $foldername );
				foreach( $list as $entry )
				{
					if( $entry['name'] != "." && $entry['name'] != ".." )
					{
						if( $entry['isdir'] )
							$this->removeFolder( $foldername."/".$entry['name'], true );
						else
							$this->removeFile( $foldername."/".$entry['name'] );
					}
				}
				$this->removeFolder( $foldername );
				return true;
			}
			else if( @ftp_rmdir( $this->conn, $foldername ) )
				return true;
			trigger_error( "Folder '".$foldername."' could not be removed", $this->error );
		}
		return false;
	}

	/**
	 *	Searchs for File in Path [and nested Folders] [with regular Expression].
	 *	@access		public
	 *	@param		string		$filename			Name of File to find
	 *	@param		bool		$recursive		Scan Folders recursive (default: false)
	 *	@param		bool		$regular			Search with regular Expression (default: false)
	 *	@return		array
	 */
	public function searchFile( $filename = "", $recursive = false, $regular = false )
	{
		if( $this->checkConnection() )
		{
			$results	= array();
			$list		= $this->getFiles( $this->getPath(), $recursive );
			foreach( $list as $entry )
			{
				if( !$entry['isdir'] )
				{
					if( $regular )
					{
						if( preg_match( $filename, $entry['name'] ) )
							$results[]	= $entry;
					}
					else if( basename( $entry['name'] ) == $filename )
						$results[]	= $entry;
				}
			}
			return $results;
		}
		return array();
	}

	/**
	 *	Searchs for Folder in Path [and nested Folders] [with regular Expression].
	 *	@access		public
	 *	@param		string		$foldername		Name of Folder to find
	 *	@param		bool		$recursive		Scan Folders recursive (default: false)
	 *	@param		bool		$regular			Search with regular Expression (default: false)
	 *	@return		array
	 */
	public function searchFolder( $foldername = "", $recursive = false, $regular = false )
	{
		if( $this->checkConnection() )
		{
			$results	= array();
			$list		= $this->getFolders( $this->getPath(), $recursive );
			foreach( $list as $entry )
			{
				if( $entry['isdir'] )
				{
					if( $regular )
					{
						if( preg_match( $foldername, $entry['name'] ) )
							$results[]	= $entry;
					}
					else if( basename( $entry['name'] ) == $foldername )
						$results[]	= $entry;
				}
			}
			return $results;
		}
		return array();
	}
}
?>