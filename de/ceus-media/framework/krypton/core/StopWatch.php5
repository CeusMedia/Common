<?php
/**
 *	Stopwatch Implementation.
 *	@package		framework.krypton.core
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Stopwatch Implementation.
 *	@package		framework.krypton.core
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Framework_Krypton_Core_StopWatch
{
	/**	@var		string		$microtimeStart		microtime at the start */
	private $microtimeStart;
	/**	@var		string		$microtimeStop			microtime at the end */
	private $microtimeStop;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->start();
	}

	/**
	 *	Starts the Watch.
	 *	@access		public
	 *	@return		void
	 */
	public function start()
	{
		$this->microtimeStart = microtime();
	}

	/**
	 *	Stops the Watch and return the time difference between start and stop.
	 *	@access		public
	 *	@param		int			$base		Time Base ( 0 - sec | 3 - msec | 6 - µsec)
	 *	@param		int			$round		Numbers after dot
	 *	@return		string
	 */
	public function stop( $base = 3, $round = 3 )
	{
		$this->microtimeStop 	= microtime();
		return $this->result( $base, $round );
	}


	/**
	 *	Calculates the time difference between start and stop in microseconds.
	 *	@access		public
	 *	@param		int			$base		Time Base ( 0 - sec | 3 - msec | 6 - µsec)
	 *	@param		int			$round		Numbers after dot
	 *	@return		string
	 **/
	public function result( $base = 3, $round = 3 )
	{
		$start	= explode( ' ', $this->microtimeStart );
		$end	= explode( ' ', $this->microtimeStop );
		$sec		= $end[1] - $start[1];
		$msec	= $end[0] - $start[0];
		$time	= (float)$sec + $msec;
		$time	= $time * pow( 10, $base );
		$time	= round( $time, $round );
		return $time;
	}
}
?>
