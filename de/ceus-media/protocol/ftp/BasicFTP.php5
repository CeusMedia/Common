<?php
/**
 *	Basic FTP Connection.
 *	@package		protocol
 *	@subpackage		ftp
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2006
 *	@version		0.1
 */
/**
 *	Basic FTP Connection.
 *	@package		protocol
 *	@subpackage		ftp
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2006
 *	@version		0.1
 *	@todo			Finish Implementation (mdtm,size,site,raw,pasv,exec)
 */
class BasicFTP
{
	/**	@var	bool			$_auth		Indicator of Authentification */
	var $_auth	= false;
	/**	@var	resource		$_conn		Resource ID of Connection (Stream in PHP5) */
	var $_conn	= false;
	/**	@var	int			$_error		Error Mode */
	var $_error	= E_USER_WARNING;
	/**	@var	int			$_mode		FTP Transfer Mode */
	var $_mode	= FTP_BINARY;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$host		Host Name
	 *	@param		int		$port		Service Port
	 *	@return		void
	 */
	public function __construct( $host, $port = 21 )
	{
		$this->_host	= $host;
		$this->_port	= $port;
		$this->connect( $host, $port );
	}
	
	/**
	 *	Changes Rights of File or Folders on FTP Server.
	 *	@access		public
	 *	@param		string	$filename		Name of File to change Rights for
	 *	@param		int		$mode		Mode of Rights (i.e. 755)	
	 *	@return		bool
	 */
	function changeRights( $filename, $mode )
	{
		if( $this->_checkConnection() )
		{
			if( @ftp_chmod( $this->_conn, $mode, $filename ) )
				return true;
			trigger_error( "Rights of File '".$filename."' could not be changed", $this->_error );
		}
		return false;
	}

