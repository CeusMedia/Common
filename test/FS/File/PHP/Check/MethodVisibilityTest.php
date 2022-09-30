<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_PHP_Check_MethodVisibility.
 *	@package		Tests.FS.File.PHP.Check
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\PHP\Check;

use CeusMedia\Common\FS\File\PHP\Check\MethodVisibility;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\MockAntiProtection;

/**
 *	TestUnit of FS_File_PHP_Check_MethodVisibility.
 *	@package		Tests.FS.File.PHP.Check
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class MethodVisibilityTest extends BaseCase
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
		$checker	= MockAntiProtection::getInstance( MethodVisibility::class, $fileName );

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
		$index	= new MethodVisibility( "not_existing" );
	}

	/**
	 *	Tests Method 'check'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCheck1()
	{
		$checker	= new MethodVisibility( $this->fileTemp1 );
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
		$checker	= new MethodVisibility( $this->fileTemp2 );
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
		$checker	= new MethodVisibility( $this->fileTemp1 );
		$checker->check();
		$assertion	= [];
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
		$checker	= new MethodVisibility( $this->fileTemp2 );
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
