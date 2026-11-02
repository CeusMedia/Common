<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_ICal_Builder.
 *	@package		Tests.FS.File,ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\ICal;

use CeusMedia\Common\FS\File\ICal\Builder;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_ICal_Builder.
 *	@package		Tests.FS.File.ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class BuilderTest extends BaseCase
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
		$creation	= Builder::__construct();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Builder::build();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests that folding of long lines keeps the whole content.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildLineFoldingKeepsContent(): void
	{
		$content	= str_repeat( 'abcdefghij', 20 );
		new Builder( "\r\n" );
		$method		= new \ReflectionMethod( Builder::class, 'buildLine' );
		$line		= $method->invoke( NULL, 'description', [], $content );
		self::assertSame( 'DESCRIPTION:'.$content, str_replace( "\r\n ", '', $line ) );
	}
}
