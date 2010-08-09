<?php
/**
 *	TestUnit of File_CSV_Reader.
 *	@package		Tests.File.CSV
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_CSV_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.08.2010
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Test/initLoaders.php5' );
import( 'File.CSV.Reader' );
/**
 *	TestUnit of File_CSV_Reader.
 *	@package		Tests.File.CSV
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_CSV_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.08.2010
 *	@version		0.1
 */
class Test_File_CSV_ReaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->pathName	= dirname( __FILE__ ).'/';
		$this->fileName	= $this->pathName.'read.csv';
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->reader	= new File_CSV_Reader( $this->fileName, TRUE );
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
		$mock		= Test_MockAntiProtection::getInstance( 'File_CSV_Reader', $this->fileName, TRUE, '|', '#' );

		$assertion	= TRUE;
		$creation	= is_object( $mock );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $mock->getProtectedVar( 'withHeaders' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '|';
		$creation	= $mock->getProtectedVar( 'delimiter' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '#';
		$creation	= $mock->getProtectedVar( 'enclosure' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $this->fileName;
		$creation	= $mock->getProtectedVar( 'fileName' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getColumnHeaders'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetColumnHeaders()
	{
		$reader		= new File_CSV_Reader( $this->fileName, TRUE );
		$assertion	= array( 'id', 'col1', 'col2' );
		$creation	= $this->reader->getColumnHeaders();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getColumnHeaders'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetColumnHeadersException1()
	{
		$reader	= new File_CSV_Reader( $this->fileName, FALSE );
		$this->setExpectedException( 'RuntimeException' );
		$reader->getColumnHeaders();
	}

	/**
	 *	Tests Exception of Method 'getColumnHeaders'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetColumnHeadersException2()
	{
		$reader	= new File_CSV_Reader( $this->pathName.'empty.csv', TRUE );
		$this->setExpectedException( 'RuntimeException' );
		$reader->getColumnHeaders();
	}

	/**
	 *	Tests Method 'getRowCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRowCount()
	{
		$assertion	= 2;
		$creation	= $this->reader->getRowCount();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getDelimiter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDelimiter()
	{
		$assertion	= ';';
		$creation	= $this->reader->getDelimiter();
		$this->assertEquals( $assertion, $creation );

		$reader		= new File_CSV_Reader( $this->fileName, TRUE, '_' );
		$assertion	= '_';
		$creation	= $reader->getDelimiter();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEnclosure'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEnclosure()
	{
		$assertion	= '"';
		$creation	= $this->reader->getEnclosure();
		$this->assertEquals( $assertion, $creation );

		$reader		= new File_CSV_Reader( $this->fileName, TRUE, ';', '_' );
		$assertion	= '_';
		$creation	= $reader->getEnclosure();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setDelimiter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetDelimiter()
	{
		$assertion	= NULL;
		$creation	= $this->reader->setDelimiter( '#' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '#';
		$creation	= $this->reader->getDelimiter();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setEnclosure'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetEnclosure()
	{
		$assertion	= NULL;
		$creation	= $this->reader->setEnclosure( '#' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '#';
		$creation	= $this->reader->getEnclosure();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray()
	{
		$assertion	= array(
			array(
				'1', 'test1', 'string without semicolon'
			), array(
				'2', 'test2', 'string with ; semicolon'
			)
		);
		$creation	= $this->reader->toArray();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toAssocArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToAssocArray()
	{
		$assertion	= array(
			array(
				'id'	=> '1',
				'col1'	=> 'test1',
				'col2'	=> 'string without semicolon'
			),
			array(
				'id'	=> '2',
				'col1'	=> 'test2',
				'col2'	=> 'string with ; semicolon'
			)
		);
		$creation	= $this->reader->toAssocArray();
		$this->assertEquals( $assertion, $creation );
	}
}
?>