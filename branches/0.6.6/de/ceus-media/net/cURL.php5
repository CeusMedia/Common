<?php
/**
 *	cURL Wrapper
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
 *	@package		net
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
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
/**
 *	cURL Wrapper
 *	@package		net
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.06.2005
 *	@version		0.6
 */
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
	 *	Time out in Seconds.
	 *	@access private
	 *	@var int
	 */
	private static $timeOut		= 0; 

	/**
	 *	cURL class constructor
	 *	@access		public
	 *	@param		string		$url 		URL to be accessed.
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
		$this->setOption( CURLOPT_FOLLOWLOCATION, TRUE );
		$this->setOption( CURLOPT_HEADER, TRUE );
		$this->setOption( CURLOPT_RETURNTRANSFER, TRUE );
		if( self::$timeOut )
		{
			$this->setOption( CURLOPT_TIMEOUT, self::$timeOut );
			$this->setOption( CURLOPT_CONNECTTIMEOUT, self::$timeOut );
		}
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
	 *	@param		bool		$breakOnError		Flag: throw an Exception if a Error has occured
	 *	@return		string
	 *	@link		http://www.php.net/curl_exec
	 *	@link		http://www.php.net/curl_getinfo
	 *	@link		http://www.php.net/curl_errno
	 *	@link		http://www.php.net/curl_error
	 */
	public function exec( $breakOnError = FALSE )
	{
		$url	= $this->getOption( CURLOPT_URL );
		if( empty( $url ) )
			throw new RuntimeException( 'No URL set.' );
		if( !preg_match( "@[a-z]+://[a-z0-9]+.+@i", $url ) )
			throw new InvalidArgumentException( 'URL "'.$url.'" has no valid Protocol.' );

		$result = curl_exec( $this->handle );
		$this->status = curl_getinfo( $this->handle );
		$this->status['errno']	= curl_errno( $this->handle );
		$this->status['error']	= curl_error( $this->handle );
				
		if( $breakOnError && $this->status['errno'] )
			throw new RuntimeException( $this->status['error'], $this->status['errno'] );

		$this->header = NULL;
		if( $this->getOption( CURLOPT_HEADER ) )
		{
			$result	= preg_replace( "@^HTTP/1\.1 100 Continue\r\n\r\n@", "", $result );				//  Hack: remove "100 Continue"
			$result	= trim( $result );																//  trim Result String
			
			$parts	= preg_split( "/(\r\n){2}/", $result );											//  split Headers Blocks
#			if( count( $parts ) < 2 )																//  no Header Blocks splitted
#				throw new Exception( 'Error while splitting HTTP Response String.' );

			while( $parts && preg_match( "@^HTTP/@", trim( $parts[0] ) ) )							//  another Header Block found
				$header	= array_shift( $parts );													//  Header Blocks is first Part

			$result	= implode( "\r\n\r", $parts );													//  implode other Blocks
			$this->parseHeader( $header );															//  parse Header Block
		}
		return $result;
	}

	/**
	 *	Returns the parsed HTTP header.
	 *	@access		public
	 *	@param		string		$key		Key name of Header Information
	 *	@return	 	mixed
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
	 *	@param		int			$option		Key name of cURL Option
	 *	@return 	mixed
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
	 *	@param		string		$key		Key name of Information
	 *	@return		mixed
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
	 *	@param		string		$header		The HTTP data header
	 *	@return		void
	 */
	public function parseHeader( $header )
	{
		$this->caseless = array();
		$headers	= preg_split( "/(\r\n)+/", $header );
		foreach( $headers as $header )
		{
			if( !( trim( $header ) && !preg_match( '/^HTTP/', $header ) ) )
				continue;
			$pair	= preg_split( "/\s*:\s*/", $header, 2 );
			$caselessTag = strtoupper( $pair[0] );
			if( !isset( $this->caseless[$caselessTag] ) )
				$this->caseless[$caselessTag] = $pair[0];
			$this->header[$this->caseless[$caselessTag]][] = $pair[1];
		}
	}

	/**
	 *	Set a cURL option.
	 *
	 *	@access		public
	 *	@param		mixed		$option		One of the valid CURLOPT defines.
	 *	@param		mixed		$value		the value of the cURL option.
	 *	@return		void
	 *	@link		http://www.php.net/curl_setopt
	 */
	public function setOption( $option, $value )
	{
		if( !curl_setopt( $this->handle, $option, $value ) )
			throw new InvalidArgumentException( "Option could not been set." );
		$this->options[$option]	= $value;
	}
	
	/**
	 *	Set Time Out in Seconds.
	 *	@access		public
	 *	@static
	 *	@param		int			$seconds	Seconds until Time Out
	 *	@return		void
	 */
	public static function setTimeOut( $seconds )
	{
		self::$timeOut	= (int) $seconds;
	}
}
?>