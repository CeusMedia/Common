<?php
/**
 *	CronParser.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_CLI_Server_Cron
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			20.01.2006
 *	@version		$Id$
 */
/**
 *	CronParser.
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Server_Cron
 *	@extends		ADT_OptionObject
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			20.01.2006
 *	@version		$Id$
 */
class CLI_Server_Cron_Job extends ADT_OptionObject
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
}
?>
