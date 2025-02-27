<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Base Implementation of a Unix Demon.
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
 *	@package		CeusMedia_Common_CLI_Server
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Server;

use CeusMedia\Common\CLI\Application;
use CeusMedia\Common\CLI;

/**
 *	Base Implementation of a Unix Demon.
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Server
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Daemon extends Application
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int		$timeLimit		Run Time Limitation in Seconds (for Development), default=10s, set 0 for unlimited Run Time
	 *	@return		void
	 */
	public function __construct( int $timeLimit = 10 )
	{
		set_time_limit( $timeLimit );
		ob_implicit_flush( TRUE );
		parent::__construct();
	}

	/**
	 *	Main Loop of Daemon with Sleep Time, to be overwritten.
	 *	@access		public
	 *	@return		void
	 */
	public function main(): void
	{
		while( TRUE ){
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
	public function serve(): void
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
	 *	@param		string|NULL		$message		Message to show below usage lines
	 *	@return		void
	 */
	protected function showUsage( ?string $message = NULL ): void
	{
		CLI::out();
		CLI::out( 'Daemon v0.1' );
		CLI::out();
		CLI::out( 'Usage: no information given, yet.' );
		CLI::out();
		if( $message )
			$this->showError( $message );
	}

	/**
	 *	Sleep Method of Service, to be overwritten or used with 1 Second.
	 *	@access		public
	 *	@return		void
	 */
	public function sleep(): void
	{
		sleep(1);
	}
}
