<?php
import( 'de.ceus-media.console.ConsoleApplication' );
/**
 *	Base Implementation of a Unix Demon.
 *	@package		server
 *	@subpackage		daemon
 *	@extends		ConsoleApplication
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2006
 *	@version		0.1
 */
/**
 *	Base Implementation of a Unix Demon.
 *	@package		server
 *	@subpackage		daemon
 *	@extends		ConsoleApplication
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2006
 *	@version		0.1
 */
class Daemon extends ConsoleApplication
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int		$time_limit		Run Time Limitation in Seconds (for Development), default=10s, set 0 for unlimited Run Time 
	 *	@return		void
	 */
	public function __construct( $time_limit = 10)
	{
		set_time_limit( $time_limit );
		ob_implicit_flush( 1 );
		parent::__construct();
	}
	
	/**
	 *	Main Loop of Daemon with Sleep Time, to be overwritten.
	 *	@access		public
	 *	@return		void
	 */
	function main()
	{
		while( 1 )
		{
			$this->serve();
			ob_flush();
			$this->sleep();
		}
	}
	
	/**
	 *	Main Method for Service, to be overwritten.
	 *	@access		public
	 *	@return		void
	 */
	function serve()
	{
		echo "\n".time();
	}
	
	/**
	 *	Sleep Method of Service, to be overwritten or used with 1 Second.
	 *	@access		public
	 *	@return		void
	 */
	function sleep()
	{
		sleep(1);
	}
	
	/**
	 *	Stops Daemon.
	 *	@access		public
	 *	@return		void
	 */
	function quit( $return )
	{
		return $return;
	}
	
	/**
	 *	Sets 'Usage Shortcuts', to be overwritten.
	 *	@access		protected
	 *	@return		void
	 */
	protected function setShortcuts()
	{
	}
	
	/**
	 *	Default 'Usage' Method, to be overwritten.
	 *	@access		protected
	 *	@return		void
	 */
	protected function showUsage()
	{
		echo "\n";
		echo "Daemon v0.1\n";
		echo "Usage: no information given, yet.";
		die();
	}
}
?>