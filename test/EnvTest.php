<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Env.
 *	@package		Tests
 */

namespace CeusMedia\CommonTest;

use CeusMedia\Common\Env;

/**
 *	TestUnit of Env.
 *	@package		Tests
 */
class EnvTest extends BaseCase
{
	private const string KEY	= 'CEUSMEDIA_COMMON_TEST_VARIABLE';

	private array $server;

	protected function setUp(): void
	{
		$this->server	= $_SERVER;
		putenv( self::KEY );
	}

	protected function tearDown(): void
	{
		$_SERVER	= $this->server;
		putenv( self::KEY );
	}

	public function testGetServerVariableFromEnvironment(): void
	{
		putenv( self::KEY.'=fromEnv' );
		self::assertSame( 'fromEnv', Env::getServerVariable( self::KEY ) );
	}

	public function testGetServerVariableFromServerOnly(): void
	{
		//  like the built-in web server (php -S): request variables in $_SERVER only
		$_SERVER[self::KEY]	= 'fromServer';
		self::assertSame( 'fromServer', Env::getServerVariable( self::KEY ) );
	}

	public function testGetServerVariablePrefersEnvironment(): void
	{
		putenv( self::KEY.'=fromEnv' );
		$_SERVER[self::KEY]	= 'fromServer';
		self::assertSame( 'fromEnv', Env::getServerVariable( self::KEY ) );
	}

	public function testGetServerVariableDefault(): void
	{
		self::assertNull( Env::getServerVariable( self::KEY ) );
		self::assertSame( 'default', Env::getServerVariable( self::KEY, 'default' ) );
		$_SERVER[self::KEY]	= ['not', 'scalar'];
		self::assertSame( 'default', Env::getServerVariable( self::KEY, 'default' ) );
	}

	public function testGetServerVariableKeepsEmptyValue(): void
	{
		putenv( self::KEY.'=' );
		$_SERVER[self::KEY]	= 'fromServer';
		self::assertSame( '', Env::getServerVariable( self::KEY, 'default' ) );
	}
}
