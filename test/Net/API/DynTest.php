<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Net\API;

use CeusMedia\Common\Net\API\Dyn;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net\API\Dyn (without network access).
 *	@package		Tests.Net.API
 */
class DynTest extends BaseCase
{
	protected string $cacheFile;

	public function setUp(): void
	{
		$this->cacheFile	= sys_get_temp_dir().'/CeusMediaCommonTestDyn'.getmypid().'.json';
	}

	public function tearDown(): void
	{
		@unlink( $this->cacheFile );
	}

	public function testGetIpFromCache(): void
	{
		file_put_contents( $this->cacheFile, json_encode( ['ip' => '192.0.2.1', 'timestamp' => time() - 60] ) );
		self::assertSame( '192.0.2.1', ( new Dyn( $this->cacheFile ) )->getIp() );
	}

	public function testUpdateSkipsAlreadyUpdatedIp(): void
	{
		file_put_contents( $this->cacheFile, json_encode( ['ip' => '192.0.2.1', 'timestamp' => time(), 'updated' => '192.0.2.1'] ) );
		self::assertSame( 'noop', ( new Dyn( $this->cacheFile ) )->update( 'user', 'pass', 'host.example.org', '192.0.2.1' ) );
	}

	public function testConstructWithInvalidCache(): void
	{
		file_put_contents( $this->cacheFile, 'not json' );
		$dyn	= new Dyn( $this->cacheFile );
		self::assertInstanceOf( Dyn::class, $dyn );
	}
}
