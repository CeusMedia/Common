<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Basic FTP Connection.
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
 *	@package		CeusMedia_Common_Net_FTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\FTP;

use RuntimeException;

/**
 *	Basic FTP Connection.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_FTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			implement SSL Support
 */
class Connection
{
	/**	@var		integer			$mode			FTP Transfer Mode */
	public int $mode				= FTP_BINARY;

	/**	@var		boolean			$auth			Indicator of Authentication */
	protected bool $auth			= FALSE;

	/**	@var		resource|NULL	$resource		Resource ID of Connection (Stream in PHP5) */
	protected $resource				= NULL;

	/**	@var		string			$host			Host Name */
	protected string $host			= '';

	/**	@var		integer			$port			Protocol Port */
	protected int $port				= 21;


	/**
	 *	Constructor, connects to FTP Server.
	 *	@access		public
	 *	@param		string		$host			Host Name
	 *	@param		integer		$port			Service Port
	 *	@param		integer		$timeout		Timeout in Seconds
	 *	@return		void
	 */
	public function __construct( string $host, int $port = 21, int $timeout = 90 )
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
	 *	Indicated State of Connection and Authentication.
	 *	@access		public
	 *	@param		boolean		$checkResource			Flag: Check Connection
	 *	@param		boolean		$checkAuthentication	Flag: Check Authentication
	 *	@param		boolean		$strict             	Flag: throw exceptions or return FALSE, default: yes (=throw!)
	 *	@return		bool
	 *	@throws		RuntimeException					if connection is not established (strict mode, only)
	 *	@throws		RuntimeException					if connection is not authenticated (strict mode, only)
	 */
	public function checkConnection( bool $checkResource = TRUE, bool $checkAuthentication = TRUE, bool $strict = TRUE ): bool
	{
		if( $checkResource && !$this->resource ){
			if( $strict )
				throw new RuntimeException( "No Connection to FTP Server established" );
			return FALSE;
		}
		if( $checkAuthentication && !$this->auth ){
			if( $strict )
				throw new RuntimeException( "Not authenticated onto FTP Server" );
			return FALSE;
		}
		return TRUE;
	}

	/**
	 *	Closes FTP Connection.
	 *	@access		public
	 *	@return		bool		$quit		Flag: ...
	 *	@throws		RuntimeException
	 */
	public function close( bool $quit = FALSE ): bool
	{
		if( !$quit )
			$this->checkConnection( TRUE, FALSE, FALSE );
		if( $this->resource !== NULL ){
			if( !ftp_quit( $this->resource ) )
				return FALSE;
			$this->auth		= FALSE;
			$this->resource	= NULL;
		}
		return TRUE;
	}

	/**
	 *	Opens Connection to FTP Server.
	 *	@access		public
	 *	@param		string		$host			Host Name
	 *	@param		integer		$port			Service Port
	 *	@param		integer		$timeout		Timeout in Seconds
	 *	@return		boolean
	 */
	public function connect( string $host, int $port = 21, int $timeout = 10 ): bool
	{
		$resource	= @ftp_connect( $host, $port, $timeout );
		if( !$resource )
			return FALSE;
		$this->host		= $host;
		$this->port		= $port;
		$this->resource	= $resource;
		return TRUE;
	}

	/**
	 *	Returns FTP Server Host.
	 *	@access		public
	 *	@return		string
	 */
	public function getHost(): string
	{
		return $this->host;
	}

	/**
	 *	Returns FTP Server Port.
	 *	@access		public
	 *	@return		integer
	 */
	public function getPort(): int
	{
		return $this->port;
	}

	/**
	 *	Returns current Path.
	 *	@access		public
	 *	@return		string
	 */
	public function getPath(): string
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
	 *	@return		integer
	 */
	public function getTimeout(): int
	{
		return ftp_get_option( $this->resource, FTP_TIMEOUT_SEC );
	}

	/**
	 *	Authenticates FTP Connection.
	 *	@access		public
	 *	@param		string		$username		Username
	 *	@param		string		$password		Password
	 *	@return		boolean
	 */
	public function login( string $username, string $password ): bool
	{
		$this->checkConnection( TRUE, FALSE );
		if( !@ftp_login( $this->resource, $username, $password ) )
			return FALSE;
		$this->auth	= TRUE;
		return TRUE;
	}

	/**
	 *	Sets passive mode.
	 *	@param		boolean		$boolean		Enable or disable passive mode
	 *	@return		boolean
	 *	@see		http://www.php.net/manual/en/function.ftp-pasv.php
	 */
	public function setPassiveMode( bool $boolean ): bool
	{
		return @ftp_pasv( $this->resource, $boolean );
	}

	/**
	 *	Set current Path.
	 *	@access		public
	 *	@param		string		$path			Path to change to
	 *	@return		bool
	 */
	public function setPath( string $path ): bool
	{
		$this->checkConnection();
		return @ftp_chdir( $this->resource, $path );
	}

	/**
	 *	Sets Timeout for all following Operations.
	 *	@access		public
	 *	@param		integer		$seconds		Timeout in seconds
	 *	@return		boolean
	 */
	public function setTimeout( int $seconds ): bool
	{
		return @ftp_set_option( $this->resource, FTP_TIMEOUT_SEC, $seconds );
	}

	/**
	 *	Set Transfer Mode between binary and ascii.
	 *	@access		public
	 *	@param		integer		$mode			Transfer Mode (FTP_BINARY|FTP_ASCII)
	 *	@return		boolean
	 */
	public function setTransferMode( int $mode ): bool
	{
		if( $mode != FTP_BINARY && $mode != FTP_ASCII )
			return FALSE;
		$this->mode	= $mode;
		return TRUE;
	}
}
