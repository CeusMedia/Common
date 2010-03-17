<?php
/**
 *	TestUnit of XML_WDDX_FileWriter.
 *	@package		Tests.{classPackage}
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.05.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of XML_WDDX_FileWriter.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_WDDX_FileWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.05.2008
 *	@version		0.1
 */
class Test_XML_WDDX_FileWriterTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."writer.wddx";
		$this->writer	= new XML_WDDX_FileWriter( $this->fileName, "test" );
		$this->data		= array(
			'data'	=> array(
				'test_string'	=> "data to be passed by WDDX",
				'test_bool'		=> TRUE,
				'test_int'		=> 12,
				'test_double'	=> 3.1415926,
			)
		);
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		@unlink( $this->fileName );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->fileName );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$writer	= new XML_WDDX_FileWriter( $this->fileName, "constructorTest" );
		$writer->write();
		
		$assertion	= TRUE;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'add'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAdd()
	{
		$assertion	= TRUE;
		$creation	= $this->writer->add( 'key1', 'value1' );
		$this->assertEquals( $assertion, $creation );
	
		$assertion	= TRUE;
		$creation	= is_int( $this->writer->write() );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= preg_match( "@<string>value1</string>@", file_get_contents( $this->fileName ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'write'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWrite()
	{
		foreach( $this->data as $key => $value )
			$this->writer->add( $key, $value );
		$result		= $this->writer->write();

		$assertion	= TRUE;
		$creation	= is_int( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $result > 0;
		$this->assertEquals( $assertion, $creation );

		$assertion	= file_get_contents( $this->path."reader.wddx" );
		$creation	= file_get_contents( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'save'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSave()
	{
		$result		= XML_WDDX_FileWriter::save( $this->fileName, $this->data, 'staticTest' );

		$assertion	= TRUE;
		$creation	= is_int( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $result > 0;
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= str_replace( ">test<", ">staticTest<", file_get_contents( $this->path."reader.wddx" ) );
		$creation	= file_get_contents( $this->fileName );
		$this->assertEquals( $assertion, $creation );

	
		$result		= XML_WDDX_FileWriter::save( $this->fileName, $this->data );

		$assertion	= TRUE;
		$creation	= is_int( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $result > 0;
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= wddx_serialize_value( $this->data );
		$creation	= file_get_contents( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
?>