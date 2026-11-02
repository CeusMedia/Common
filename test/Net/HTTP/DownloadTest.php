<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Net\HTTP;

use CeusMedia\Common\Net\HTTP\Download;
use CeusMedia\CommonTest\BaseCase;
use ReflectionMethod;
use RuntimeException;

/**
 *	TestUnit of Net\HTTP\Download.
 *	@package		Tests.Net.HTTP
 */
class DownloadTest extends BaseCase
{
	public function testRenderContentDisposition(): void
	{
		$method	= new ReflectionMethod( Download::class, 'renderContentDisposition' );
		self::assertSame( 'Content-Disposition: attachment; filename="report.pdf"', $method->invoke( NULL, 'report.pdf' ) );
		self::assertSame(
			'Content-Disposition: attachment; filename="a\\"b.txt"; filename*=UTF-8\'\'a%22b.txt',
			$method->invoke( NULL, 'a"b.txt' )
		);
		self::assertSame(
			'Content-Disposition: attachment; filename="Gr__e.txt"; filename*=UTF-8\'\'Gr%C3%B6%C3%9Fe.txt',
			$method->invoke( NULL, 'Größe.txt' )
		);
		self::assertStringNotContainsString( "\n", $method->invoke( NULL, "evil\r\nX-Header: 1" ) );
	}

	public function testSendFileStripsParentFolderReferences(): void
	{
		try{
			Download::sendFile( '....//....//not_existing.txt', NULL, FALSE );
			self::fail( 'Exception expected' );
		}
		catch( RuntimeException $e ){
			self::assertSame( 'File "not_existing.txt" is not existing', $e->getMessage() );
		}
	}
}
