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
	private const KEY	= 'CEUSMEDIA_COMMON_TEST_VARIABLE';

	private array $server;

	private string $envFile;

	protected function setUp(): void
	{
		$this->server	= $_SERVER;
		putenv( self::KEY );
	}

	protected function tearDown(): void
	{
		$_SERVER	= $this->server;
		putenv( self::KEY );
		foreach( ['ENVTEST_MODE', 'ENVTEST_NAME', 'ENVTEST_DEBUG', 'ENVTEST_PORT', 'ENVTEST_EMPTY', 'envtest_db_host', 'envtest_db_name', 'envtest_mail_from'] as $key )
			putenv( $key );
		if( isset( $this->envFile ) && file_exists( $this->envFile ) )
			unlink( $this->envFile );
	}

	/**
	 *	Tests that values are stored without quotes (they were stored as '"value"' before).
	 */
	public function testSet(): void
	{
		self::assertTrue( Env::set( self::KEY, 'value' ) );
		self::assertSame( 'value', Env::get( self::KEY ) );

		Env::set( self::KEY, 'with "quotes" and spaces' );
		self::assertSame( 'with "quotes" and spaces', Env::get( self::KEY ) );

		Env::set( self::KEY, '' );
		self::assertSame( '', Env::get( self::KEY, 'default' ) );

		Env::set( self::KEY, 42 );
		self::assertSame( '42', Env::get( self::KEY ) );

		Env::set( self::KEY, 1.5 );
		self::assertSame( '1.5', Env::get( self::KEY ) );
	}

	/**
	 *	Tests loading a flat file and a file with sections.
	 */
	public function testLoad(): void
	{
		$this->envFile	= sys_get_temp_dir().'/CeusMediaCommonEnvTest.env';

		file_put_contents( $this->envFile, "ENVTEST_MODE = dev\nENVTEST_NAME = \"My App\"\nENVTEST_DEBUG = true\nENVTEST_PORT = 8080\nENVTEST_EMPTY =\n" );
		Env::load( $this->envFile );
		self::assertSame( 'dev', Env::get( 'ENVTEST_MODE' ) );
		self::assertSame( 'My App', Env::get( 'ENVTEST_NAME' ) );
		self::assertSame( '1', Env::get( 'ENVTEST_DEBUG' ) );
		self::assertSame( '8080', Env::get( 'ENVTEST_PORT' ) );
		self::assertSame( '', Env::get( 'ENVTEST_EMPTY', 'default' ) );

		file_put_contents( $this->envFile, "[envtest.db]\nhost = localhost\nname = \"my db\"\n[envtest.mail]\nfrom = info@example.com\n" );
		Env::load( $this->envFile );
		self::assertSame( 'localhost', Env::get( 'envtest_db_host' ) );
		self::assertSame( 'my db', Env::get( 'envtest_db_name' ) );
		self::assertSame( 'info@example.com', Env::get( 'envtest_mail_from' ) );
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
