<?php
import( 'de.ceus-media.server.cron.CronParser' );
import( 'de.ceus-media.file.log.LogFile' );
/**
 *	Cron Server.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		console.server.cron
 *	@uses			Console_Server_Cron_Parser
 *	@uses			LogFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.01.2006
 *	@version		0.5
 */
/**
 *	Cron Server.
 *	@package		console.server.cron
 *	@uses			Console_Server_Cron_Parser
 *	@uses			File_Log_Writer
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2006
 *	@version		0.5
 */
class Console_Server_Cron_Daemon
{
	/**	@var		string		$cronTab		Cron Tab File */
	protected $cronTab;
	/**	@var		string		$logFile		Message Log File */
	protected $logFile;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$cronTab		Cron Tab File
	 *	@param		string		$logFile		Message Log File
	 *	@return		void
	 */
	public function __construct( $cronTab, $logFile = "cron.log" )
	{
		$this->cronTab	= $cronTab;
		$this->logFile	= new File_Log_Writer( $logFile );
		ob_implicit_flush();
		set_time_limit( 0 );
	}
	
	/**
	 *	Executes Service once or Starts as Service.
	 *	@access		public
	 *	@param		bool		$service		Run as Service
	 *	@return		void
	 */
	public function serve( $service = false )
	{
		$lastminute	= $service ? date( "i", time() ) : "-1";
		do
		{
			if( $lastminute	!= date( "i", time() ) )
			{
				$cp	= new Console_Server_Cron_Parser( $this->cronTab );
				$jobs	= $cp->getJobs();
				foreach( $jobs as $job )
				{
					if( $job->checkMaturity() )
					{
						$content	= $job->execute();
						if( $content )
						{
							$content	= preg_replace( "@((\\r)?\\n)+$@", "", $content );
							$this->logFile->note( $content );
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