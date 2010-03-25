<?php
/**
 *	TestUnit of File_Configuration_Reader.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Configuration_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.file.configuration.Reader' );
/**
 *	TestUnit of File_Configuration_Reader.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Configuration_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.05.2008
 *	@version		0.1
 */
class Tests_File_Configuration_ReaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
		$this->data	= array(
			'section1.string'	=> "name@domain.tld",
			'section1.integer'	=> 1,
			'section1.double'	=> 3.14,
			'section1.bool'		=> TRUE,
			'section2.string'	=> "http://sub.domain.tld/application/",
			'section2.integer'	=> 12,
			'section2.double'	=> -5.12,
			'section2.bool'		=> FALSE,
		);
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		 @unlink( $this->path."test.ini.cache" );
		 @unlink( $this->path."test.json.cache" );
		 @unlink( $this->path."test.xml.cache" );
		 @unlink( $this->path."test.yaml.cache" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructIni()
	{
		$reader		= new File_Configuration_Reader( $this->path."test.ini" );
		$assertion	= $this->data;
		$creation	= $reader->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructIniCache()
	{
		$reader		= new File_Configuration_Reader( $this->path."test.ini", $this->path );
		$assertion	= $this->data;
		$creation	= $reader->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructIniQuick()
	{
		File_Configuration_Reader::$iniQuickLoad	= TRUE;
		$reader		= new File_Configuration_Reader( $this->path."test.ini" );
		$stringData	= array();
		foreach( $this->data as $key => $value )
			$stringData[$key]	= (string) $value;
		$assertion	= $stringData;
		$creation	= $reader->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructJson()
	{
		$reader		= new File_Configuration_Reader( $this->path."test.json" );
		$assertion	= $this->data;
		$creation	= $reader->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructJsonCache()
	{
		$reader		= new File_Configuration_Reader( $this->path."test.json", $this->path );
		$assertion	= $this->data;
		$creation	= $reader->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructYaml()
	{
		$reader		= new File_Configuration_Reader( $this->path."test.yaml" );
		$assertion	= $this->data;
		ksort( $assertion );
		$creation	= $reader->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructYamlCache()
	{
		$reader		= new File_Configuration_Reader( $this->path."test.yaml", $this->path );
		$assertion	= $this->data;
		ksort( $assertion );
		$creation	= $reader->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructXml()
	{
		$reader		= new File_Configuration_Reader( $this->path."test.xml" );
		$assertion	= $this->data;
		$creation	= $reader->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructXmlCache()
	{
		$reader		= new File_Configuration_Reader( $this->path."test.xml", $this->path );
		$assertion	= $this->data;
		$creation	= $reader->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructExceptionNotExisting()
	{
		$this->setExpectedException( 'RuntimeException' );
		new File_Configuration_Reader( $this->path."name.not_supported" );
	}

	/**
	 *	Tests Exception Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructExceptionNotSupported()
	{
		$fileName	= $this->path."filename.xyz";
		file_put_contents( $fileName, "" );
		$this->setExpectedException( 'InvalidArgumentException' );
		new File_Configuration_Reader( $fileName );
		unlink( $fileName );
	}
}
?>