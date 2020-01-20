<?php
/**
 *	TestUnit of FS_File_INI_Creator.
 *	@package		Tests.file.ini
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			03.11.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of FS_File_INI_Creator.
 *	@package		Tests.file.ini
 *	@extends		Test_Case
 *	@uses			FS_File_INI_Creator
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			03.11.2008
 *	@version		0.1
 */
class Test_FS_File_INI_CreatorTest extends Test_Case
{
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
		$creation	= FS_File_INI_Creator::__construct();
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
		$creation	= FS_File_INI_Creator::addProperty();
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
		$creation	= FS_File_INI_Creator::addPropertyToSection();
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
		$creation	= FS_File_INI_Creator::addSection();
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
		$creation	= FS_File_INI_Creator::write();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
