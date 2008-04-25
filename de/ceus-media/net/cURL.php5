<?php
/**
 *	cURL Wrapper
 *	@package		net
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2005
 *	@version		0.6
 */
/**
 *	cURL Wrapper
 *	@package		net
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2005
 *	@version		0.6
 */
define( 'CURL_STATUS_CONTENT_TYPE',				'content_type' );
define( 'CURL_STATUS_CONTENT_LENGTH_DOWNLOAD',	'download_content_length' );
define( 'CURL_STATUS_CONTENT_LENGTH_UPLOAD',	'upload_content_length' );
define( 'CURL_STATUS_ERRNO',					'errno' );
define( 'CURL_STATUS_ERROR',					'error' );
define( 'CURL_STATUS_HTTP_CODE',				'http_code' );
define( 'CURL_STATUS_HTTP_URL',					'url' );
define( 'CURL_STATUS_REDIRECT_COUNT',			'redirect_count' );
define( 'CURL_STATUS_REDIRECT_TIME',			'redirect_time' );
define( 'CURL_STATUS_SIZE_HEADER',				'header_size' );
define( 'CURL_STATUS_SIZE_DOWNLOAD',			'size_download' );
define( 'CURL_STATUS_SIZE_REQUEST',				'request_size' );
define( 'CURL_STATUS_SIZE_UPLOAD',				'size_upload' );
define( 'CURL_STATUS_SPEED_DOWNLOAD',			'speed_download' );
define( 'CURL_STATUS_SPEED_UPLOAD',				'speed_upload' );
define( 'CURL_STATUS_SSL_VERIFY_RESULT',		'ssl_verify_result' );
define( 'CURL_STATUS_TIME_CONNECT',				'connect_time' );
define( 'CURL_STATUS_TIME_NAMELOOKUP',			'namelookup_time' );
define( 'CURL_STATUS_TIME_PRETRANSFER',			'pretransfer_time' );
define( 'CURL_STATUS_TIME_STARTTRANSFER',		'starttransfer_time' );
define( 'CURL_STATUS_TIME_TOTAL',				'total_time' );
class Net_cURL
{
	/**
	 *	Array of caseless header names.
	 *	@access private
	 *	@var array
	 */
	private $caseless;

	/**
	 *	Current cURL session.
	 *	@access private
	 *	@var resource
	 */
	private $handle;

	/**
	 *	Array of parsed HTTP header.
	 *	@access private
	 *	@var mixed
	 */
	private $header;

	/**
	 *	Current setting of the cURL options.
	 *	@access private
	 *	@var mixed
	 */
	private $options;

	/**
	 *	Latest Request Status information.
	 *	@link http://www.php.net/curl_getinfo
	 *	@link http://www.php.net/curl_errno
	 *	@link http://www.php.net/curl_error
	 *	@access private
	 *	@var mixed
	 */
	private $status;

	/**
	 *	cURL class constructor
	 *	@access		public
	 *	@param		string	$url 			URL to be accessed.
	 *	@return		void
	 *	@link		http://www.php.net/curl_init
	 */
	public function __construct( $url = NULL )
	{
		if( !function_exists( 'curl_init' ) )
			throw new Exception( "No cURL support in PHP available." );
		$this->handle	= curl_init();
		$this->caseless	= null;
		$this->header	= null;
		$this->status	= null;
		$this->options	= array();
		if( !empty( $url ) )
			$this->setOption( CURLOPT_URL, $url ); 
		$this->setOption( CURLOPT_HEADER, false );
		$this->setOption( CURLOPT_RETURNTRANSFER, true );
	}

	/**
	 *	Close cURL session and free resources.
	 *	@access		public
	 *	@return		void
	 *	@link		http://www.php.net/curl_close
	 */
	public function close()
	{
		curl_close( $this->handle );
		$this->handle = null;
	}

