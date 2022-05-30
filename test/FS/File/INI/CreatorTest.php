<?php
/**
 *	TestUnit of FS_File_INI_Creator.
 *	@package		Tests.file.ini
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			03.11.2008
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of FS_File_INI_Creator.
 *	@package		Tests.file.ini
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			03.11.2008
 */
class Test_FS_File_INI_CreatorTest extends BaseCase
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
