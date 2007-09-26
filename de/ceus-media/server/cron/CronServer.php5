<?php
import( 'de.ceus-media.server.cron.CronParser' );
import( 'de.ceus-media.file.log.LogFile' );
/**
 *	CronServer.
 *	@package		server
 *	@subpackage		cron
 *	@uses			CronParser
 *	@uses			LogFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2006
 *	@version		0.1
 */
/**
 *	CronServer.
 *	@package		server
 *	@subpackage		cron
 *	@uses			CronParser
 *	@uses			LogFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2006
 *	@version		0.1
 */
class CronServer
{
	/**	@var		string	$_crontab	Cron Tab File */
	var $_crontab;
	/**	@var		string	$_log		Message Log File */
	var $_log;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$_crontab	Cron Tab File
	 *	@param		string	$_log		Message Log File
	 *	@return		void
	 */
	public function __construct( $crontab, $logfile = "cron.log" )
	{
		$this->_crontab	= $crontab;
		$this->_log		= new LogFile( $logfile );
		ob_implicit_flush();
		set_time_limit( 0 );
	}
	
	/**
	 *	Executes Service once or Starts as Service.
	 *	@access		public
	 *	@param		bool		$service		Run as Service
	 *	@return		void
	 */
	function serve( $service = false )
	{
		$lastminute	= $service ? date( "i", time() ) : "-1";
		do
		{
			if( $lastminute	!= date( "i", time() ) )
			{
				$cp	= new CronParser( $this->_crontab );
				$jobs	= $cp->getJobs();
				foreach( $jobs as $job )
				{
					if( $job->_checkMaturity() )
					{
						$content	= $job->execute();
						if( $content )
						{
							$content	= preg_replace( "@((\\r)?\\n)+$@", "", $content );
							$this->_log->addEntry( $content );
						}
					}
				}
			}
			if( $service )
			{
				$lastminute	= date( "i", time() );
				sleep( 1 );
			}
		}
		while( $service );
	}
}
?>