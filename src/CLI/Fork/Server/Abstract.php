<?php
/**
 *	...
 *
 *	Copyright (c) 2010-2020 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 */
#require_once( "Exception.php5" );
/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Fork_Server
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 */
abstract class CLI_Fork_Server_Abstract
{
	const E_LISTEN_FAILED		= 'No sense in creating socket';
	const E_ACCEPT_FAILED		= 'Miscommunicating';
	const E_READ_FAILED			= 'Misread';
	const E_WRITE_FAILED		= 'Miswritten';

	protected $childrenMap		= array();
	protected $childrenMax		= 30;
	protected $childrenOpen		= 0;
	protected $listenExcept		= NULL;
	protected $listenWrite		= null;
	protected $statSeenMax		= 0;
	protected $statSeenTotal	= 0;
	protected $signalTerm		= FALSE;
	protected $signalHangup		= FALSE;
	protected $sizeBuffer		= 2048;
	protected $socketPort		= 8000;
	protected $timeStarted		= NULL;
	protected $filePid			= "pid";

	public function __construct( $port = NULL, bool $force = FALSE )
	{
		if( !is_null( $port ) )
			$this->setPort( $port );
		//	Give us eternity to execute the script. We can always kill -9
		ini_set( 'max_execution_time', '0' );
		ini_set( 'max_input_time', '0' );
		set_time_limit( 0 );
		try
		{
			$this->setUp( $force );
			$this->run();
		}
		catch( CLI_Fork_Server_SocketException $e )
		{
			$this->handleSocketException( $e );
		}
		catch( CLI_Fork_Server_Exception $e )
		{
			$this->handleServerException( $e );
		}
		catch( Exception $e )
		{
			die( "!!! Not handled: ".$e->getMessage()."\n" );
		}
	}

	public function getPid()
	{
		if( !$this->isRunning() )
			throw new CLI_Fork_Server_Exception( 'Server is not running' );
		return trim( file_get_contents( $this->filePid ) );
	}

	abstract protected function handleRequest( $request );

	protected function handleServerException( CLI_Fork_Server_Exception $e )
	{
		die( $e->getMessage()."\n" );
	}

	//	Do funky things with signals
	protected function handleSignal( int $signalNumber )
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

	protected function handleSocketException( CLI_Fork_Server_SocketException $e )
	{
		$key		= md5( time() );
		$dump		= serialize( $e );
		$code		= $e->getCode();
		$error		= socket_strerror( $code );
		$message	= $e->getMessage()." (".$error.")\n";
		error_log( $key.":".$dump."\n", 3, "error.socket.dump.log" );
		error_log( "Time:".time()."|".$code."|".$key."|".$message, 3, "error.socket.log" );
		echo $message;
	}

	public function isRunning()
	{
		if( !file_exists( $this->filePid ) )
			return FALSE;
		if( !is_readable( $this->filePid ) )
			return FALSE;
		return TRUE;
	}

	protected function report( $string )
	{
		echo $string."\n";
	}

	protected function run()
	{
		//	Fork and exit (daemonize)
		$pid = pcntl_fork();
		//	Not good.
		if( $pid == -1 )
			throw new CLI_Fork_Server_Exception( 'Could not fork' );

		else if( $pid ){
			file_put_contents( $this->filePid, $pid );
			exit();
		}

//  kriss: not used
#		$parentpid = posix_getpid();

		//	And we're off!
		while( !$this->signalTerm ){
			//	Set up listener
			if( ( $sock = socket_create_listen( $this->socketPort, SOMAXCONN ) ) === FALSE ){
				$this->signalHangup = TRUE;
				$errNo	= socket_last_error();
				throw new CLI_Fork_Server_SocketException( self::E_LISTEN_FAILED, $errNo );
			}
			//	Whoop-tee-loop!
			//	Patiently wait until some of our children dies. Make sure we don't use all powers that be.
			while( !$this->signalHangup && !$this->signalTerm ){
				while( pcntl_wait( $status, WNOHANG OR WUNTRACED ) > 0 ){
					usleep( 5000 );
				}
				while( list( $key, $val ) = each( $this->childrenMap ) ){
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
				if( socket_select( $rarray = array( $sock ), $this->listenWrite, $this->listenExcept, 0, 0 ) <= 0 ){
					usleep( 5000 );
					continue;
				}

				if( ( $conn = socket_accept( $sock ) ) === FALSE ){
					$this->signalHangup = TRUE;
					$errNo	= socket_last_error();
					throw new CLI_Fork_Server_SocketException( self::E_ACCEPT_FAILED, $errNo );
					continue;
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
					throw new CLI_Fork_Server_Exception( 'Could not fork' );
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
					while( TRUE ){
						//	Happy buffer reading!
						$tbuf = socket_read( $conn, $this->sizeBuffer, PHP_BINARY_READ );
						if( $tbuf === FALSE ){
							$errNo	= socket_last_error();
							throw new CLI_Fork_Server_SocketException( self::E_READ_FAILED, $errNo );
							break;
						}
						$rbuf = $tbuf;
						while( strlen( $tbuf ) == $this->sizeBuffer ){
							$tbuf = socket_read( $conn, $this->sizeBuffer, PHP_BINARY_READ );
							if( $tbuf === FALSE ){
								$errNo	= socket_last_error();
								throw new CLI_Fork_Server_SocketException( self::E_READ_FAILED, $errNo );
								break;
							}
							$rbuf .= $tbuf;
						}

						//	Formulating answer
						$wbuf	= $this->handleRequest( $rbuf );

						//	Going postal!
						if( socket_write( $conn, $wbuf ) === FALSE ){
							$errNo	= socket_last_error();
							throw new CLI_Fork_Server_SocketException( self::E_WRITE_FAILED, $errNo );
							break;
						}
						break;
					}

					//	Let's die!
					socket_close( $conn );
					exit();
				}
			}

			//	Patiently wait until all our children die.
			while( pcntl_wait( $status, WNOHANG OR WUNTRACED ) > 0 ){
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

	public function setPort( $port )
	{
		if( !is_int( $port ) )
			throw new InvalidArgumentException( 'Port must be of integer' );
		$this->socketPort	= $port;
	}

	protected function setUp( $force = FALSE ){
		if( $this->isRunning() ){
			if( $force ){
				if( posix_kill( $this->getPid(), 9 ) )
					unlink( $this->filePid );
			}
			else
				throw new CLI_Fork_Server_Exception( 'Server is already running' );
		}

		//	Set up the basic
		declare( ticks = 1 );
		$this->timeStarted	= time();

		pcntl_signal( SIGTERM, array( &$this, "handleSignal" ) );
		pcntl_signal( SIGHUP, array( &$this, "handleSignal" ) );
	}
}
