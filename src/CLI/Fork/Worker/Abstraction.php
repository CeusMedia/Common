<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	Copyright (c) 2010-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Fork_Worker
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Fork\Worker;

use RuntimeException;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Fork_Worker
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
abstract class Abstraction
{
	protected ?bool $isWindows	= NULL;

	/**
	 *	Constructor, checks Server Operating System.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$os	= substr( PHP_OS, 0, 3 );
		if( strtoupper( $os ) == 'WIN' )
			throw new RuntimeException( 'Not possible on Windows' );
	}

	public function forkWorkers( int $numberWorkers = 1 ): void
	{
		$numberWorkers	= abs( $numberWorkers );
		for( $i=0; $i<$numberWorkers; $i++ ){
			//	Fork and exit (daemonize)
			$pid = pcntl_fork();
			//	Not good.
			if( $pid == -1 )
				//  Fork was not possible
				throw new RuntimeException( 'Could not fork' );
			//  Parent
			if( $pid ){
				$isLast	= $i == $numberWorkers - 1;
				//  do Parent Stuff
				$this->workParent( $pid/*, $isLast*/ );
			}
			else{
				$code	= $this->workChild( $pid, $i );
				exit( $code );
			}
		}
	}

	/**
	 *	Implement this method to set up or validate settings before forking.
	 *	Throw an Exception if something is wrong.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
	}

	protected function handleHangupSignal(): void
	{
	}

	/**
	 *	Handle Process Signals.
	 *	@access		protected
	 *	@param		int			$signalNumber
	 *	@return		void
	 */
	protected function handleSignal( int $signalNumber ): void
	{
		switch( $signalNumber ){
			case SIGHUP:
				$this->handleHangupSignal();
				break;
			case SIGTERM:
				$this->handleTerminationSignal();
				break;
			default:
				$this->handleUnknownSignal( $signalNumber );
		}
	}

	protected function handleTerminationSignal(): void
	{
	}

	protected function handleUnknownSignal( int $signalNumber ): void
	{
//		$this->report( 'Unknown signal: ' . $signalNumber );
	}

//	protected function report( $message )
//	{
//
//	}

	/**
	 *	This method is executed by the Child Process only.
	 *	Please implement this method and return an Error Code, Error Message or 0 or an empty String.
	 *	@access		protected
	 *	@param		int			$pid			Parent PID
	 *	@param		int			$workerNumber	Worker Number, set by loop in Parent Worker
	 *	@return		int|string	Error Code or Error Message
	 */
	abstract protected function workChild( int $pid, int $workerNumber ): int|string;

	/**
	 *	This method is executed by the Parent Process only.
	 *	You need to implement this method, but it can be empty.
	 *	@access		protected
	 *	@param		int			$pid			Parent PID
	 *	@return		int|string	Error Code or Error Message
	 */
	abstract protected function workParent( int $pid ): int|string;
}
