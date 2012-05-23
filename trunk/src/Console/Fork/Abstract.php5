<?php
/**
 *	Abstract forking application supporting to clone the current process.
 *	Create an application by extending by child and parent code.
 *
 *	Copyright (c) 2010-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Console.Fork
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Abstract forking application supporting to clone the current process.
 *	Create an application by extending by child and parent code.
 *
 *	@category		cmClasses
 *	@package		Console.Fork
 *	@abstract		Extend by child (and parent) code.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 *	@todo			Code doc
 */
abstract class Console_Fork_Abstract{

	protected $pids			= array();
	protected $isBlocking;

	public function __construct($blocking = FALSE) {
		$this->isBlocking	= (int) $blocking;
	}

	protected function cleanUpForks() {
		if( pcntl_wait($status, WNOHANG OR WUNTRACED) < 1 ) {
			foreach($this->pids as $nr => $pid) {
				if(!posix_kill($pid, 0)) {									// This detects if the child is still running or not
					unset($this->pids[$nr]);
				}
			}
		}
	}

	protected function fork() {
		$arguments	= func_get_args();
		$pid		= pcntl_fork();
		if($pid == -1) {
			throw new RuntimeException('Could not fork');
		}
		if($pid) {																// parent process runs what is here
			$this->runInParent($arguments);
			if($this->isBlocking)
				pcntl_waitpid($pid, $status, WUNTRACED);						// wait until the child has finished processing then end the script
			else
				$this->pids[]	= $pid;
		}
		else {																	// child process runs what is here
			return $this->runInChild($arguments);
		}
		if(!$this->isBlocking)
			$this->cleanUpForks();
	}

	abstract protected function runInChild($arguments = array());

	abstract protected function runInParent($arguments = array());
}
?>