<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_Configuration_Reader.
 *	@package		Tests.FS.File.Configuration
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\FS\File\Configuration;

use CeusMedia\Common\FS\File\Configuration\Reader;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of FS_File_Configuration_Reader.
 *	@package		Tests.FS.File.Configuration
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ReaderTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
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
	public function tearDown(): void
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
		$reader		= new Reader( $this->path."test.ini" );
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
		$reader		= new Reader( $this->path."test.ini", $this->path );
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
		Reader::$iniQuickLoad	= TRUE;
		$reader		= new Reader( $this->path."test.ini" );
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
		$reader		= new Reader( $this->path."test.json" );
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
		$reader		= new Reader( $this->path."test.json", $this->path );
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
		$reader		= new Reader( $this->path."test.yaml" );
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
		$reader		= new Reader( $this->path."test.yaml", $this->path );
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
		$reader		= new Reader( $this->path."test.xml" );
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
		$reader		= new Reader( $this->path."test.xml", $this->path );
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
		new Reader( $this->path."name.not_supported" );
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
		new Reader( $fileName );
		unlink( $fileName );
	}
}
