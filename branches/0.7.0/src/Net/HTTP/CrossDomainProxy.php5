<?php
/**
 *	Proxy for Cross Domain Requests to bypass JavaScript's same origin policy.
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
 *	@package		net.http
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.06.2008
 *	@version		$Id$
 */
/**
 *	Proxy for Cross Domain Requests to bypass JavaScript's same origin policy.
 *	@category		cmClasses
 *	@package		net.http
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.06.2008
 *	@version		$Id$
 *	@todo			use Net_Reader or Net_cURL
 *	@todo			implement time out and http status code check
 */
class Net_HTTP_CrossDomainProxy
{
	/**	@var		string		$url				URL of Service Request */
	protected		$url		= "";
	/**	@var		string		$username			Username of HTTP Basic Authentication */
	protected		$username	= "";
	/**	@var		string		$password			Password of HTTP Basic Authentication */
	protected		$password	= "";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url				URL of Service Request
	 *	@param		string		$username			Username of HTTP Basic Authentication
	 *	@param		string		$password			Password of HTTP Basic Authentication
	 *	@return		void
	 */
	public function __construct( $url, $username = NULL , $password = NULL )
	{
		$this->url		= $url;
		$this->username	= $username;
		$this->password	= $password;
	}

	/**
	 *	Forwards GET or POST Request and returns Response Data.
	 *	@access		public
	 *	@param		bool		$throwException		Check Service Response for Exception and throw a found Exception further
	 *	@return		string
	 */
	public function forward( $throwException = FALSE )
	{
		$query	= getEnv( 'QUERY_STRING' );														//  get GET Query String
		$url	= $this->url."?".$query;														//  build Service Request URL
		return self::requestUrl( $url, $this->username, $this->password, $throwException );
	}
	
	public static function requestUrl( $url, $username = NULL, $password = NULL, $throwException = FALSE )
	{
		$curl	= curl_init();																	//  open cURL Handler
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );										//  skip Peer Verification
		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 0 );										//  skip Host Verification
		curl_setopt( $curl, CURLOPT_URL, $url );												//  set Service Request URL
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE );										//  catch Response
		curl_setopt( $curl, CURLOPT_HEADER, FALSE );
		if( $username )																			//  Basic Authentication Username is set
			curl_setopt( $curl, CURLOPT_USERPWD, $username.":".$password );						//  set HTTP Basic Authentication
		$method	= getEnv( 'REQUEST_METHOD' );													//  get Request Method
		if( $method == "POST" )																	//  Request Method is POST
		{
			$data	= http_build_query( $_POST, NULL, "&" );									//  build POST Parameters
			curl_setopt( $curl, CURLOPT_POST, TRUE );											//  set POST Request on cURL Handler
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );									//  set POST Parameters
		}
		else if( $method != "GET" )																//  neither POST nor GET
			throw new Exception( 'Invalid Request Method.' );									//  throw Exception

		$response	= curl_exec( $curl );														//  get Service Response
		curl_close( $curl );																	//  close cURL Handler

		if( $throwException )																	//  check Response for Exception
			if( $object = @unserialize( $response ) )											//  Response is an Object
				if( is_object( $object ) && is_a( $object, "Exception" ) )						//  Response is an Exception
					throw $object;																//  throw this Exception

		return $response;																		//  return Service Response
	}
	
}
?>