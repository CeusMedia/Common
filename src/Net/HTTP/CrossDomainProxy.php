<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Proxy for Cross Domain Requests to bypass JavaScript's same origin policy.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP;

use Exception;

/**
 *	Proxy for Cross Domain Requests to bypass JavaScript's same origin policy.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			use Net_Reader or Net_CURL
 *	@todo			implement time out and http status code check
 *	@todo			think about forwarding header "X-Requested-With"
 */
class CrossDomainProxy
{
	/**	@var		string		$url				URL of Service Request */
	protected string $url;

	/**	@var		string|NULL		$username			Username of HTTP Basic Authentication */
	protected ?string $username	= NULL;

	/**	@var		string|NULL		$password			Password of HTTP Basic Authentication */
	protected ?string $password	= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$url				URL of Service Request
	 *	@param		string|NULL		$username			Username of HTTP Basic Authentication
	 *	@param		string|NULL		$password			Password of HTTP Basic Authentication
	 *	@return		void
	 */
	public function __construct( string $url, ?string $username = NULL , ?string $password = NULL )
	{
		$this->url		= $url;
		$this->username	= $username;
		$this->password	= $password;
	}

	/**
	 *	Forwards GET or POST Request and returns Response Data.
	 *	@access		public
	 *	@param		bool		$throwException		Check Service Response for Exception and throw a found Exception further
	 *	@return		string|bool
	 *	@throws		Exception
	 */
	public function forward( bool $throwException = FALSE ): bool|string
	{
		//  get GET Query String
		$query	= getEnv( 'QUERY_STRING' );
		//  build Service Request URL
		$url	= $this->url."?".$query;
		return self::requestUrl( $url, $this->username, $this->password, $throwException );
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string			$url				URL of Service Request
	 *	@param		string|NULL		$username			Username of HTTP Basic Authentication
	 *	@param		string|NULL		$password			Password of HTTP Basic Authentication
	 *	@param		bool			$throwException		Check Service Response for Exception and throw a found Exception further
	 *	@return		string|bool
	 *	@throws		Exception
	 */
	public static function requestUrl( string $url, ?string $username = NULL, ?string $password = NULL, bool $throwException = FALSE ): bool|string
	{
		//  open cURL Handler
		$curl	= curl_init();
		//  skip Peer Verification
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
		//  skip Host Verification
		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 0 );
		//  set Service Request URL
		curl_setopt( $curl, CURLOPT_URL, $url );
		//  catch Response
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE );
		//  don't receiver headers
		curl_setopt( $curl, CURLOPT_HEADER, FALSE );
		//  follow redirects
		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, TRUE );
		//  Basic Authentication Username is set
		if( $username )
			//  set HTTP Basic Authentication
			curl_setopt( $curl, CURLOPT_USERPWD, $username.":".$password );
		//  get Request Method
		$method	= getEnv( 'REQUEST_METHOD' );
		//  Request Method is POST
		if( $method == "POST" ){
			//  build POST Parameters
			$data	= http_build_query( $_POST, '', "&" );
			//  set POST Request on cURL Handler
			curl_setopt( $curl, CURLOPT_POST, TRUE );
			//  set POST Parameters
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
		}
		//  neither POST nor GET
		else if( $method != "GET" )
			//  throw Exception
			throw new Exception( 'Invalid Request Method.' );

		//  get Service Response
		$response	= curl_exec( $curl );
		//  close cURL Handler
		curl_close( $curl );

		//  check Response for Exception
		if( $throwException )
			//  Response is an Object
			if( $object = @unserialize( $response ) )
				//  Response is an Exception
				if( $object instanceof Exception )
					//  throw this Exception
					throw $object;

		//  return Service Response
		return $response;
	}
}
