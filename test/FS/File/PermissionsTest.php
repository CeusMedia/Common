<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_Permissions.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File;

use CeusMedia\Common\FS\File\Permissions;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_Permissions.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class PermissionsTest extends BaseCase
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
		$this->permissions	= new Permissions( $this->fileName );
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
		$instance	= new Permissions( $this->fileName );

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
		new Permissions( 'not_existing' );
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
		$permissions	= new Permissions( 'not_existing' );
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
		$permissions	= new Permissions( 'not_existing' );
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
		$creation	= Permissions::getOctalFromFile( $this->fileName );
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
		$creation	= Permissions::getOctalFromString( 'rw-------' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0644';
		$creation	= Permissions::getOctalFromString( 'rw-r--r--' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0750';
		$creation	= Permissions::getOctalFromString( 'rwxr-x---' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0770';
		$creation	= Permissions::getOctalFromString( 'rwxrwx---' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0775';
		$creation	= Permissions::getOctalFromString( 'rwxrwxr-x' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0777';
		$creation	= Permissions::getOctalFromString( 'rwxrwxrwx' );
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
		$creation	= Permissions::getStringFromFile( $this->fileName );
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
		$creation	= Permissions::getStringFromOctal( "0600" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rw-------';
		$creation	= Permissions::getStringFromOctal( '0600' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rw-r--r--';
		$creation	= Permissions::getStringFromOctal( '0644' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxr-x---';
		$creation	= Permissions::getStringFromOctal( '0750' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwx---';
		$creation	= Permissions::getStringFromOctal( '0770' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwxr-x';
		$creation	= Permissions::getStringFromOctal( '0775' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwxrwx';
		$creation	= Permissions::getStringFromOctal( '0777' );
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
