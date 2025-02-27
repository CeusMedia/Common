<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	CronParser.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_CLI_Server_Cron
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Server\Cron;

use CeusMedia\Common\ADT\OptionObject;

/**
 *	CronParser.
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Server_Cron
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Job extends OptionObject
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$action		String to execute within Cron Job
	 *	@return		void
	 */
	public function __construct( string $action )
	{
		parent::__construct();
		$this->setOption( "action", $action );
	}

	/**
	 *	Indicates whether this job is mature.
	 *	@access		protected
	 *	@return		bool
	 */
	protected function checkMaturity(): bool
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
		if( $j_weekday[0] == "*" || in_array( $c_weekday, $j_weekday, TRUE ) )
			if( $j_month[0] == "*" || in_array( $c_month, $j_month, TRUE ) )
				if( $j_day[0] == "*" || in_array( $c_day, $j_day, TRUE ) )
					if( $j_hour[0] == "*" || in_array( $c_hour, $j_hour, TRUE ) )
						if( $j_minute[0] == "*" || in_array( $c_minute, $j_minute, TRUE ) )
							return TRUE;
		return FALSE;
	}

	/**
	 *	Executes Cron Job and returns execution output.
	 *	@access		public
	 *	@return		string
	 */
	public function execute(): string
	{
		ob_start();
		passthru( $this->getOption( "action" ) );
		$content	= ob_get_contents();
		ob_end_clean();
		return $content;
	}
}
