<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Sender for HTTP POST requests.
 *
 *	Copyright (c) 2015-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP;

use CeusMedia\Common\Net\CURL;
use OutOfBoundsException;
use RuntimeException;

/**
 *	Sender for HTTP POST requests.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Post
{
	const TRANSPORT_NONE		= 0;
	const TRANSPORT_FOPEN		= 1;
	const TRANSPORT_CURL		= 2;

	protected $transport		= self::TRANSPORT_NONE;

	protected $dataMaxLength	= 0;

	//  default user agent to report to server, can be overridden by constructor or given CURL options on get or post
	static protected $userAgent	= "CeusMediaCommon:Net.HTTP.Post/0.9";

	public function __construct()
	{
		$allowUrlFopen	= preg_match( '/1|yes|on|true/i', ini_get( 'allow_url_fopen' ) );
		if( CURL::isSupported() )
			$this->transport	= self::TRANSPORT_CURL;
		else if( $allowUrlFopen )
			$this->transport	= self::TRANSPORT_FOPEN;
	}

	public function send( string $url, array $data = [], array $curlOptions = [] ): string
	{
		if( is_array( $data ) )
			$data	= http_build_query( $data, NULL, '&' );
		if( $this->dataMaxLength && strlen( $data ) > $this->dataMaxLength )
			throw new OutOfBoundsException( 'POST content larger than '.$this->dataMaxLength.' bytes' );
		$contentType	= 'Content-type: application/x-www-form-urlencoded';

		switch( $this->transport ){
			case self::TRANSPORT_CURL:
				$curl		= new CURL( $url );
				$options	= array(
					CURLOPT_POST				=> TRUE,
					CURLOPT_RETURNTRANSFER		=> TRUE,
					CURLOPT_HTTPHEADER			=> array( $contentType ),
					CURLOPT_POSTFIELDS			=> $data,
					CURLOPT_FOLLOWLOCATION		=> FALSE,
					CURLOPT_USERAGENT			=> self::$userAgent,
					CURLOPT_CONNECTTIMEOUT		=> 15,
				);
				foreach( $curlOptions as $key => $value )
					$options[$key]	= $value;
				foreach( $options as $key => $value )
					$curl->setOption( $key, $value );
				return trim( $curl->exec( TRUE ) );

			case self::TRANSPORT_FOPEN:
				$stream	= array(
					'method'		=> 'POST',
					'header'		=> $contentType,
					'content'		=> $data,
					'max_redirects'	=> 0,
					'timeout'		=> 15,
				);
				$stream	= stream_context_create( array( 'http'	=> $stream ) );
				return trim( file_get_contents( $url, FALSE, $stream ) );

			default:
				throw new RuntimeException( 'Could not make HTTP request: allow_url_open is false and cURL not available' );
		}
	}

	public static function sendData( string $url, array $data = [], array $curlOptions = [] ): string
	{
		$post	= new self();
		return $post->send( $url, $data, $curlOptions );
	}

	public function setDataMaxLength( int $integer ): self
	{
		if( $integer === 0 || $integer > 1 )
			$this->dataMaxLength	= $integer;
		return $this;
	}
}
