<?php
import( 'de.ceus-media.adt.OptionObject' );
/**
 *	CronParser.
 *	@package		console.server.cron
 *	@extends		ADT_OptionObject
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2006
 *	@version		0.6
 */
/**
 *	CronParser.
 *	@package		console.server.cron
 *	@extends		ADT_OptionObject
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2006
 *	@version		0.6
 */
class Console-Server_Cron_Job extends ADT_OptionObject
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$action		String to execute within Cron Job
	 *	@return		void
	 */
	public function __construct( $action )
	{
		$this->setOption( "action", $action );
	}
	
	/**
	 *	Executes Cron Job and returns execution output.
	 *	@access		public
	 *	@return		string
	 */
	public function execute()
	{
		ob_start();
		passthru( $this->getOption( "action" ) );
		$content	= ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Indicates whether this job is mature.
	 *	@access		protected
	 *	@return		bool
	 */
	protected function checkMaturity()
	{
		$time	= time();
		$c_minute	= date( "i", $time );
		$c_hour		= date( "H", $time );
		$c_day		= date( "d", $time );
		$c_month	= date( "m", $time );
		$c_weekday	= date( "w", $time );

		$j_minute	= (array) $this->getOption( 'minute' );
		$j_hour		= (array) $this->getOption( 'hour' );
		$j_day		= (array) $this->getOption( 'day' );
		$j_month	= (array) $this->getOption( 'month' );
		$j_weekday	= (array) $this->getOption( 'weekday' );
		if( $j_weekday[0] == "*" || in_array( $c_weekday, $j_weekday ) )
			if( $j_month[0] == "*" || in_array( $c_month, $j_month ) )
				if( $j_day[0] == "*" || in_array( $c_day, $j_day ) )
					if( $j_hour[0] == "*" || in_array( $c_hour, $j_hour ) )
						if( $j_minute[0] == "*" || in_array( $c_minute, $j_minute ) )
							return true;	
		return false;	
	}
}
?>