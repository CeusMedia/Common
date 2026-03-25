<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Cron Server.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Server\Cron;

use CeusMedia\Common\FS\File\Log\Writer as LogWriter;

/**
 *	Cron Server.
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Server_Cron
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class Daemon
{
	/**	@var		string		$cronTab		Cron Tab File */
	protected string $cronTab;
	/**	@var		LogWriter	$logFile		Message Log File */
	protected LogWriter $logFile;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$cronTab		Cron Tab File
	 *	@param		string		$logFile		Message Log File
	 *	@return		void
	 */
	public function __construct( string $cronTab, string $logFile = "cron.log" )
	{
		$this->cronTab	= $cronTab;
		$this->logFile	= new LogWriter( $logFile );
		ob_implicit_flush();
		set_time_limit( 0 );
	}

	/**
	 *	Executes Service once or Starts as Service.
	 *	@access		public
	 *	@param		bool		$service		Run as Service
	 *	@return		void
	 */
	public function serve( bool $service = FALSE )
	{
		$lastminute	= $service ? date( "i", time() ) : "-1";
		do{
			if( $lastminute	!= date( "i", time() ) ){
				$cp	= new Parser( $this->cronTab );
				$jobs	= $cp->getJobs();
				foreach( $jobs as $job ){
					if( $job->checkMaturity() ){
						$content	= $job->execute();
						if( $content ){
							$content	= preg_replace( "@((\\r)?\\n)+$@", "", $content );
							$this->logFile->note( $content );
						}
					}
				}
			}
			if( $service ){
				$lastminute	= date( "i", time() );
				sleep( 1 );
			}
		}
		while( $service );
	}
}
