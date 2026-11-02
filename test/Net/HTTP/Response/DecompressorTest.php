<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Net\HTTP\Response;

use CeusMedia\Common\Net\HTTP\Response;
use CeusMedia\Common\Net\HTTP\Response\Compressor;
use CeusMedia\Common\Net\HTTP\Response\Decompressor;
use CeusMedia\CommonTest\BaseCase;
use InvalidArgumentException;
use RuntimeException;

/**
 *	TestUnit of Net\HTTP\Response\Compressor and Decompressor.
 *	@package		Tests.Net.HTTP.Response
 */
class DecompressorTest extends BaseCase
{
	public function testRoundTrip(): void
	{
		$content	= str_repeat( 'Größe € ', 50 );
		foreach( ['gzip', 'deflate'] as $type ){
			$compressed	= Compressor::compressString( $content, $type );
			self::assertNotSame( $content, $compressed );
			self::assertSame( $content, Decompressor::decompressString( $compressed, $type ) );
		}
		//  HTTP deflate is zlib format
		self::assertSame( $content, gzuncompress( Compressor::compressString( $content, 'deflate' ) ) );
	}

	public function testInflateRawDeflate(): void
	{
		self::assertSame( 'raw data', Decompressor::decompressString( (string) gzdeflate( 'raw data' ), 'deflate' ) );
	}

	public function testDecompressResponse(): void
	{
		$response	= new Response();
		$response->setBody( (string) gzencode( (string) gzcompress( 'nested' ) ) );
		$response->addHeaderPair( 'Content-Encoding', 'deflate, gzip' );
		Decompressor::decompressResponse( $response );
		self::assertSame( 'nested', $response->getBody() );
		self::assertFalse( $response->hasHeader( 'Content-Encoding' ) );
		self::assertSame( '6', $response->getHeader( 'Content-Length', TRUE )->getValue() );
	}

	public function testCompressResponse(): void
	{
		$response	= new Response();
		$response->setBody( 'content' );
		$compressed	= Compressor::compressResponse( $response, 'gzip' );
		self::assertSame( 'gzip', $compressed->getHeader( 'Content-Encoding', TRUE )->getValue() );
		self::assertSame( (string) strlen( (string) $compressed->getBody() ), $compressed->getHeader( 'Content-Length', TRUE )->getValue() );
		Decompressor::decompressResponse( $compressed );
		self::assertSame( 'content', $compressed->getBody() );
	}

	public function testDecompressStringExceptions(): void
	{
		$this->expectException( RuntimeException::class );
		Decompressor::decompressString( 'not compressed', 'gzip' );
	}

	public function testDecompressStringUnsupported(): void
	{
		$this->expectException( InvalidArgumentException::class );
		Decompressor::decompressString( 'data', 'br' );
	}
}
