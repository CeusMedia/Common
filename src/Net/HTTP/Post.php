<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Sender for HTTP POST requests.
 *
 *	Copyright (c) 2015-2024 Christian Würker (ceusmedia.de)
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
 *	@copyright		2015-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP;

use CeusMedia\Common\ADT\URL;
use CeusMedia\Common\Net\CURL;
use OutOfBoundsException;
use RuntimeException;

/**
 *	Sender for HTTP POST requests.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Post
{
	public const TRANSPORT_NONE			= 0;
	public const TRANSPORT_FOPEN		= 1;
	public const TRANSPORT_CURL			= 2;

	protected ?string $content			= NULL;

	protected string $contentType		= 'application/x-www-form-urlencoded';

	protected int $dataMaxLength		= 0;

	protected int $transport			= self::TRANSPORT_NONE;

	protected URL $url;

	protected string $userAgent			= "CeusMediaCommon:Net.HTTP.Post/0.9";

	public static function convertArrayToFormData( array $data ): string
	{
		return http_build_query( $data, '', '&' );
	}

	public static function sendData( string $url, array|string $data, array $curlOptions = [] ): string
	{
		$post	= new self( $url );
		$post->setContent( is_array( $data ) ? self::convertArrayToFormData( $data ) : $data );
		return $post->send( $curlOptions );
	}

	public function __construct( ?string $url = NULL )
	{
		if ($url !== NULL)
			$this->setUrl($url);
		$this->detectTransportStrategy();
	}

	public function send( array $curlOptions = [] ): string
	{
		$this->checkContentLength( $this->content );

		switch( $this->transport ){
			case self::TRANSPORT_CURL:
				return $this->performRequestUsingCurl( $curlOptions );
			case self::TRANSPORT_FOPEN:
				return $this->performRequestUsingStream();
			default:
				throw new RuntimeException( 'Could not make HTTP request: allow_url_open is false and cURL not available' );
		}
	}

	protected function performRequestUsingCurl( array $curlOptions = [] ): string
	{
		$contentType	= 'Content-type: '.$this->contentType;
		$curl		= new CURL( $this->url->get() );
		$options	= [
			CURLOPT_POST				=> TRUE,
			CURLOPT_RETURNTRANSFER		=> TRUE,
			CURLOPT_HTTPHEADER			=> [$contentType],
			CURLOPT_POSTFIELDS			=> $this->content,
			CURLOPT_FOLLOWLOCATION		=> FALSE,
			CURLOPT_USERAGENT			=> $this->userAgent,
			CURLOPT_CONNECTTIMEOUT		=> 15,
		];
		foreach( $curlOptions as $key => $value )
			$options[$key]	= $value;
		foreach( $options as $key => $value )
			$curl->setOption( $key, $value );
		return trim( $curl->exec( TRUE ) );
	}

	protected function performRequestUsingStream(): string
	{
		$contentType	= 'Content-type: '.$this->contentType;
		$httpContext	= [
			'method'		=> 'POST',
			'header'		=> $contentType,
			'content'		=> $this->content,
			'max_redirects'	=> 0,
			'timeout'		=> 15,
		];
		$context	= stream_context_create( ['http' => $httpContext] );
		return trim( file_get_contents( $this->url->get(), FALSE, $context ) );

	}

	public function setContent( string $content ): self
	{
		$this->checkContentLength( $content );
		$this->content		= $content;
		return $this;
	}

	public function setContentType( string $contentType ): self
	{
		$this->contentType	= $contentType;
		return $this;
	}

	public function setDataMaxLength( int $integer ): self
	{
		if( $integer === 0 || $integer > 1 )
			$this->dataMaxLength	= $integer;
		return $this;
	}

	/**
	 *	@param		URL|string		$url
	 *	@return		self
	 */
	public function setUrl( $url ): self
	{
		$this->url	= $url instanceof URL ? $url : new URL( $url );
		return $this;
	}

	public function setUserAgent( string $userAgent ): self
	{
		$this->userAgent	= $userAgent;
		return $this;
	}

	protected function checkContentLength( string $content ): void
	{
		if( $this->dataMaxLength > 0 && strlen( $content ) > $this->dataMaxLength )
			throw new OutOfBoundsException( 'POST content larger than '.$this->dataMaxLength.' bytes' );
	}

	protected function detectTransportStrategy(): self
	{
		if( CURL::isSupported() )
			$this->transport	= self::TRANSPORT_CURL;
		else if( preg_match( '/1|yes|on|true/i', ini_get( 'allow_url_fopen' ) ) )
			$this->transport	= self::TRANSPORT_FOPEN;
		return $this;
	}
}
