<?php
/**
 *	TestUnit of CLI_Command_Program.
 *	@package		Tests.console.command
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			12.01.2009
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of CLI_Command_Program.
 *	@package		Tests.console.command
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			12.01.2009
 */
class Test_CLI_Command_ProgramTest extends Test_Case
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

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct1()
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRun()
	{
		$program	= new Test_CLI_Command_TestProgram;
		$assertion	= 2;
		$creation	= $program->run( "arg1" );
		$this->assertEquals( $assertion, $creation );
	}
}
class Test_CLI_Command_TestProgram extends CLI_Command_Program
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

	protected function main()
	{
		return 2;
	}

	public function getArguments()
	{
		return $this->arguments;
	}

	public function getOptions()
	{
		return $this->options;
	}
}
