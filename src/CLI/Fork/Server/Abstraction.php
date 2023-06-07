<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	Copyright (c) 2010-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_CLI_Fork_Server
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Fork\Server;

use CeusMedia\Common\CLI\Fork\Server\Exception as ForkServerException;
use CeusMedia\Common\CLI\Fork\Server\SocketException as ForkServerSocketException;
use JetBrains\PhpStorm\NoReturn;
use Throwable;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Fork_Server
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
abstract class Abstraction
{
	public const E_LISTEN_FAILED		= 'No sense in creating socket';
	public const E_ACCEPT_FAILED		= 'Miscommunicating';
	public const E_READ_FAILED			= 'Misread';
	public const E_WRITE_FAILED			= 'Miswritten';

	protected array $childrenMap		= [];
	protected int $childrenMax			= 30;
	protected int $childrenOpen			= 0;
	protected ?array $listenExcept		= NULL;
	protected ?array $listenWrite		= null;
	protected int $statSeenMax			= 0;
	protected int $statSeenTotal		= 0;
	protected bool $signalTerm			= FALSE;
	protected bool $signalHangup		= FALSE;
	protected int $sizeBuffer			= 2048;
	protected int $socketPort			= 8000;
	protected ?int $timeStarted			= NULL;
	protected string $filePid			= "pid";

	public function __construct( ?int $port = NULL, bool $force = FALSE )
	{
		if( !is_null( $port ) )
			$this->setPort( $port );
		//	Give us eternity to execute the script. We can always kill -9
		ini_set( 'max_execution_time', '0' );
		ini_set( 'max_input_time', '0' );
		set_time_limit( 0 );
		try
		{
			$this->setUp( $force )->run();
		}
		catch( ForkServerSocketException $e )
		{
			$this->handleServerSocketException( $e );
		}
		catch( ForkServerException $e )
		{
			$this->handleServerException( $e );
		}
		catch( Throwable $e )
		{
			die( "!!! Not handled: ".$e->getMessage()."\n" );
		}
	}

	public function getPid(): int
	{
		if( !$this->isRunning() )
			throw new ForkServerException( 'Server is not running' );
		return intval( trim( file_get_contents( $this->filePid ) ) );
	}

	abstract protected function handleRequest( string $request ): string|int|NULL;

	protected function handleServerException(Throwable $e ): void
	{
		die( $e->getMessage()."\n" );
	}

	//	Do funky things with signals
	protected function handleSignal( int $signalNumber ): void
	{
		switch( $signalNumber ){
			case SIGTERM:
				$this->signalTerm = TRUE;
				@unlink( $this->filePid );
				break;
			case SIGHUP:
				$this->signalHangup = TRUE;
				break;
			default:
				$this->report( 'Funny signal: ' . $signalNumber );
		}
	}

	protected function handleServerSocketException( ForkServerSocketException $e ): void
	{
		$key		= md5( (string) time() );
		$dump		= serialize( $e );
		$code		= $e->getCode();
		$error		= socket_strerror( $code );
		$message	= $e->getMessage()." (".$error.")\n";
		error_log( $key.":".$dump."\n", 3, "error.socket.dump.log" );
		error_log( "Time:".time()."|".$code."|".$key."|".$message, 3, "error.socket.log" );
		echo $message;
	}

	public function isRunning(): bool
	{
		if( !file_exists( $this->filePid ) )
			return FALSE;
		if( !is_readable( $this->filePid ) )
			return FALSE;
		return TRUE;
	}

	protected function report( string $string ): void
	{
		echo $string."\n";
	}

