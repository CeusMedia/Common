<?php
/**
 *	TestUnit of File_Permissions.
 *	@package		Tests.File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			0.7.0
 *	@version		$Id$
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Test/initLoaders.php5' );
/**
 *	TestUnit of File_Permissions.
 *	@package		Tests.File
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Permissions
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			0.7.0
 *	@version		$Id$
 */
class Test_File_PermissionsTest extends PHPUnit_Framework_TestCase
{
	protected $fileName;
	protected $pathName;
	protected $permissions;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->pathName	= dirname( __FILE__ ).'/';
		$this->fileName	= $this->pathName.'test.file';
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		file_put_contents( $this->fileName, 'this file is for testing permissions' );
		chmod( $this->fileName, 0777 );
		$this->permissions	= new File_Permissions( $this->fileName );
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
		$instance	= new File_Permissions( $this->fileName );

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
		$this->setExpectedException( 'InvalidArgumentException' );
		new File_Permissions( 'not_existing' );
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
		$this->setExpectedException( 'InvalidArgumentException' );
		$permissions	= new File_Permissions( 'not_existing' );
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
		$this->setExpectedException( 'InvalidArgumentException' );
		$permissions	= new File_Permissions( 'not_existing' );
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
		$this->setExpectedException( 'InvalidArgumentException' );
		$permissions	= new File_Permissions( 'not_existing' );
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
		$creation	= File_Permissions::getIntegerFromFile( $this->fileName );
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
		$creation	= File_Permissions::getIntegerFromOctal( 0600 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 384;
		$creation	= File_Permissions::getIntegerFromOctal( "0600" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 420;
		$creation	= File_Permissions::getIntegerFromOctal( 0644 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 488;
		$creation	= File_Permissions::getIntegerFromOctal( 0750 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 504;
		$creation	= File_Permissions::getIntegerFromOctal( 0770 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 509;
		$creation	= File_Permissions::getIntegerFromOctal( 0775 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 511;
		$creation	= File_Permissions::getIntegerFromOctal( 0777 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromOctalException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getIntegerFromOctal( NULL );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromOctalException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getIntegerFromOctal( TRUE );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromOctalException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getIntegerFromOctal( M_PI );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromOctalException4()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getIntegerFromOctal( new stdClass() );
	}

	/**
	 *	Tests Method 'getIntegerFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromString()
	{
		$assertion	= 384;
		$creation	= File_Permissions::getIntegerFromString( 'rw-------' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 420;
		$creation	= File_Permissions::getIntegerFromString( 'rw-r--r--' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 488;
		$creation	= File_Permissions::getIntegerFromString( 'rwxr-x---' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 504;
		$creation	= File_Permissions::getIntegerFromString( 'rwxrwx---' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 509;
		$creation	= File_Permissions::getIntegerFromString( 'rwxrwxr-x' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 511;
		$creation	= File_Permissions::getIntegerFromString( 'rwxrwxrwx' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromStringException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getIntegerFromString( NULL );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromStringException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getIntegerFromString( TRUE );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromStringException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getIntegerFromString( M_PI );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromStringException4()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getIntegerFromString( 511 );
	}

	/**
	 *	Tests Exception of Method 'getIntegerFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIntegerFromStringException5()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getIntegerFromString( new stdClass() );
	}

	/**
	 *	Tests Method 'getOctalFromFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromFile()
	{
		$assertion	= '0777';
		$creation	= File_Permissions::getOctalFromFile( $this->fileName );
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
		$creation	= File_Permissions::getOctalFromInteger( 384 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0600';
		$creation	= File_Permissions::getOctalFromInteger( 0600 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0644';
		$creation	= File_Permissions::getOctalFromInteger( 420 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0750';
		$creation	= File_Permissions::getOctalFromInteger( 488 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0770';
		$creation	= File_Permissions::getOctalFromInteger( 504 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0775';
		$creation	= File_Permissions::getOctalFromInteger( 509 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0777';
		$creation	= File_Permissions::getOctalFromInteger( 511 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromIntegerException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getOctalFromInteger( NULL );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromIntegerException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getOctalFromInteger( TRUE );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromIntegerException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getOctalFromInteger( M_PI );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromIntegerException4()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getOctalFromInteger( new stdClass() );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromIntegerException5()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getOctalFromInteger( 'rwx------' );
	}

	/**
	 *	Tests Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromString()
	{
		$assertion	= '0600';
		$creation	= File_Permissions::getOctalFromString( 'rw-------' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0644';
		$creation	= File_Permissions::getOctalFromString( 'rw-r--r--' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0750';
		$creation	= File_Permissions::getOctalFromString( 'rwxr-x---' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0770';
		$creation	= File_Permissions::getOctalFromString( 'rwxrwx---' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0775';
		$creation	= File_Permissions::getOctalFromString( 'rwxrwxr-x' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0777';
		$creation	= File_Permissions::getOctalFromString( 'rwxrwxrwx' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromStringException11()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getOctalFromString( NULL );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromStringException12()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getOctalFromString( TRUE );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromStringException13()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getOctalFromString( M_PI );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromStringException14()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getOctalFromString( new stdClass() );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromStringException15()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getOctalFromString( 0600 );
	}

	/**
	 *	Tests Exception of Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromStringException21()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getOctalFromString( 'rwxrwxrwxrwx');
	}

	/**
	 *	Tests Exception of Method 'getOctalFromString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOctalFromStringException22()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getOctalFromString( 'rwxrwx');
	}

	/**
	 *	Tests Method 'getStringFromFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromFile()
	{
		$assertion	= 'rwxrwxrwx';
		$creation	= File_Permissions::getStringFromFile( $this->fileName );
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
		$creation	= File_Permissions::getStringFromInteger( 384 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rw-------';
		$creation	= File_Permissions::getStringFromInteger( 0600 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rw-r--r--';
		$creation	= File_Permissions::getStringFromInteger( 420 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxr-x---';
		$creation	= File_Permissions::getStringFromInteger( 488 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwx---';
		$creation	= File_Permissions::getStringFromInteger( 504 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwxr-x';
		$creation	= File_Permissions::getStringFromInteger( 509 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwxrwx';
		$creation	= File_Permissions::getStringFromInteger( 511 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getStringFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromIntegerException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getStringFromInteger( NULL );
	}

	/**
	 *	Tests Exception of Method 'getStringFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromIntegerException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getStringFromInteger( TRUE );
	}

	/**
	 *	Tests Exception of Method 'getStringFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromIntegerException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getStringFromInteger( M_PI );
	}

	/**
	 *	Tests Exception of Method 'getStringFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromIntegerException4()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getStringFromInteger( new stdClass() );
	}

	/**
	 *	Tests Exception of Method 'getStringFromInteger'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromIntegerException5()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getStringFromInteger( 'rwxrwxrwx' );
	}

	/**
	 *	Tests Method 'getStringFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromOctal()
	{
		$assertion	= 'rw-------';
		$creation	= File_Permissions::getStringFromOctal( 0600 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rw-------';
		$creation	= File_Permissions::getStringFromOctal( '0600' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rw-r--r--';
		$creation	= File_Permissions::getStringFromOctal( 0644 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxr-x---';
		$creation	= File_Permissions::getStringFromOctal( 0750 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwx---';
		$creation	= File_Permissions::getStringFromOctal( 0770 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwxr-x';
		$creation	= File_Permissions::getStringFromOctal( 0775 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'rwxrwxrwx';
		$creation	= File_Permissions::getStringFromOctal( 0777 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getStringFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromOctalException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getStringFromOctal( NULL );
	}

	/**
	 *	Tests Exception of Method 'getStringFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromOctalException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getStringFromOctal( TRUE );
	}

	/**
	 *	Tests Exception of Method 'getStringFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromOctalException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getStringFromOctal( M_PI );
	}

	/**
	 *	Tests Exception of Method 'getStringFromOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStringFromOctalException4()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Permissions::getStringFromOctal( new stdClass() );
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
		$this->setExpectedException( 'RuntimeException' );
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
		$this->setExpectedException( 'RuntimeException' );
		unlink( $this->fileName );
		$this->permissions->setByOctal( 0777 );
	}
}
?>