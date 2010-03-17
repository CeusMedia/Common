<?php
/**
 *	TestUnit of File_PHP_MethodVisibilityCheck.
 *	@package		Tests.file.php
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.01.2009
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of File_PHP_MethodVisibilityCheck.
 *	@package		Tests.file.php
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_PHP_MethodVisibilityCheck
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.01.2009
 *	@version		0.1
 */
class Test_File_PHP_MethodVisibilityCheckTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path			= dirname( __FILE__ )."/";
		$this->fileTemp		= $this->path."Test.class.tmp.php5";
		$this->fileClass	= $this->path."TestClass.php5";
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
		file_put_contents( $this->fileTemp, $class );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->fileTemp );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$fileName	= __FILE__;
		$checker	= new Test_File_PHP_MethodVisibilityCheckInstance( $fileName );
		
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
		$index	= new File_PHP_MethodVisibilityCheck( "not_existing" );
	}

	/**
	 *	Tests Method 'check'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCheck1()
	{
		$checker	= new File_PHP_MethodVisibilityCheck( $this->fileClass );
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
		$checker	= new File_PHP_MethodVisibilityCheck( $this->fileTemp );
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
		$checker	= new File_PHP_MethodVisibilityCheck( $this->fileClass );
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
		$checker	= new File_PHP_MethodVisibilityCheck( $this->fileTemp );
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
class Test_File_PHP_MethodVisibilityCheckInstance extends File_PHP_MethodVisibilityCheck
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}
}
?>