<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of CLI_Command_BackgroundProcess.
 *	@package		Tests.CLI.Command
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\CLI\Command;

use CeusMedia\Common\CLI\Command\BackgroundProcess;
use CeusMedia\CommonTest\BaseCase;
use Exception;
use InvalidArgumentException;

/**
 *	TestUnit of CLI_Command_BackgroundProcess.
 *	@package		Tests.CLI.Command
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class BackgroundProcessTest extends BaseCase
{
	protected BackgroundProcess $process;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->process	= new BackgroundProcess();
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Method 'getArguments'.
	 *	@access		public
	 *	@return		void
	 */
	public function testNewInstance()
	{
		$assertion	= new BackgroundProcess;
		$creation	= BackgroundProcess::newInstance();
		$this->assertEquals( $assertion, $creation );
	}

	public function testSetCommand()
	{
		$command	= 'ls -lah';
		$process	= new BackgroundProcessInstance();
		$process->setCommand( $command );

		$assertion	= $command;
		$creation	= $process->getProtectedVar( 'command' );
		$this->assertEquals( $assertion, $creation );

		$process->start();
		$creation	= $process->getProtectedVar( 'pid' );
		$this->assertIsInt( $creation );
		$this->assertGreaterThan( 1, $creation );

		$process->setCommand( $command );
		$creation	= $process->getProtectedVar( 'command' );
		$this->assertEquals( $assertion, $creation );

		$creation	= $process->getProtectedVar( 'pid' );
		$this->assertIsInt( $creation );
		$this->assertEquals( 0, $creation );
	}

	public function testSetCommand_Exception1()
	{
		$this->expectException( InvalidArgumentException::class );
		$this->process->setCommand( '' );
	}

	public function testSetCommand_Exception2()
	{
		$this->expectException( InvalidArgumentException::class );
		$command	= 'sleep 1';
		$this->process->setCommand( $command )->start()->setCommand( $command );
	}

}
class BackgroundProcessInstance extends BackgroundProcess
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}

	public function setProtectedVar( $varName, $varValue )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		$this->$varName	= $varValue;
	}
}
