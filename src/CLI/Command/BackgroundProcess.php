<?php
/**
 *	...
 *
 *	Copyright (c) 2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_CLI_Command
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
namespace CeusMedia\Common\CLI\Command;

use CeusMedia\Common\FS\File\Reader as FileReader;
use Exception;
use InvalidArgumentException;
use RangeException;
use RuntimeException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Command
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class BackgroundProcess
{
	protected static $pidMin	= 2;

	protected static $pidMax	= 32768;

	protected $pid				= 0;

	protected $command;

	/**
	 *	Constructor.
	 *	Determines maximum process ID.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		try{
			self::$pidMax	= FileReader::load( '/proc/sys/kernel/pid_max' );
		}
		catch( Exception $e ){}
	}

	/**
	 *	Return set command.
	 *	@access		public
	 *	@return		?string
	 */
	public function getCommand(): ?string
	{
		return $this->command;
	}

	/**
	 *	Returns process ID (PID) of process if started.
	 *	@access		public
	 *	@param		boolean		$strict		Flag: throw exceptions on errors (default: yes)
	 *	@return		integer					Process ID (PID) of running process
	 *	@throws		RuntimeException		if process has not been started or has been stopped and strict mode is enabled
	 */
	public function getPid( bool $strict = TRUE ): int
	{
		$this->ensurePid( $strict );
		return $this->pid;
	}

	/**
	 *	Indicates whether process is still running.
	 *	@access		public
	 *	@static
	 *	@param		boolean		$strict		Flag: throw exceptions on errors (default: yes)
	 *	@return		boolean
	 */
	public function getStatus( bool $strict = TRUE ): bool
	{
		if( !$this->ensurePid( $strict ) )
			return FALSE;
		$command = 'ps -p '.$this->pid;
		exec( $command, $op );
		return isset( $op[1] );
	}

	/**
	 *	Creates new instance statically.
	 *	@access		public
	 *	@static
	 *	@return		self
	 */
	public static function newInstance(): self
	{
		return new self();
	}

	/**
	 *	Sets command to be executed.
	 *	@access		public
	 *	@param		string		$command		Command to be executed
	 *	@return		self
	 *	@throws		InvalidArgumentException	if no command has been set
	 */
	public function setCommand( string $command ): self
	{
		if( !strlen( trim( $command ) ) )
			throw new InvalidArgumentException( 'Command cannot be empty' );
		if( $this->getStatus( FALSE ) )
			throw new InvalidArgumentException( 'Command cannot be changed on a running process' );
		$this->command	= $command;
		$this->pid		= 0;
		return $this;
	}

	/**
	 *	Sets process ID (pid).
	 *	Validates PID to be between self::$pidMin and self::$pidMax.
	 *	@access		public
	 *	@param		integer		$pid		Process ID
	 *	@return		self
	 *	@throws		RangeException			if given PID is not between self::$pidMin and self::$pidMax
	 */
	public function setPid( int $pid ): self
	{
		if( $pid < static::$pidMin || $pid > static::$pidMax ){
			$msg	= 'Invalid PID (must be between %d and %d)';
			throw new RangeException( sprintf( $msg, static::$pidMin, static::$pidMax ) );
		}
		$this->pid = $pid;
		return $this;
	}

	/**
	 *	Starts process if set command has not been executed, yet.
	 *	@access		public
	 *	@return		self
	 *	@throws		RuntimeException		if command has already been executed
	 *	@throws		RuntimeException		if no command has been set
	 */
	public function start(): self
	{
		if( $this->pid )
			throw new RuntimeException( 'Process already has been started' );
		if( !strlen( trim( $this->command ) ) )
			throw new RuntimeException( 'No command set' );
		$this->runCommand();
		return $this;
    }

	/**
	 *	Stops process by killing it.
	 *	@access		public
	 *	@param		boolean		$strict		Flag: throw exceptions on errors (default: yes)
	 *	@return		self
	 */
	public function stop( bool $strict = TRUE ): self
	{
		if( $this->getStatus( $strict ) ){
			$command = 'kill '.$this->pid;
			exec( $command );
			$this->pid	= 0;
		}
		return $this;
	}

	//  --  PROTECTED  --  //

	/**
	 *	Ensures that there is a process ID (PID).
	 *	Throws exception in strict mode.
	 *	@access		protected
	 *	@param		boolean		$strict			Flag: strict mode (default: yes)
	 *	@return		boolean
	 *	@throws		RuntimeException			if no process ID is known and strict mode is enabled
	 */
	protected function ensurePid( bool $strict = TRUE ): bool
	{
		if( $this->pid )
			return TRUE;
		if( $strict )
			throw new RuntimeException( 'Process has not been started or has been stopped' );
		return FALSE;
	}

	/**
	 *	Runs command in background and fetches its process ID.
	 *	@access		protected
	 *	@return		void
	 *	@todo		handle errors
	 */
	protected function runCommand()
	{
		$command	= 'nohup '.$this->command.' > /dev/null 2>&1 & echo $!';
		exec( $command, $op );
		$this->pid	= (int) $op[0];
	}
}
