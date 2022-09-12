<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of CLI_Command_Program.
 *	@package		Tests.CLI.Command
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\CLI;

use CeusMedia\Common\CLI\Command\Program;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of CLI_Command_Program.
 *	@package		Tests.CLI.Command
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ProgramTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

//	/**
//	 *	Tests Method '__construct'.
//	 *	@access		public
//	 *	@return		void
//	 */
//	public function testConstruct1()
//	{
//	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRun()
	{
		$program	= new TestProgram;
		$assertion	= 2;
		$creation	= $program->run( "arg1" );
		$this->assertEquals( $assertion, $creation );
	}
}
class TestProgram extends Program
{
	public $testOptions	= array(
		'user'		=> "@[a-z]@i",
		'password'	=> "@[a-z]@i",
		'force'		=> "",
		"long"		=> "@[0-9]@",
	);
	public $testShortcuts	= array(
		'f'		=> 'force',
		'u'		=> 'user',
		'p'		=> 'password',
	);

	public function __construct()
	{
		$options	= $this->testOptions;
		$shortcuts	= $this->testShortcuts;
		parent::__construct( $options, $shortcuts, 1 );
	}

	protected function main(): int
	{
		return 2;
	}

	public function getArguments(): ?array
	{
		return $this->arguments;
	}

	public function getOptions(): ?array
	{
		return $this->options;
	}
}
