<?php
import( 'de.ceus-media.net.http.Header' );
class Net_HTTP_Headers
{
	protected $headers	= array(
		'general'	=> array(
			'cache-control'			=> array(),
			'connection'			=> array(),
			'date'					=> array(),
			'pragma'				=> array(),
			'trailer'				=> array(),
			'transfer-encoding'		=> array(),
			'upgrade'				=> array(),
			'via'					=> array(),
			'warning'				=> array()
		),
		'request'	=> array(
			'accept'				=> array(),
			'accept-charset'		=> array(),
			'accept-encoding'		=> array(),
			'accept-language'		=> array(),
			'authorization'			=> array(),
			'expect'				=> array(),
			'from'					=> array(),
			'host'					=> array(),
			'if-match'				=> array(),
			'if-modified-since'		=> array(),
			'if-none-match'			=> array(),
			'if-range'				=> array(),
			'if-unmodified-since'	=> array(),
			'max-forwards'			=> array(),
			'proxy-authorization'	=> array(),
			'range'					=> array(),
			'referer'				=> array(),
			'te'					=> array(),
			'user-agent'			=> array()
		),
		'response'	=> array(
			'accept-ranges'			=> array(),
			'age'					=> array(),
			'etag'					=> array(),
			'location'				=> array(),
			'proxy-authenticate'	=> array(),
			'retry-after'			=> array(),
			'server'				=> array(),
			'vary'					=> array(),
			'www-authenticate'		=> array()
		),
		'entity'	=> array(
			'allow'		=> array(),
			'content-encoding'		=> array(),
			'content-language'		=> array(),
			'content-length'		=> array(),
			'content-location'		=> array(),
			'content-md5'			=> array(),
			'content-range'			=> array(),
			'content-type'			=> array(),
			'expires'				=> array(),
			'last-modified'			=> array()
		),
		'others'	=> array(
		)
	);
	
	public function addHeader( Net_HTTP_Header $header )
	{
		$name	= $header->getName();
		foreach( $this->headers as $sectionName => $sectionPairs )
		{
			if( array_key_exists( $name, $sectionPairs ) )
			{
				$this->headers[$sectionName][$name][]	= $header;
				return;
			}
		}
		$this->headers['others'][$name]	= array( $header );
	}
	
	public function addHeaderPair( $name, $value )
	{
		$header	= new Net_HTTP_Header( $name, $value );
		$this->addHeader( $header );
	}

	public function addHeaders( $headers )
	{
		foreach( $headers as $header )
			$this->addHeader( $header );
	}

	public function getHeaders()
	{
		$list	= array();
		foreach( $this->headers as $sectionName => $sectionPairs )
			foreach( $sectionPairs as $name => $headerList )
				if( count( $headerList ) )
					foreach( $headerList as $header )
						$list[]	= $header;
		return $list;
	}
	
	public function getHeadersByName( $name )
	{
		$name	= strtolower( $name );
		foreach( $this->headers as $sectionName => $sectionPairs )
			if( array_key_exists( $name, $sectionPairs ) )
				if( $this->headers[$sectionName][$name] )
					return $this->headers[$sectionName][$name];
		return array();
	}

	public function hasHeader( $name )
	{
		$name	= strtolower( $name );
		foreach( $this->headers as $sectionName => $sectionPairs )
			if( array_key_exists( $name, $sectionPairs ) )
				return (bool) count( $this->headers[$sectionName][$name] );
		return FALSE;
	}
	
	public function toString()
	{
		$list	= array();
		foreach( $this->headers as $sectionName => $sectionPairs )
			foreach( $sectionPairs as $name => $headers )
				if( $headers )
					foreach( $headers as $header )
						$list[]	= $header->toString();
		$list	= implode( "\r\n", $list );
		return $list;
	}
}
/*

GENERAL
-------
Cache-Control
Connection
Date
Pragma
Trailer
Transfer-Encoding
Upgrade
Via
Warning


REQUEST
-------
Accept
Accept-Charset
Accept-Encoding
Accept-Language
Authorization
Expect
From
Host
If-Match

If-Modified-Since
If-None-Match
If-Range
If-Unmodified-Since
Max-Forwards
Proxy-Authorization
Range
Referer
TE
User-Agent


RESPONSE
--------
Accept-Ranges
Age
ETag
Location
Proxy-Authenticate
Retry-After
Server
Vary
WWW-Authenticate


ENTITY
------
Allow
Content-Encoding
Content-Language
Content-Length
Content-Location
Content-MD5
Content-Range
Content-Type
Expires
Last-Modified
*/
?>