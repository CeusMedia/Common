<?php
/**
 *	TestUnit of Console_Command_Program.
 *	@package		Tests.console.command
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.01.2009
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Console_Command_Program.
 *	@package		Tests.console.command
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Console_Command_Program
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.01.2009
 *	@version		0.1
 */
class Test_Console_Command_ProgramTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
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
		$program	= new Test_Console_Command_TestProgram;
		$assertion	= 2;
		$creation	= $program->run();
		$this->assertEquals( $assertion, $creation );
	}
}
class Test_Console_Command_TestProgram extends Console_Command_Program
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

?>