	/**
	 *	Execute the cURL request and return the result.
	 *	@access		public
	 *	@return		string
	 *	@link		http://www.php.net/curl_exec
	 *	@link		http://www.php.net/curl_getinfo
	 *	@link		http://www.php.net/curl_errno
	 *	@link		http://www.php.net/curl_error
	 */
	public function exec()
	{
		$result = curl_exec( $this->handle );
		$this->status = curl_getinfo( $this->handle );
		$this->status['errno']	= curl_errno( $this->handle );
		$this->status['error']	= curl_error( $this->handle );
		$this->header = NULL;
		if( $this->getOption( CURLOPT_HEADER ) )
		{
			$array = preg_split( "/(\r\n){2,2}/", $result, 2 );
			if( $array[0] )
			{
				$this->parseHeader( $array[0] );
				return $array[1];
			}
		}
		return $result;
	}

	/**
	 *	Returns the parsed HTTP header.
	 *	@access		public
	 *	@param		string	$key		Key name of Header Information
	 *	@returns 	mixed
	 */
	public function getHeader( $key = NULL )
	{
		if( empty( $key ) )
			return $this->header;
		else
		{
			$key = strtoupper( $key );
			if( isset( $this->caseless[$key] ) )
				return $this->header[$this->caseless[$key]];
			else
				return false;
		}
	}

	/**
	 *	Returns the current setting of the request option.
	 *	@access		public
	 *	@param		int		$option		Key name of cURL Option
	 *	@returns 	mixed
	 */
	public function getOption( $option )
	{
		if( !isset( $this->options[$option] ) )
			return NULL;
		return $this->options[$option];
	}

	/**
	 *	Return the status information of the last cURL request.
	 *	@access		public
	 *	@param		string	$key		Key name of Information
	 *	@returns	mixed
	 */
	public function getStatus( $key = NULL )
	{
		if( !$this->status )
			throw new RuntimeException( "No Request has been sent, yet." );
		if( empty( $key ) )
			return $this->status;
		else if( isset( $this->status[$key] ) )
			return $this->status[$key];
		else
			return false;
	}

	/**
	 *	Did the last cURL exec operation have an error?
	 *	@access		public
	 *	@return		mixed 
	 */
	public function hasError()
	{
		if( isset( $this->status['error'] ) )
			return ( empty( $this->status['error'] ) ? false : $this->status['error'] );
		else
			return false;
	}

	/**
	 *	Parse an HTTP header.
	 *
	 *	As a side effect it stores the parsed header in the
	 *	header instance variable.  The header is stored as
	 *	an associative array and the case of the headers 
	 *	as provided by the server is preserved and all
	 *	repeated headers (pragma, set-cookie, etc) are grouped
	 *	with the first spelling for that header
	 *	that is seen.
	 *
	 *	All headers are stored as if they COULD be repeated, so
	 *	the headers are really stored as an array of arrays.
	 *
	 *	@access		public
	 *	@param		string	$header		The HTTP data header
	 *	@return		void
	 */
	public function parseHeader( $header )
	{
		$this->caseless = array();
		$array	= preg_split( "/(\r\n)+/", $header );
		if( preg_match( '/^HTTP/', $array[0] ) )
			$array = array_slice($array, 1);
		foreach( $array as $headerString )
		{
			$headerStringArray = preg_split( "/\s*:\s*/", $headerString, 2 );
			$caselessTag = strtoupper( $headerStringArray[0] );
			if( !isset( $this->caseless[$caselessTag] ) )
				$this->caseless[$caselessTag] = $headerStringArray[0];
			$this->header[$this->caseless[$caselessTag]][] = $headerStringArray[1];
		}
	}

	/**
	 *	Set a cURL option.
	 *
	 *	@access		public
	 *	@param		mixed	$option		One of the valid CURLOPT defines.
	 *	@param		mixed	$value		the value of the cURL option.
	 *	@return		void
	 *	@link		http://www.php.net/curl_setopt
	 */
	public function setOption( $option, $value )
	{
		if( !curl_setopt( $this->handle, $option, $value ) )
			throw new InvalidArgumentException( "Option could not been set." );
		$this->options[$option]	= $value;
	}
}
?>
