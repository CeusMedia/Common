<?php
/**
 *	TestUnit of FS_File_Permissions.
 *	@package		Tests.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			0.7.0
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of FS_File_Permissions.
 *	@package		Tests.File
 *	@extends		Test_Case
 *	@uses			FS_File_Permissions
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			0.7.0
 */
class Test_FS_File_PermissionsTest extends Test_Case
{
	protected $fileName;
	protected $pathName;
	protected $permissions;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
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
	public function tearDown()
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
	 *	Tests Method 'getAsInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAsInteger()
	{
		$assertion	= 511;
		$creation	= $this->permissions->getAsInteger();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getAsInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAsIntegerException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$permissions	= new FS_File_Permissions( 'not_existing' );
		$permissions->getAsInteger();
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
	 *	Tests Method 'getIntegerFromFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromFile()
	{
		$assertion	= 511;
		$creation	= FS_File_Permissions::getIntegerFromFile( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getIntegerFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromOctal()
	{
		$assertion	= 384;
		$creation	= FS_File_Permissions::getIntegerFromOctal( 0600 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 384;
		$creation	= FS_File_Permissions::getIntegerFromOctal( "0600" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 420;
		$creation	= FS_File_Permissions::getIntegerFromOctal( 0644 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 488;
		$creation	= FS_File_Permissions::getIntegerFromOctal( 0750 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 504;
		$creation	= FS_File_Permissions::getIntegerFromOctal( 0770 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 509;
		$creation	= FS_File_Permissions::getIntegerFromOctal( 0775 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 511;
		$creation	= FS_File_Permissions::getIntegerFromOctal( 0777 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromOctalException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getIntegerFromOctal( NULL );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromOctalException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getIntegerFromOctal( TRUE );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromOctalException3()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getIntegerFromOctal( M_PI );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromOctalException4()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getIntegerFromOctal( new stdClass() );
	}

	/**
	 *	Tests Method 'getIntegerFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromString()
	{
		$assertion	= 384;
		$creation	= FS_File_Permissions::getIntegerFromString( 'rw-------' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 420;
		$creation	= FS_File_Permissions::getIntegerFromString( 'rw-r--r--' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 488;
		$creation	= FS_File_Permissions::getIntegerFromString( 'rwxr-x---' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 504;
		$creation	= FS_File_Permissions::getIntegerFromString( 'rwxrwx---' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 509;
		$creation	= FS_File_Permissions::getIntegerFromString( 'rwxrwxr-x' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 511;
		$creation	= FS_File_Permissions::getIntegerFromString( 'rwxrwxrwx' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromStringException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getIntegerFromString( NULL );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromStringException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getIntegerFromString( TRUE );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromStringException3()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getIntegerFromString( M_PI );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromStringException4()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getIntegerFromString( 511 );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromStringException5()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getIntegerFromString( new stdClass() );
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
	 *	Tests Method 'getOctalFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromInteger()
	{
		$assertion	= '0600';
		$creation	= FS_File_Permissions::getOctalFromInteger( 384 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0600';
		$creation	= FS_File_Permissions::getOctalFromInteger( 0600 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0644';
		$creation	= FS_File_Permissions::getOctalFromInteger( 420 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0750';
		$creation	= FS_File_Permissions::getOctalFromInteger( 488 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0770';
		$creation	= FS_File_Permissions::getOctalFromInteger( 504 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0775';
		$creation	= FS_File_Permissions::getOctalFromInteger( 509 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0777';
		$creation	= FS_File_Permissions::getOctalFromInteger( 511 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromIntegerException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getOctalFromInteger( NULL );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromIntegerException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getOctalFromInteger( TRUE );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromIntegerException3()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getOctalFromInteger( M_PI );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromIntegerException4()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getOctalFromInteger( new stdClass() );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromIntegerException5()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getOctalFromInteger( 'rwx------' );
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
	 *	Tests Exception of Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromStringException11()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getOctalFromString( NULL );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromStringException12()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getOctalFromString( TRUE );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromStringException13()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getOctalFromString( M_PI );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromStringException14()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getOctalFromString( new stdClass() );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromStringException15()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getOctalFromString( 0600 );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromStringException21()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getOctalFromString( 'rwxrwxrwxrwx');
	}

	/**
	 *	Tests Exception of Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromStringException22()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getOctalFromString( 'rwxrwx');
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
	 *	Tests Method 'getStringFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromInteger()
	{
		$assertion	= 'rw-------';
		$creation	= FS_File_Permissions::getStringFromInteger( 384 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rw-------';
		$creation	= FS_File_Permissions::getStringFromInteger( 0600 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rw-r--r--';
		$creation	= FS_File_Permissions::getStringFromInteger( 420 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxr-x---';
		$creation	= FS_File_Permissions::getStringFromInteger( 488 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwx---';
		$creation	= FS_File_Permissions::getStringFromInteger( 504 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwxr-x';
		$creation	= FS_File_Permissions::getStringFromInteger( 509 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwxrwx';
		$creation	= FS_File_Permissions::getStringFromInteger( 511 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getStringFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromIntegerException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getStringFromInteger( NULL );
	}

	/**
	 *	Tests Exception of Method 'getStringFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromIntegerException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getStringFromInteger( TRUE );
	}

	/**
	 *	Tests Exception of Method 'getStringFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromIntegerException3()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getStringFromInteger( M_PI );
	}

	/**
	 *	Tests Exception of Method 'getStringFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromIntegerException4()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getStringFromInteger( new stdClass() );
	}

	/**
	 *	Tests Exception of Method 'getStringFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromIntegerException5()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getStringFromInteger( 'rwxrwxrwx' );
	}

	/**
	 *	Tests Method 'getStringFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromOctal()
	{
		$assertion	= 'rw-------';
		$creation	= FS_File_Permissions::getStringFromOctal( 0600 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rw-------';
		$creation	= FS_File_Permissions::getStringFromOctal( '0600' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rw-r--r--';
		$creation	= FS_File_Permissions::getStringFromOctal( 0644 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxr-x---';
		$creation	= FS_File_Permissions::getStringFromOctal( 0750 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwx---';
		$creation	= FS_File_Permissions::getStringFromOctal( 0770 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwxr-x';
		$creation	= FS_File_Permissions::getStringFromOctal( 0775 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwxrwx';
		$creation	= FS_File_Permissions::getStringFromOctal( 0777 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getStringFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromOctalException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getStringFromOctal( NULL );
	}

	/**
	 *	Tests Exception of Method 'getStringFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromOctalException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getStringFromOctal( TRUE );
	}

	/**
	 *	Tests Exception of Method 'getStringFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromOctalException3()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getStringFromOctal( M_PI );
	}

	/**
	 *	Tests Exception of Method 'getStringFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromOctalException4()
	{
		$this->expectException( 'InvalidArgumentException' );
		FS_File_Permissions::getStringFromOctal( new stdClass() );
	}

	/**
	 *	Tests Method 'setByOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetByOctal()
	{
		$assertion	= TRUE;
		$creation	= $this->permissions->setByOctal( 0770 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0770;
		$creation	= $this->permissions->getAsInteger();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setByOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetByOctalException1()
	{
		$this->expectException( 'RuntimeException' );
		unlink( $this->fileName );
		$this->permissions->setByOctal( 0777 );
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

		$assertion	= 0770;
		$creation	= $this->permissions->getAsInteger();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setByOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetByOctalException()
	{
		$this->expectException( 'RuntimeException' );
		unlink( $this->fileName );
		$this->permissions->setByOctal( 0777 );
	}
}
