<?php
/**
 *	TestUnit of File_PHP_Check_MethodVisibility.
 *	@package		Tests.file.php
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.01.2009
 *	@version		$Id$
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of File_PHP_Check_MethodVisibility.
 *	@package		Tests.file.php
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_PHP_Check_MethodVisibility
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.01.2009
 *	@version		$Id$
 */
class Test_File_PHP_Check_MethodVisibilityTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path			= dirname( __FILE__ )."/";
		$this->fileTemp1	= $this->path."Test.class1.tmp.php5";
		$this->fileTemp2	= dirname( __FILE__ ).'/AllTests.php';
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$class	= "
class Test
{
	function alpha()
	{
	}

	function beta()
	{
	}

	public function gamma()
	{
	}

	function & delta()
	{
	}
}";
		File_Writer::save( $this->fileTemp1, $class );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->fileTemp1 );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$fileName	= __FILE__;
		$checker	= Test_MockAntiProtection::getInstance( 'File_PHP_Check_MethodVisibility', $fileName );
		
		$assertion	= $fileName;
		$creation	= $checker->getProtectedVar( 'fileName' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $checker->getProtectedVar( 'checked' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException()
	{
		$this->setExpectedException( 'RuntimeException' );
		$index	= new File_PHP_Check_MethodVisibility( "not_existing" );
	}

	/**
	 *	Tests Method 'check'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCheck1()
	{
		$checker	= new File_PHP_Check_MethodVisibility( $this->fileTemp2 );
		$assertion	= TRUE;
		$creation	= $checker->check();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'check'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCheck2()
	{
		$checker	= new File_PHP_Check_MethodVisibility( $this->fileTemp1 );
		$assertion	= FALSE;
		$creation	= $checker->check();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getMethods'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMethods1()
	{
		$checker	= new File_PHP_Check_MethodVisibility( $this->fileTemp2 );
		$checker->check();
		$assertion	= array();
		$creation	= $checker->getMethods();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getMethods'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMethods2()
	{
		$checker	= new File_PHP_Check_MethodVisibility( $this->fileTemp1 );
		$checker->check();
		$assertion	= array(
			'alpha',
			'beta',
			'delta'
		);
		$creation	= $checker->getMethods();
		$this->assertEquals( $assertion, $creation );
	}
}
?>