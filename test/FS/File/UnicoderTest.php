<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_Unicoder.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File;

use CeusMedia\Common\FS\File\Unicoder;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_Unicoder.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class UnicoderTest extends BaseCase
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
		$creation	= Unicoder::__construct();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsUnicode()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Unicoder::isUnicode();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertToUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToUnicode()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Unicoder::convertToUnicode();
		self::assertEquals( $assertion, $creation );
	}
}
