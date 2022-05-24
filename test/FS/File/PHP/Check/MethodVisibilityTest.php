<?php
/**
 *	TestUnit of FS_File_PHP_Check_MethodVisibility.
 *	@package		Tests.file.php
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			04.01.2009
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of FS_File_PHP_Check_MethodVisibility.
 *	@package		Tests.file.php
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			04.01.2009
 */
class Test_FS_File_PHP_Check_MethodVisibilityTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path			= dirname( __FILE__ )."/";
		$this->fileTemp1	= __FILE__;
		$this->fileTemp2	= $this->path."TestClass_Bad.php";
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
	public function testConstruct()
	{
		$fileName	= __FILE__;
		$checker	= Test_MockAntiProtection::getInstance( 'FS_File_PHP_Check_MethodVisibility', $fileName );

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
		$this->expectException( 'RuntimeException' );
		$index	= new FS_File_PHP_Check_MethodVisibility( "not_existing" );
	}

	/**
	 *	Tests Method 'check'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCheck1()
	{
		$checker	= new FS_File_PHP_Check_MethodVisibility( $this->fileTemp1 );
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
		$checker	= new FS_File_PHP_Check_MethodVisibility( $this->fileTemp2 );
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
		$checker	= new FS_File_PHP_Check_MethodVisibility( $this->fileTemp1 );
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
		$checker	= new FS_File_PHP_Check_MethodVisibility( $this->fileTemp2 );
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
