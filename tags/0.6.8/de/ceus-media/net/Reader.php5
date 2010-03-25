<?php
/**
 *	Reader for Contents from the Net.
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
 *	@package		net
 *	@uses			Net_cURL
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2008
 *	@version		$Id$
 */
import( 'de.ceus-media.net.cURL' );
/**
 *	Reader for Contents from the Net.
 *
 *	@category		cmClasses
 *	@package		net
 *	@uses			Net_cURL
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2008
 *	@version		$Id$
 */
class Net_Reader
{
	/**	@var		array		$status				Status Array of last Request */
	protected $status			= array();
	/**	@var		string		$url				URL to read */
	protected $url;
	/**	@var		string		$agent				User Agent */
	protected static $userAgent	= "cmClasses:Net_Reader/0.6";
	/**	@var		string		$username			Username for Basic Authentication */
	private $username			= "";
	/**	@var		string		$password			Password for Basic Authentication */
	private $password			= "";
	/**	@var		bool		$verifyHost			Flag: verify Host */
	private $verifyHost 		= FALSE;
	/**	@var		bool		$verifyPeer			Flag: verify Peer */
	private $verifyPeer			= FALSE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url				URL to read
	 *	@return		void
	 */
	public function __construct( $url = NULL )
	{
		if( $url )
			$this->setUrl( $url );
	}

	/**
	 *	Returns Headers Array or a specified Header from last Request.
	 *	@access		public
	 *	@param		string		$key				Header Key
	 *	@return		mixed
	 */
	public function getHeader( $key = NULL )
	{
		if( !$this->status )
			throw new RuntimeException( "No Request has been sent, yet." );
		if( !$key )
			return $this->headers;
		if( !array_key_exists( $key, $this->headers ) )
			throw new InvalidArgumentException( 'Header Key "'.$key.'" is invalid.' );
		return $this->headers[$key];
	}

	/**
	 *	Returns Status Array or single Status Information from last Request.
	 *	@access		public
	 *	@param		string		$key				Status Information Key
	 *	@return		mixed
	 */
	public function getStatus( $key = NULL )
	{
		if( !$this->status )
			throw new RuntimeException( "No Request has been sent, yet." );
		if( !$key )
			return $this->status;
		if( !array_key_exists( $key, $this->status ) )
			throw new InvalidArgumentException( 'Status Key "'.$key.'" is invalid.' );
		return $this->status[$key];
	}

	/**
	 *	Returns URL to read.
	 *	@access		public
	 *	@return		string
 		 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 *	Returns set User Agent.
	 *	@access		public
	 *	@return		string
	 */
	public function getUserAgent()
	{
		return self::$userAgent;
	}

	/**
	 *	Requests set URL and returns Response.
	 *	@access		public
	 *	@return		string		$curlOptions		Array of cURL Options
	 *	@todo		Auth
	 */
	public function read( $curlOptions = array() )
	{
		$curl		= new Net_cURL( $this->url );

		$curl->setOption( CURLOPT_SSL_VERIFYHOST, $this->verifyHost );
		$curl->setOption( CURLOPT_SSL_VERIFYPEER, $this->verifyPeer );
		if( self::$userAgent )
			$curl->setOption( CURLOPT_USERAGENT, self::$userAgent );
		if( $this->username )
			$curl->setOption( CURLOPT_USERPWD, $this->username.":".$this->password );

		foreach( $curlOptions as $key => $value )
		{
			if( !( is_string( $key ) && preg_match( "@^CURLOPT_@", $key ) && defined( $key ) ) )
				throw new InvalidArgumentException( 'Invalid constant "'.$key.'"' );
			$key	= constant( $key );
			$curl->setOption( $key, $value );
		}
		$response		= $curl->exec();
		$this->status	= $curl->getStatus();
		$this->headers	= $curl->getHeader();
		$code			= $curl->getStatus( Net_cURL::STATUS_HTTP_CODE );
	
		if( !in_array( $code, array( '200', '301', '303', '304', '307' ) ) )
			throw new RuntimeException( 'URL "'.$this->url.'" can not be accessed (HTTP Code '.$code.').', $code );

		return $response;
	}

	/**
	 *	Requests URL and returns Response statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$url			URL to request
	 *	@param		array		$curlOptions	Array of cURL Options
	 *	@return		string
	 *	@todo		Auth
	 */
	public static function readUrl( $url, $curlOptions = array() )
	{
		$reader	= new Net_Reader( $url );
		return $reader->read( $curlOptions );
	}

	/**
	 *	Set Username and Password for Basic Auth.
	 *	@access		public
	 *	@param		string		$username		Basic Auth Username
	 *	@param		string		$password		Basic Auth Password
	 *	@return		void
	 */
	public function setBasicAuth( $username, $password )
	{
		$this->username	= $username;
		$this->password	= $password;
	}

	/**
	 *	Sets User Agent.
	 *	@access		public
	 *	@param		string		$title			User Agent to set
	 *	@return		void
	 */
	public function setUserAgent( $title )
	{
		self::$userAgent	= $title;
	}

	/**
	 *	Set URL to request.
	 *	@access		public
	 *	@param		string		$url			URL to request
	 *	@return		void
	 */
	public function setUrl( $url )
	{
		if( !( is_string( $url ) && $url ) )
			throw new InvalidArgumentException( "No URL given." );
		$this->url	= $url;
	}

	/**
	 *	Sets Option CURLOPT_SSL_VERIFYHOST.
	 *	@access		public
	 *	@param		bool		$verify			Flag: verify Host
	 *	@return		void
	 */
	public function setVerifyHost( $verify )
	{
		$this->verifyHost	= (bool) $verify;
	}

	/**
	 *	Sets Option CURLOPT_SSL_VERIFYPEER.
	 *	@access		public
	 *	@param		bool		$verify			Flag: verify Peer
	 *	@return		void
	 */
	public function setVerifyPeer( $verify )
	{
		$this->verifyPeer	= (bool) $verify;
	}
}
?>