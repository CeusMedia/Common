<?php
/**
 *	Base Implementation of a Unix Demon.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		console.server
 *	@extends		Console_Application
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.01.2006
 *	@version		$Id$
 */
import( 'de.ceus-media.console.Application' );
/**
 *	Base Implementation of a Unix Demon.
 *	@category		cmClasses
 *	@package		console.server
 *	@extends		Console_Application
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.01.2006
 *	@version		$Id$
 */
class Console_Server_Daemon extends Console_Application
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int		$timeLimit		Run Time Limitation in Seconds (for Development), default=10s, set 0 for unlimited Run Time 
	 *	@return		void
	 */
	public function __construct( $timeLimit = 10)
	{
		set_time_limit( $timeLimit );
		ob_implicit_flush( 1 );
		parent::__construct();
	}
	
	/**
	 *	Main Loop of Daemon with Sleep Time, to be overwritten.
	 *	@access		public
	 *	@return		void
	 */
	public function main()
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
	public function serve()
	{
		echo "\n".time();
	}
	
	/**
	 *	Sleep Method of Service, to be overwritten or used with 1 Second.
	 *	@access		public
	 *	@return		void
	 */
	public function sleep()
	{
		sleep(1);
	}
	
	/**
	 *	Stops Daemon.
	 *	@access		public
	 *	@return		void
	 */
	public function quit( $return )
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