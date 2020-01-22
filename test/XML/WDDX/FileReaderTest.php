<?php
/**
 *	TestUnit of XML_WDDX_FileReader.
 *	@package		Tests.{classPackage}
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			03.05.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of XML_WDDX_FileReader.
 *	@package		Tests.{classPackage}
 *	@extends		Test_Case
 *	@uses			XML_WDDX_FileReader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			03.05.2008
 *	@version		0.1
 */
class Test_XML_WDDX_FileReaderTest extends Test_Case
{
	public function setUp(){
		if( !extension_loaded( 'wddx' ) )
			$this->markTestSkipped( 'Missing WDDX support' );

		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."reader.wddx";
		$this->reader	= new XML_WDDX_FileReader( $this->fileName );
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
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$reader	= new XML_WDDX_FileReader( $this->fileName );

		$assertion	= $this->data;
		$creation	= $reader->read();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRead()
	{
		$assertion	= $this->data;
		$creation	= $this->reader->read();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'load'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoad()
	{
		$assertion	= $this->data;
		$creation	= XML_WDDX_FileReader::load( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
