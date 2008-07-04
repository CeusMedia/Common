<?php
/**
 *	Basic FTP Connection.
 *	@package		net.ftp
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2006
 *	@version		0.6
 */
/**
 *	Basic FTP Connection.
 *	@package		net.ftp
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2006
 *	@version		0.6
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
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$host			Host Name
	 *	@param		int			$port			Service Port
	 *	@return		void
	 */
	public function __construct( $host, $port = 21 )
	{
		$this->connect( $host, $port );
	}
	
	/**
	 *	Destructor.
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
	 *	@return		bool
	 */
	public function connect( $host, $port = 21 )
	{
		$resource	= @ftp_connect( $host, $port );
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
}	
?>