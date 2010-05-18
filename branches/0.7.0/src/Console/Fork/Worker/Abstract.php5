<?php
/**
 *	...
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		Console.Fork.Worker
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id: Abstract.php5 607 2010-03-24 23:39:16Z christian.wuerker $
 */
/**
 *	...
 *
 *	@category		cmClasses
 *	@package		Console.Fork.Worker
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id: Abstract.php5 607 2010-03-24 23:39:16Z christian.wuerker $
 */
abstract class Console_Fork_Worker_Abstract
{
	protected $isWindows	= NULL;
	/**
	 *	Constructor, checks Server Operation System.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$os	= substr( PHP_OS, 0, 3 );
		if( strtoupper( $os ) == 'WIN' )
			throw new RuntimeException( 'Not possible on Windows' );
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
	
	public function forkWorkers( $numberWorkers = 1 )
	{
		$numberWorkers	= abs( (int) $numberWorkers );
		for( $i=0; $i<$numberWorkers; $i++ )
		{
			$pid = pcntl_fork();																		//	Fork and exit (daemonize)
			if( $pid == -1 )																			//	Not good.
				throw new RuntimeException( 'Could not fork' );											//  Fork was not possible
			if( $pid )																					//  Parent
			{
				$isLast	= $i == $numberWorkers - 1;
				$this->workParent( $pid, $isLast );														//  do Parent Stuff
			}	
			else
			{
				$code	= $this->workChild( $pid, $i );
				exit( $code );
			}
		}
	}

	/**
	 *	This method is executed by the Parent Process only.
	 *	You need to implement this method but it can by empty.
	 *	@access		protected
	 *	@param		int			$pid			Parent PID
	 *	@return		int|string	Error Code or Error Message
	 */
	abstract protected function workParent( $pid );
	
	/**
	 *	This method is executed by the Child Process only.
	 *	Please implement this method and return an Error Code, Error Message or 0 or an empty String.
	 *	@access		protected
	 *	@param		int			$pid			Parent PID
	 *	@param		int			$numberWorker	Worker Number, set by loop in Parent Worker
	 *	@return		int|string	Error Code or Error Message
	 */
	abstract protected function workChild( $pid, $workerNumber );

	
	/**
	 *	Handle Process Signals.
	 *	@access		protected
	 *	@param		int			$signalNumber
	 *	@return		void
	 */
	protected function handleSignal( $signalNumber )
	{
		switch( $signalNumber )
		{
			case SIGHUP:
				$this->handleHangupSignal();
				break;
			case SIGTERM:
				$this->handleTerminationSignal();
				break;
			default:
				$this->handleUnknownSignal();
		}
	}
	
	protected function handleHangupSignal()
	{
	}
	
	protected function handleTerminationSignal()
	{
	}

	protected function handleUnknownSignal( $signalNumber )
	{
//		$this->report( 'Unknown signal: ' . $signalNumber );
	}
	
//	protected function report( $message )
//	{
//	
//	}
}
?>