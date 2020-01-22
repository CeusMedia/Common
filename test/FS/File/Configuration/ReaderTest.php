<?php
/**
 *	TestUnit of FS_File_Configuration_Reader.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			08.05.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of FS_File_Configuration_Reader.
 *	@package		Tests.
 *	@extends		Test_Case
 *	@uses			FS_File_Configuration_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			08.05.2008
 *	@version		0.1
 */
class Test_FS_File_Configuration_ReaderTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
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
		$reader		= new FS_File_Configuration_Reader( $this->path."test.ini" );
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
		$reader		= new FS_File_Configuration_Reader( $this->path."test.ini", $this->path );
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
		FS_File_Configuration_Reader::$iniQuickLoad	= TRUE;
		$reader		= new FS_File_Configuration_Reader( $this->path."test.ini" );
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
		$reader		= new FS_File_Configuration_Reader( $this->path."test.json" );
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
		$reader		= new FS_File_Configuration_Reader( $this->path."test.json", $this->path );
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
		$reader		= new FS_File_Configuration_Reader( $this->path."test.yaml" );
		$assertion	= $this->data;
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
		$reader		= new FS_File_Configuration_Reader( $this->path."test.yaml", $this->path );
		$assertion	= $this->data;
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
		$reader		= new FS_File_Configuration_Reader( $this->path."test.xml" );
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
		$reader		= new FS_File_Configuration_Reader( $this->path."test.xml", $this->path );
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
		$this->expectException( 'RuntimeException' );
		new FS_File_Configuration_Reader( $this->path."name.not_supported" );
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
		$this->expectException( 'InvalidArgumentException' );
		new FS_File_Configuration_Reader( $fileName );
		unlink( $fileName );
	}
}
