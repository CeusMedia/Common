<?php
/**
 *	Basic FTP Connection.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		net.ftp
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.03.2006
 *	@version		$Id$
 */
/**
 *	Basic FTP Connection.
 *	@category		cmClasses
 *	@package		net.ftp
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.03.2006
 *	@version		$Id$
 *	@deprecated		in 0.7
 */
class Net_FTP_Basic
{
	/**	@var	bool			$auth		Indicator of Authentification */
	protected $auth				= false;
	/**	@var	resource		$conn		Resource ID of Connection (Stream in PHP5) */
	protected $conn				= false;
	/**	@var	int				$error		Error Mode */
	protected $error			= E_USER_WARNING;
	/**	@var	int				$_mode		FTP Transfer Mode */
	protected $mode				= FTP_BINARY;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$host		Host Name
	 *	@param		int		$port		Service Port
	 *	@return		void
	 */
	public function __construct( $host, $port = 21 )
	{
		$this->connect( $host, $port );
	}
	
	/**
	 *	Changes Rights of File or Folders on FTP Server.
	 *	@access		public
	 *	@param		string	$filename		Name of File to change Rights for
	 *	@param		int		$mode		Mode of Rights (i.e. 755)	
	 *	@return		bool
	 */
	public function changeRights( $filename, $mode )
	{
		if( $this->checkConnection() )
		{
			if( @ftp_chmod( $this->conn, $mode, $filename ) )
				return true;
			trigger_error( "Rights of File '".$filename."' could not be changed", $this->error );
		}
		return false;
	}

	/**
	 *	Indicated State of Connection and Authentification.
	 *	@access		protected
	 *	@param		bool		$conn			Check Connection
	 *	@param		bool		$auth			Check Authentification
	 *	@return		bool
	 */
	protected function checkConnection( $conn = true, $auth = true )
	{
		if( $conn && !$this->conn )
			throw new Exception( "No Connection to FTP Server established." );
		if( $auth && !$this->auth )
			throw new Exception( "Not authenticated onto FTP Server." );
		return true;
	}
	
	/**
	 *	Closes FTP Connection.
	 *	@access		public
	 *	@return		bool
	 */
	public function close()
	{
		if( $this->checkConnection( true, false ) )
		{
			if( @ftp_quit( $this->conn ) )
			{
				$this->auth	= false;
				$this->conn	= false;
				return true;
			}
			trigger_error( "Connection to FTP Server could not be closed", $this->error );
		}
		return false;
	}
	
	/**
	 *	Opens Connection to FTP Server.
	 *	@access		public
	 *	@param		string	$host		Host Name
	 *	@param		int		$port		Service Port
	 *	@return		bool
	 */
	public function connect( $host, $port = 21 )
	{
		if( $this->conn	= ftp_connect( $host, $port ) )
			return true;
		trigger_error( "FTP Connection to ".$host." @ Port ".$port." failed", $this->error );
		return false;
	}
	

	/**
	 *	Creates a Folder on FTP Server.
	 *	@access		public
	 *	@param		string	$foldername	Name of Folder to be created
	 *	@return		bool
	 */
	public function createFolder( $foldername )
	{
		if( $this->checkConnection() )
		{
			if( @ftp_mkdir( $this->conn, $foldername ) )
				return true;
			trigger_error( "Folder '".$foldername."' could not be created", $this->error );
		}
		return false;
	}
	
	/**
	 *	Transferes a File from FTP Server.
	 *	@access		public
	 *	@param		string	$filename		Name of Remove File
	 *	@param		string	$target		Name of Target File
	 *	@return		bool
	 */
	public function getFile( $filename, $target = "")
	{
		if( $this->checkConnection( true, true ) )
		{
			if( !$target )
				$target	= $filename;
			if( @ftp_get( $this->conn, $target, $filename, $this->mode ) )
				return true;
			trigger_error( "File '".$filename."' could not be received", $this->error );
		}
		return false;
	}
	
	/**
	 *	Returns a List of all Folders an Files of a Path on FTP Server.
	 *	@access		public
	 *	@param		string	$path			Path
	 *	@return		array
	 */
	public function getList( $path = "" )
	{
		if( $this->checkConnection() )
		{
			if( !$path )
				$path	= $this->getPath();
			$list	= ftp_rawlist( $this->conn, $path );
			foreach ($list as $current)
			{
				$data	= $this->parseListEntry( $current );
				if( count( $data ) )
					$parsed[]	= $data;
			}
			return $parsed;
		}
		return array();
	}
	
