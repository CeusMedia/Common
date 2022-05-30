<?php
/**
 *	TestUnit of CLI_Command_BackgroundProcess.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of CLI_Command_BackgroundProcess.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class Test_CLI_Command_BackgroundProcessTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->process	= new CLI_Command_BackgroundProcess();
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
		$assertion	= new CLI_Command_BackgroundProcess;
		$creation	= CLI_Command_BackgroundProcess::newInstance();
		$this->assertEquals( $assertion, $creation );
	}

	public function testSetCommand()
	{
		$command	= 'ls -lah';
		$process	= new Test_CLI_Command_BackgroundProcessInstance();
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
class Test_CLI_Command_BackgroundProcessInstance extends CLI_Command_BackgroundProcess
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