	/**
	 *	@return		void
	 */
	protected function run(): void
	{
		//	Fork and exit (daemonize)
		$pid = pcntl_fork();
		//	Not good.
		if( $pid == -1 )
			throw new ForkServerException( 'Could not fork' );

		else if( $pid ){
			file_put_contents( $this->filePid, $pid );
			exit();
		}

//  not used
#		$parentpid = posix_getpid();

		//	And we're off!
		while( !$this->signalTerm ){
			//	Set up listener
			if( ( $sock = socket_create_listen( $this->socketPort, SOMAXCONN ) ) === FALSE ){
				$this->signalHangup = TRUE;
				$errNo	= socket_last_error();
				throw new ForkServerSocketException( self::E_LISTEN_FAILED, $errNo );
			}
			//	Whoop-tee-loop!
			//	Patiently wait until some of our children dies. Make sure we don't use all powers that be.
			/** @noinspection PhpConditionAlreadyCheckedInspection */
			while( !$this->signalHangup && !$this->signalTerm ){
				while( pcntl_wait( $status, WNOHANG | WUNTRACED ) > 0 ){
					usleep( 5000 );
				}
				foreach( $this->childrenMap as $key => $val ){
					if( !posix_kill( $val, 0 ) ){
						unset( $this->childrenMap[$key] );
						$this->childrenOpen = $this->childrenOpen - 1;
					}
				}
				$this->childrenMap = array_values( $this->childrenMap );
				if( $this->childrenOpen >= $this->childrenMax ){
					usleep( 5000 );
					continue;
				}

				//	Wait for somebody to talk to.
				$readArray = [$sock];
				/** @noinspection PhpRedundantOptionalArgumentInspection */
				if( socket_select( $readArray, $this->listenWrite, $this->listenExcept, 0, 0 ) <= 0 ){
					usleep( 5000 );
					continue;
				}

				if( ( $conn = socket_accept( $sock ) ) === FALSE ){
					$this->signalHangup = TRUE;
					$errNo	= socket_last_error();
					throw new ForkServerSocketException( self::E_ACCEPT_FAILED, $errNo );
				}

				//	Fork a child.
				$this->childrenOpen++;
				$this->statSeenTotal++;

				if( $this->childrenOpen > $this->statSeenMax ){
					$this->statSeenMax = $this->childrenOpen;
				}

				$pid = pcntl_fork();
				//	Not good.
				if( $pid == -1 ){
					throw new ForkServerException( 'Could not fork' );
				}
				//	This is the parent. It doesn't do much.
				else if( $pid ){
					socket_close( $conn );
					$this->childrenMap[] = $pid;
					usleep( 5000 );
				}
				//	This is a child. It dies, hopefully.
				else{
					socket_close( $sock );

					//	Happy buffer reading!
					/** @noinspection PhpRedundantOptionalArgumentInspection */
					$tbuf = socket_read( $conn, $this->sizeBuffer, PHP_BINARY_READ );
					if( $tbuf === FALSE ){
						$errNo	= socket_last_error();
						throw new ForkServerSocketException( self::E_READ_FAILED, $errNo );
					}
					$rbuf = $tbuf;
					while( strlen( $tbuf ) == $this->sizeBuffer ){
						/** @noinspection PhpRedundantOptionalArgumentInspection */
						$tbuf = socket_read( $conn, $this->sizeBuffer, PHP_BINARY_READ );
						if( $tbuf === FALSE ){
							$errNo	= socket_last_error();
							throw new ForkServerSocketException( self::E_READ_FAILED, $errNo );
						}
						$rbuf .= $tbuf;
					}

					//	Formulating answer
					$wbuf	= $this->handleRequest( $rbuf );

					//	Going postal!
					if( socket_write( $conn, (string) $wbuf ) === FALSE ){
						$errNo	= socket_last_error();
						throw new ForkServerSocketException( self::E_WRITE_FAILED, $errNo );
					}

					//	Let's die!
					socket_close( $conn );
					exit();
				}
			}

			//	Patiently wait until all our children die.
			while( pcntl_wait( $status, WNOHANG | WUNTRACED ) > 0 ){
				usleep( 5000 );
			}

			//	Kill the listener.
			socket_close( $sock );
			$this->signalHangup = FALSE;
			$this->timeStarted	= time();
		}
		//	Finally!
		exit();
	}

	public function setPort( int $port ): self
	{
		$this->socketPort	= $port;
		return $this;
	}

	protected function setUp( bool $force = FALSE ): self
	{
		if( $this->isRunning() ){
			if( $force ){
				if( posix_kill( $this->getPid(), 9 ) )
					unlink( $this->filePid );
			}
			else
				throw new ForkServerException( 'Server is already running' );
		}

		//	Set up the basic
		declare( ticks = 1 );
		$this->timeStarted	= time();

		pcntl_signal( SIGTERM, [&$this, "handleSignal"] );
		pcntl_signal( SIGHUP, [&$this, "handleSignal"] );
		return $this;
	}
}
