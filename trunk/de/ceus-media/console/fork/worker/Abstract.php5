<?php
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