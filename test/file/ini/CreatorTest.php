<?php
/**
 *	TestUnit of File_INI_Creator.
 *	@package		Tests.file.ini
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.11.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of File_INI_Creator.
 *	@package		Tests.file.ini
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_INI_Creator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.11.2008
 *	@version		0.1
 */
class Test_File_INI_CreatorTest extends PHPUnit_Framework_TestCase
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
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_INI_Creator::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddProperty()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_INI_Creator::addProperty();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addPropertyToSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddPropertyToSection()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_INI_Creator::addPropertyToSection();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddSection()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_INI_Creator::addSection();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'write'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWrite()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_INI_Creator::write();
		$this->assertEquals( $assertion, $creation );
	}
}
?>