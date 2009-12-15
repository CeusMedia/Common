<?php
/**
 *	TestUnit of XML_WDDX_FileReader.
 *	@package		Tests.{classPackage}
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.05.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of XML_WDDX_FileReader.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_WDDX_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.05.2008
 *	@version		0.1
 */
class Test_XML_WDDX_FileReaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
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
?>