	/**
	 *	Returns current Path.
	 *	@access		public
	 *	@return		string
	 */
	public function getPath()
	{
		if( $this->checkConnection( true ) )
		{
			if( $path	= @ftp_pwd( $this->conn ) )
				return $path;
			trigger_error( "FTP Login for ".$username." @ ".host."failed", $this->error );
		}
		return "";
	}
	
	/**
	 *	Authenticates FTP Connection.
	 *	@access		public
	 *	@param		string	$username		Username
	 *	@param		string	$password		Password
	 *	@return		bool
	 */
	public function login( $username, $password )
	{
		if( $this->checkConnection( true, false ) )
		{
			if( $this->auth	= @ftp_login( $this->conn, $username, $password ) )
				return true;
			trigger_error( "FTP Login for ".$username." @ ".host."failed", $this->error );
		}
		return false;
	}

	/**
	 *	Parsed List Entry.
	 *	@access		protected
	 *	@param		string	$entry		Entry of List
	 *	@return		array
	 */
	protected function parseListEntry( $entry )
	{
		$data	= array();
		$parts = preg_split("[ ]", $entry, 9, PREG_SPLIT_NO_EMPTY);
		if ($parts[0] != "total")
		{
			$data['isdir']		= $parts[0]{0} === "d";
			$data['perms']		= $parts[0];
			$data['number']		= $parts[1];
			$data['owner']		= $parts[2];
			$data['group']		= $parts[3];
			$data['size']		= $parts[4];
			$data['month']		= $parts[5];
			$data['day']		= $parts[6];
			$data['time/year']	= $parts[7];
			$data['name']		= $parts[8];
			return $data;
		}
		return array();
	}
	
	/**
	 *	Transferes a File onto FTP Server.
	 *	@access		public
	 *	@param		string	$filename			Name of Local File
	 *	@param		string	$target			Name of Target File
	 *	@return		bool
	 */
	public function putFile( $filename, $target)
	{
		if( $this->checkConnection() )
		{
			if( @ftp_put( $this->conn, $target, $filename, $this->mode ) )
				return true;
			trigger_error( "File '".$filename."' could not be transfered", $this->error );
		}
		return false;
	}
	
	/**
	 *	Removes a File.
	 *	@access		public
	 *	@param		string	$filename			Name of File to be removed
	 *	@return		bool
	 */
	public function removeFile( $filename )
	{
		if( $this->checkConnection() )
		{
			if( @ftp_delete( $this->conn, $filename ) )
				return true;
			trigger_error( "File '".$filename."' could not be removed", $this->error );
		}
		return false;
	}
	
	/**
	 *	Removes a Folder.
	 *	@access		public
	 *	@param		string	$foldername		Name of Folder to be removed
	 *	@return		bool
	 */
	public function removeFolder( $foldername )
	{
		if( $this->checkConnection() )
		{
			if( @ftp_rmdir( $this->conn, $foldername ) )
				return true;
			trigger_error( "Folder '".$foldername."' could not be removed", $this->error );
		}
		return false;
	}
	
	/**
	 *	Renames a File on FTP Server.
	 *	@access		public
	 *	@param		string	$from		Name of Source File
	 *	@param		string	$to			Name of Target File
	 *	@return		bool
	 */
	public function renameFile( $from, $to )
	{
		if( $this->checkConnection() )
		{
			if( @ftp_rename( $this->conn, $from, $to ) )
				return true;
			trigger_error( "File '".$from."' could not be renamed", $this->error );
		}
		return false;
	}
	
	/**
	 *	Set current Path.
	 *	@access		public
	 *	@param		string	$path		Path to change to
	 *	@return		bool
	 */
	public function setPath( $path )
	{
		if( $this->checkConnection() )
		{
			if( @ftp_chdir( $this->conn, $path ) )
				return true;
			trigger_error( "Path could not be set to '".$path."'", $this->error );
		}
		return false;
	}
	
	/**
	 *	Set Transfer Mode between binary and ascii.
	 *	@access		public
	 *	@param		string	$host		Host Name
	 *	@param		int		$port		Service Port
	 *	@return		void
	 */
	public function setErrorMode( $int )
	{
		$this->error	= $int;
	}
	
	/**
	 *	Set Transfer Mode between binary and ascii.
	 *	@access		public
	 *	@param		int		$mode	Transfer Mode (FTP_BINARY|FTP_ASCII)
	 *	@return		bool
	 */
	public function setMode( $mode )
	{
		if( $mode == FTP_BINARY || $mode == FTP_ASCII )
		{
			$this->mode	= $mode;
			return true;
		}
		trigger_error( "Mode '".$mode."' is not supported. Use FTP_BINARY or FTP_ASCII instead", $this->error );
		return false;
	}
}	
?>