	/**
	 *	Closes FTP Connection.
	 *	@access		public
	 *	@return		bool
	 */
	function close()
	{
		if( $this->_checkConnection( true, false ) )
		{
			if( @ftp_quit( $this->_conn ) )
			{
				$this->_auth	= false;
				$this->_conn	= false;
				return true;
			}
			trigger_error( "Connection to FTP Server could not be closed", $this->_error );
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
	function connect( $host, $port = 21 )
	{
		if( $this->_conn	= ftp_connect( $host, $port ) )
			return true;
		trigger_error( "FTP Connection to ".$host." @ Port ".$port." failed", $this->_error );
		return false;
	}
	

	/**
	 *	Creates a Folder on FTP Server.
	 *	@access		public
	 *	@param		string	$foldername	Name of Folder to be created
	 *	@return		bool
	 */
	function createFolder( $foldername )
	{
		if( $this->_checkConnection() )
		{
			if( @ftp_mkdir( $this->_conn, $foldername ) )
				return true;
			trigger_error( "Folder '".$foldername."' could not be created", $this->_error );
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
	function getFile( $filename, $target = "")
	{
		if( $this->_checkConnection( true, true ) )
		{
			if( !$target )
				$target	= $filename;
			if( @ftp_get( $this->_conn, $target, $filename, $this->_mode ) )
				return true;
			trigger_error( "File '".$filename."' could not be received", $this->_error );
		}
		return false;
	}
	
	/**
	 *	Returns a List of all Folders an Files of a Path on FTP Server.
	 *	@access		public
	 *	@param		string	$path			Path
	 *	@return		array
	 */
	function getList( $path = "" )
	{
		if( $this->_checkConnection() )
		{
			if( !$path )
				$path	= $this->getPath();
			$list	= ftp_rawlist( $this->_conn, $path );
			foreach ($list as $current)
			{
				$data	= $this->_parseListEntry( $current );
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
	function getPath()
	{
		if( $this->_checkConnection( true ) )
		{
			if( $path	= @ftp_pwd( $this->_conn ) )
				return $path;
			trigger_error( "FTP Login for ".$username." @ ".host."failed", $this->_error );
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
	function login( $username, $password )
	{
		if( $this->_checkConnection( true, false ) )
		{
			if( $this->_auth	= @ftp_login( $this->_conn, $username, $password ) )
				return true;
			trigger_error( "FTP Login for ".$username." @ ".host."failed", $this->_error );
		}
		return false;
	}
	
	/**
	 *	Transferes a File onto FTP Server.
	 *	@access		public
	 *	@param		string	$filename			Name of Local File
	 *	@param		string	$target			Name of Target File
	 *	@return		bool
	 */
	function putFile( $filename, $target)
	{
		if( $this->_checkConnection() )
		{
			if( @ftp_put( $this->_conn, $target, $filename, $this->_mode ) )
				return true;
			trigger_error( "File '".$filename."' could not be transfered", $this->_error );
		}
		return false;
	}
	
	/**
	 *	Removes a File.
	 *	@access		public
	 *	@param		string	$filename			Name of File to be removed
	 *	@return		bool
	 */
	function removeFile( $filename )
	{
		if( $this->_checkConnection() )
		{
			if( @ftp_delete( $this->_conn, $filename ) )
				return true;
			trigger_error( "File '".$filename."' could not be removed", $this->_error );
		}
		return false;
	}
	
	/**
	 *	Removes a Folder.
	 *	@access		public
	 *	@param		string	$foldername		Name of Folder to be removed
	 *	@return		bool
	 */
	function removeFolder( $foldername )
	{
		if( $this->_checkConnection() )
		{
			if( @ftp_rmdir( $this->_conn, $foldername ) )
				return true;
			trigger_error( "Folder '".$foldername."' could not be removed", $this->_error );
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
	function renameFile( $from, $to )
	{
		if( $this->_checkConnection() )
		{
			if( @ftp_rename( $this->_conn, $from, $to ) )
				return true;
			trigger_error( "File '".$from."' could not be renamed", $this->_error );
		}
		return false;
	}
	
	/**
	 *	Set current Path.
	 *	@access		public
	 *	@param		string	$path		Path to change to
	 *	@return		bool
	 */
	function setPath( $path )
	{
		if( $this->_checkConnection() )
		{
			if( @ftp_chdir( $this->_conn, $path ) )
				return true;
			trigger_error( "Path could not be set to '".$path."'", $this->_error );
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
	function setErrorMode( $int )
	{
		$this->_error	= $int;
	}
	
	/**
	 *	Set Transfer Mode between binary and ascii.
	 *	@access		public
	 *	@param		int		$mode	Transfer Mode (FTP_BINARY|FTP_ASCII)
	 *	@return		bool
	 */
	function setMode( $mode )
	{
		if( $mode == FTP_BINARY || $mode == FTP_ASCII )
		{
			$this->_mode	= $mode;
			return true;
		}
		trigger_error( "Mode '".$mode."' is not supported. Use FTP_BINARY or FTP_ASCII instead", $this->_error );
		return false;
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Indicated State of Connection and Authentification.
	 *	@access		private
	 *	@param		bool		$conn			Check Connection
	 *	@param		bool		$auth			Check Authentification
	 *	@return		bool
	 */
	function _checkConnection( $conn = true, $auth = true )
	{
		if( $conn && !$this->_conn )
		{
			trigger_error( "No Connection to FTP Server opened yet", $this->_error );
			return false;
		}
		if( $auth && !$this->_auth )
		{
			trigger_error( "No Connection to FTP Server opened or not logged in yet", $this->_error );
			return false;
		}
		return true;
	}
	
	/**
	 *	Parsed List Entry.
	 *	@access		private
	 *	@param		string	$entry		Entry of List
	 *	@return		array
	 */
	function _parseListEntry( $entry )
	{
		$data	= array();
		$parts = preg_split("[ ]", $entry, 9, PREG_SPLIT_NO_EMPTY);
		if ($parts[0] != "total")
		{
			$data['isdir']		= $parts[0]{0} === "d";
			$data['perms']		= $parts[0];
			$data['number']	= $parts[1];
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
}

/**
 *	Function FTP_CHMOD for PHP4.
 *	@param		resource	$ftpstream		Resource ID of FTP Connection
 *	@param		int		$chmod			Mode of Rights as octal (i.e. 0755)	
 *	@param		string	$file				File to change
 *	@return		bool
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		09.03.2006
 *	@version		0.1
 */
if( !function_exists( 'ftp_chmod' ) )
{
	function ftp_chmod( $ftpstream, $chmod, $file )
	{
		if( @ftp_site( $ftpstream, "CHMOD ".$chmod." ".$file ) )
			return true;
		return false;
	}
}	
?>