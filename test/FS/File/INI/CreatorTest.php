<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_INI_Creator.
 *	@package		Tests.FS.File.INI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\INI;

use CeusMedia\Common\FS\File\INI\Creator;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_INI_Creator.
 *	@package		Tests.FS.File.INI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class CreatorTest extends BaseCase
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
		$creation	= Creator::__construct();
		self::assertEquals( $assertion, $creation );
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
		$creation	= Creator::addProperty();
		self::assertEquals( $assertion, $creation );
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
		$creation	= Creator::addPropertyToSection();
		self::assertEquals( $assertion, $creation );
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
		$creation	= Creator::addSection();
		self::assertEquals( $assertion, $creation );
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
		$creation	= Creator::write();
		self::assertEquals( $assertion, $creation );
	}
}
