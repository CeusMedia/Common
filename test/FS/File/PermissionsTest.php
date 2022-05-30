<?php
/**
 *	TestUnit of FS_File_Permissions.
 *	@package		Tests.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			0.7.0
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of FS_File_Permissions.
 *	@package		Tests.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			0.7.0
 */
class Test_FS_File_PermissionsTest extends BaseCase
{
	protected $fileName;
	protected $pathName;
	protected $permissions;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->pathName	= dirname( __FILE__ ).'/';
		$this->fileName	= $this->pathName.'test.file';
		file_put_contents( $this->fileName, 'this file is for testing permissions' );
		chmod( $this->fileName, 0777 );
		$this->permissions	= new FS_File_Permissions( $this->fileName );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		unset( $this->permissions );
		@unlink( $this->fileName );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$instance	= new FS_File_Permissions( $this->fileName );

		$assertion	= TRUE;
		$creation	= is_object( $instance );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__constructException()
	{
		$this->expectException( 'InvalidArgumentException' );
		new FS_File_Permissions( 'not_existing' );
	}

	/**
	 *	Tests Method 'getAsOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAsOctal()
	{
		$assertion	= '0777';
		$creation	= $this->permissions->getAsOctal();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getAsOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAsOctalException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$permissions	= new FS_File_Permissions( 'not_existing' );
		$permissions->getAsOctal();
	}

	/**
	 *	Tests Method 'getAsString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAsString()
	{
		$assertion	= 'rwxrwxrwx';
		$creation	= $this->permissions->getAsString();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getAsString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAsStringException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$permissions	= new FS_File_Permissions( 'not_existing' );
		$permissions->getAsString();
	}

	/**
	 *	Tests Method 'getOctalFromFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromFile()
	{
		$assertion	= '0777';
		$creation	= FS_File_Permissions::getOctalFromFile( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromString()
	{
		$assertion	= '0600';
		$creation	= FS_File_Permissions::getOctalFromString( 'rw-------' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0644';
		$creation	= FS_File_Permissions::getOctalFromString( 'rw-r--r--' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0750';
		$creation	= FS_File_Permissions::getOctalFromString( 'rwxr-x---' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0770';
		$creation	= FS_File_Permissions::getOctalFromString( 'rwxrwx---' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0775';
		$creation	= FS_File_Permissions::getOctalFromString( 'rwxrwxr-x' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0777';
		$creation	= FS_File_Permissions::getOctalFromString( 'rwxrwxrwx' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getStringFromFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromFile()
	{
		$assertion	= 'rwxrwxrwx';
		$creation	= FS_File_Permissions::getStringFromFile( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getStringFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromOctal()
	{
		$assertion	= 'rw-------';
		$creation	= FS_File_Permissions::getStringFromOctal( "0600" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rw-------';
		$creation	= FS_File_Permissions::getStringFromOctal( '0600' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rw-r--r--';
		$creation	= FS_File_Permissions::getStringFromOctal( '0644' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxr-x---';
		$creation	= FS_File_Permissions::getStringFromOctal( '0750' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwx---';
		$creation	= FS_File_Permissions::getStringFromOctal( '0770' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwxr-x';
		$creation	= FS_File_Permissions::getStringFromOctal( '0775' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwxrwx';
		$creation	= FS_File_Permissions::getStringFromOctal( '0777' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setByOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetByOctal()
	{
		$assertion	= TRUE;
		$creation	= $this->permissions->setByOctal( "0770" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0770";
		$creation	= $this->permissions->getAsOctal();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "rwxrwx---";
		$creation	= $this->permissions->getAsString();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setByString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetByString()
	{
		$assertion	= TRUE;
		$creation	= $this->permissions->setByString( 'rwxrwx---' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0770";
		$creation	= $this->permissions->getAsOctal();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwx---';
		$creation	= $this->permissions->getAsString();
		$this->assertEquals( $assertion, $creation );
	}
}
