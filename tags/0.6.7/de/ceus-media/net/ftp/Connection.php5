<?php
/**
 *	Basic FTP Connection.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.03.2006
 *	@version		0.6
 */
/**
 *	Basic FTP Connection.
 *	@package		net.ftp
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.03.2006
 *	@version		0.6
 *	@todo			implement SSL Support
 */
class Net_FTP_Connection
{
	/**	@var		bool		$auth			Indicator of Authentification */
	protected $auth				= FALSE;
	/**	@var		resource	$resource		Resource ID of Connection (Stream in PHP5) */
	protected $resource			= NULL;
	/**	@var		string		$host			Host Name */
	protected $host				= "";
	/**	@var		int			$port			Protocol Port */
	protected $port				= 21;
	/**	@var		int			$mode			FTP Transfer Mode */
	public $mode				= FTP_BINARY;

	/**
	 *	Constructor, connects to FTP Server.
	 *	@access		public
	 *	@param		string		$host			Host Name
	 *	@param		int			$port			Service Port
	 *	@param		int			$timeout		Timeout in Seconds
	 *	@return		void
	 */
	public function __construct( $host, $port = 21, $timeout = 90 )
	{
		$this->connect( $host, $port, $timeout );
	}

	/**
	 *	Destructor, closes open Connection to FTP Server.
	 *	@access		public
	 *	@return		void
	 */
	public function __destruct()
	{
		$this->close( TRUE );
	}

	/**
	 *	Indicated State of Connection and Authentification.
	 *	@access		public
	 *	@param		bool		$checkResource			Flag: Check Connection
	 *	@param		bool		$checkAuthentication	Flag: Check Authentification
	 *	@return		void
	 */
	public function checkConnection( $checkResource = TRUE, $checkAuthentication = TRUE )
	{
		if( $checkResource && !$this->resource )
			throw new RuntimeException( "No Connection to FTP Server established." );
		if( $checkAuthentication && !$this->auth )
			throw new RuntimeException( "Not authenticated onto FTP Server." );
	}
	
	/**
	 *	Closes FTP Connection.
	 *	@access		public
	 *	@return		bool
	 */
	public function close( $quit = FALSE )
	{
		if( !$quit )
			$this->checkConnection( TRUE, FALSE );
		if( !@ftp_quit( $this->resource ) )
			return FALSE;
		$this->auth		= FALSE;
		$this->resource	= NULL;
		return TRUE;
	}
	
	/**
	 *	Opens Connection to FTP Server.
	 *	@access		public
	 *	@param		string		$host			Host Name
	 *	@param		int			$port			Service Port
	 *	@param		int			$timeout		Timeout in Seconds
	 *	@return		bool
	 */
	public function connect( $host, $port = 21, $timeout = 10 )
	{
		$resource	= @ftp_connect( $host, $port, $timeout );
		if( !$resource )
			return FALSE;
		$this->host	= $host;
		$this->port	= $port;
		$this->resource	= $resource;
		return TRUE;
	}

	/**
	 *	Returns FTP Server Host.
	 *	@access		public
	 *	@return		resource
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 *	Returns FTP Server Port.
	 *	@access		public
	 *	@return		resource
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 *	Returns current Path.
	 *	@access		public
	 *	@return		string
	 */
	public function getPath()
	{
		$this->checkConnection();
		$path = @ftp_pwd( $this->resource );
		return (string) $path;
	}

	/**
	 *	Returns FTP Connection Resource.
	 *	@access		public
	 *	@return		resource
	 */
	public function getResource()
	{
		return $this->resource;
	}

	/**
	 *	Returns current set Timeout in Seconds.
	 *	@access		public
	 *	@return		int
	 */
	public function getTimeout()
	{
		return ftp_get_option( $this->resource, FTP_TIMEOUT_SEC );
	}
	
	/**
	 *	Authenticates FTP Connection.
	 *	@access		public
	 *	@param		string		$username		Username
	 *	@param		string		$password		Password
	 *	@return		bool
	 */
	public function login( $username, $password )
	{
		$this->checkConnection( TRUE, FALSE );
		if( !@ftp_login( $this->resource, $username, $password ) )
			return FALSE;
		$this->auth	= TRUE;
		return TRUE;
	}

	/**
	 *	Set Transfer Mode between binary and ascii.
	 *	@access		public
	 *	@param		int			$mode			Transfer Mode (FTP_BINARY|FTP_ASCII)
	 *	@return		bool
	 */
	public function setMode( $mode )
	{
		if( $mode != FTP_BINARY && $mode != FTP_ASCII )
			return FALSE;
		$this->mode	= $mode;
		return TRUE;
	}
	
	/**
	 *	Set current Path.
	 *	@access		public
	 *	@param		string		$path			Path to change to
	 *	@return		bool
	 */
	public function setPath( $path )
	{
		$this->checkConnection();
		return @ftp_chdir( $this->resource, $path );
	}

	/**
	 *	Sets Timeout for all following Operations.
	 *	@access		public
	 *	@param		int			$seconds		Timeout in Seconds
	 *	@return		bool
	 */
	public function setTimeout( $seconds )
	{
		return @ftp_set_option( $this->resource, FTP_TIMEOUT_SEC, $seconds );
	}
}	
?>