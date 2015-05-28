<?php
/**
 *	TestUnit of Section INI Reader.
 *	@package		Tests.file.ini
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
require_once 'Test/initLoaders.php';
/**
 *	TestUnit of Section INI Reader.
 *	@package		Tests.file.ini
 *	@extends		Test_Case
 *	@uses			FS_File_INI_SectionReader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
class Test_FS_File_INI_SectionReaderTest extends Test_Case
{
	/**	@var	string		$fileName		File Name of Test File */
	private $fileName;

	public function __construct()
	{
		$this->fileName	= dirname( __FILE__ )."/section.reader.ini";
		$this->reader	= new FS_File_INI_SectionReader( $this->fileName );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testContruct()
	{
		$assertion	= array(
			"section1"	=> array(
				"key1"	=> "value1",
				"key2"	=> "value2",
			),
			"section2"	=> array(
				"key3"	=> "value3",
				"key4"	=> "value4",
			),
		);
		$reader		= new FS_File_INI_SectionReader( $this->fileName );
		$creation	= $reader->toArray();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getProperties'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetProperties()
	{
		$assertion	= array(
			"section1"	=> array(
				"key1"	=> "value1",
				"key2"	=> "value2",
			),
			"section2"	=> array(
				"key3"	=> "value3",
				"key4"	=> "value4",
			),
		);
		$creation	= $this->reader->getProperties();
		$this->assertEquals( $assertion, $creation );
	
		$assertion	= array(
			"key3"	=> "value3",
			"key4"	=> "value4",
		);
		$creation	= $this->reader->getProperties( "section2" );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Exception of Method 'getProperties'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPropertiesException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );	
		$creation	= $this->reader->getProperties( 'section3' );
	}

	/**
	 *	Tests Method 'getProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetProperty()
	{
		$assertion	= "value2";
		$creation	= $this->reader->getProperty( 'section1', 'key2' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPropertyException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );	
		$creation	= $this->reader->getProperty( 'section3', 'not_relevant' );
	}

	/**
	 *	Tests Exception of Method 'getProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPropertyException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );	
		$creation	= $this->reader->getProperty( 'section1', 'invalid_key' );
	}

	/**
	 *	Tests Method 'getSections'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSections()
	{
		$assertion	= array( 'section1', 'section2' );
		$creation	= $this->reader->getSections();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasProperty()
	{
		$assertion	= TRUE;	
		$creation	= $this->reader->hasProperty( 'section1', 'key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;	
		$creation	= $this->reader->hasProperty( 'section2', 'key1' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasSection()
	{
		$assertion	= TRUE;
		$creation	= $this->reader->hasSection( 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->reader->hasSection( 'section3' );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
