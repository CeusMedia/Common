<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	Base class for tests of Net\FTP, running a local test FTP server.
 *	@package		Tests.Net.FTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net\FTP;

use CeusMedia\CommonTest\BaseCase;

/**
 *	Base class for tests of Net\FTP, running a local test FTP server.
 *	The server (assets/ftpServer.php) serves a temporary folder, which is given as local path to the tests.
 *	Tests are skipped if the server cannot be started or the FTP extension is missing.
 *	@package		Tests.Net.FTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
abstract class FtpTestCase extends BaseCase
{
	/** @var resource|NULL */
	protected static $serverProcess	= NULL;

	/** @var array<string,string|int> */
	protected static array $ftpConfig	= [];

	public static function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass();
		self::$ftpConfig	= [];
		if( !extension_loaded( 'ftp' ) || !function_exists( 'proc_open' ) )
			return;

		$socket	= @stream_socket_server( 'tcp://127.0.0.1:0' );
		if( FALSE === $socket )
			return;
		$address	= (string) stream_socket_get_name( $socket, FALSE );
		fclose( $socket );
		$port		= (int) substr( $address, strrpos( $address, ':' ) + 1 );

		$root		= sys_get_temp_dir().'/CeusMediaCommonTestFtp'.getmypid();
		@mkdir( $root, 0777, TRUE );
		$command	= [PHP_BINARY, __DIR__.'/assets/ftpServer.php', (string) $port, $root, 'test', 'secret'];
		$pipes		= [];
		$process	= proc_open( $command, [1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']], $pipes );
		if( !is_resource( $process ) )
			return;
		for( $i = 0; $i < 50; $i++ ){
			$connection	= @fsockopen( '127.0.0.1', $port, $errorCode, $errorMessage, 0.1 );
			if( FALSE !== $connection ){
				fclose( $connection );
				self::$serverProcess	= $process;
				self::$ftpConfig		= [
					'host'	=> '127.0.0.1',
					'port'	=> $port,
					'user'	=> 'test',
					'pass'	=> 'secret',
					'path'	=> '',
					'local'	=> $root.'/',
				];
				return;
			}
			usleep( 100_000 );
		}
		proc_terminate( $process );
		proc_close( $process );
	}

	public static function tearDownAfterClass(): void
	{
		if( is_resource( self::$serverProcess ) ){
			proc_terminate( self::$serverProcess );
			proc_close( self::$serverProcess );
		}
		self::$serverProcess	= NULL;
		if( isset( self::$ftpConfig['local'] ) )
			self::removeFolder( (string) self::$ftpConfig['local'] );
		self::$ftpConfig	= [];
		parent::tearDownAfterClass();
	}

	/**
	 *	Returns connection data of test FTP server, or empty array if not available.
	 *	@return		array<string,string|int>
	 */
	protected static function getFtpConfig(): array
	{
		return self::$ftpConfig;
	}

	protected static function removeFolder( string $path ): void
	{
		if( !is_dir( $path ) )
			return;
		foreach( array_diff( scandir( $path ) ?: [], ['.', '..'] ) as $entry ){
			$entryPath	= rtrim( $path, '/' ).'/'.$entry;
			is_dir( $entryPath ) ? self::removeFolder( $entryPath ) : @unlink( $entryPath );
		}
		@rmdir( $path );
	}
}
