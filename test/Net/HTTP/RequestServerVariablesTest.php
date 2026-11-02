<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Net_HTTP_Request with request variables in $_SERVER only, like the built-in web server (php -S).
 *	@package		Tests.Net.HTTP
 */

namespace CeusMedia\CommonTest\Net\HTTP;

use CeusMedia\Common\Net\HTTP\Request;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net_HTTP_Request with request variables in $_SERVER only, like the built-in web server (php -S).
 *	@package		Tests.Net.HTTP
 */
class RequestServerVariablesTest extends BaseCase
{
	private const array VARIABLES	= [
		'REQUEST_METHOD'	=> 'POST',
		'SCRIPT_NAME'		=> '/app/index.php',
		'REQUEST_URI'		=> '/app/users/1?view=full',
		'REMOTE_ADDR'		=> '198.51.100.1',
		'REQUEST_SCHEME'	=> 'https',
		'HTTP_HOST'			=> 'example.org',
	];

	private array $server;

	/** @var array<string,string|false> */
	private array $environment	= [];

	protected function setUp(): void
	{
		$this->server	= $_SERVER;
		foreach( array_keys( self::VARIABLES ) as $key ){
			$this->environment[$key]	= getenv( $key );
			putenv( $key );
		}
		foreach( self::VARIABLES as $key => $value )
			$_SERVER[$key]	= $value;
	}

	protected function tearDown(): void
	{
		$_SERVER	= $this->server;
		foreach( $this->environment as $key => $value )
			putenv( FALSE !== $value ? $key.'='.$value : $key );
	}

	public function testFromEnvReadsServerVariables(): void
	{
		$request	= ( new Request() )->fromEnv();
		self::assertSame( 'POST', $request->getMethod()->get() );
		self::assertSame( 'users/1', $request->getPath() );
		self::assertSame( '198.51.100.1', $request->getRemoteAddress() );
		self::assertSame( 'https://example.org/app/users/1?view=full', (string) $request->getUrl() );
	}

	public function testEnvironmentWins(): void
	{
		putenv( 'REQUEST_METHOD=PUT' );
		self::assertSame( 'PUT', ( new Request() )->fromEnv()->getMethod()->get() );
	}
}
