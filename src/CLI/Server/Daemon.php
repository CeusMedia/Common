<?php
/**
 *	Base Implementation of a Unix Demon.
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
 *	@package		CeusMedia_Common_CLI_Server
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			20.01.2006
 *	@version		$Id$
 */
/**
 *	Base Implementation of a Unix Demon.
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Server
 *	@extends		CLI_Application
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			20.01.2006
 *	@version		$Id$
 */
class CLI_Server_Daemon extends CLI_Application
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
	 *	Stops Daemon.
	 *	@access		public
	 *	@return		void
	 */
	public function quit( $return )
	{
		return $return;
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

	/**
	 *	Sleep Method of Service, to be overwritten or used with 1 Second.
	 *	@access		public
	 *	@return		void
	 */
	public function sleep()
	{
		sleep(1);
	}
}
?>
