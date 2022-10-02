<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Abstract forking application supporting to clone the current process.
 *	Create an application by extending by child and parent code.
 *
 *	Copyright (c) 2010-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_CLI_Fork
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Fork;

use RuntimeException;

/**
 *	Abstract forking application supporting to clone the current process.
 *	Create an application by extending by child and parent code.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Fork
 *	@abstract		Extend by child (and parent) code.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code doc
 */
abstract class Abstraction
{
	protected $pids			= [];

	protected $isBlocking;

	public function __construct( bool $blocking = FALSE )
	{
		$this->isBlocking	= (int) $blocking;
	}

	protected function cleanUpForks()
	{
		if( pcntl_wait( $status, WNOHANG OR WUNTRACED ) < 1 ){
			foreach( $this->pids as $nr => $pid ){
				// This detects if the child is still running or not
				if( !posix_kill( $pid, 0 ) ){
					unset( $this->pids[$nr] );
				}
			}
		}
	}

	protected function fork()
	{
		$arguments	= func_get_args();
		$pid		= pcntl_fork();
		if( $pid == -1 )
			throw new RuntimeException('Could not fork');

		// parent process runs what is here
		if( $pid ){
			$this->runInParent( $arguments );
			if( $this->isBlocking )
				// wait until the child has finished processing then end the script
				pcntl_waitpid( $pid, $status, WUNTRACED );
			else
				$this->pids[]	= $pid;
		}
		// child process runs what is here
		else {
			$this->runInChild( $arguments );
			return;
		}
		if( !$this->isBlocking )
			$this->cleanUpForks();
	}

	abstract protected function runInChild( array $arguments = [] );

	abstract protected function runInParent( array $arguments = [] );
}
