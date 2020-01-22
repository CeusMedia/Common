<?php
/**
 *	TestUnit of XML_WDDX_FileWriter.
 *	@package		Tests.{classPackage}
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			03.05.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of XML_WDDX_FileWriter.
 *	@package		Tests.{classPackage}
 *	@extends		Test_Case
 *	@uses			XML_WDDX_FileWriter
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			03.05.2008
 *	@version		0.1
 */
class Test_XML_WDDX_FileWriterTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."writer.wddx";
		$this->data		= array(
			'data'	=> array(
				'test_string'	=> "data to be passed by WDDX",
				'test_bool'		=> TRUE,
				'test_int'		=> 12,
				'test_double'	=> 3.1415926,
			)
		);

		if( !extension_loaded( 'wddx' ) )
			$this->markTestSkipped( 'Missing WDDX support' );
		@unlink( $this->fileName );
		$this->writer	= new XML_WDDX_FileWriter( $this->fileName, "test" );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		if( !function_exists( 'wddx_packet_start' ) )
			return;
		@unlink( $this->fileName );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		if( !function_exists( 'wddx_packet_start' ) )
			return;
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
		if( !function_exists( 'wddx_packet_start' ) )
			return;
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
		if( !function_exists( 'wddx_packet_start' ) )
			return;
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
		if( !function_exists( 'wddx_packet_start' ) )
			return;
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
