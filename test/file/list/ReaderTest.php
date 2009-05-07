<?php
/**
 *	TestUnit of List Reader.
 *	@package		Tests.file.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_List_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once '../autoload.php5'; 
import( 'de.ceus-media.file.list.Reader' );
/**
 *	TestUnit of List Reader.
 *	@package		Tests.file.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_List_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class File_List_ReaderTest extends PHPUnit_Framework_TestCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName		= "file/list/read.list";
	
	/**
	 *	Set up for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->reader	= new File_List_Reader( $this->fileName );
	}

	/**
	 *	Tests Method 'getList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIndex()
	{
		$assertion	= 0;
		$creation	= $this->reader->getIndex( "line1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->reader->getIndex( "line2" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIndexException()
	{
		$this->setExpectedException( 'DomainException' );
		$this->reader->getIndex( "not_existing" );
	}

	/**
	 *	Tests Method 'getList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetList()
	{
		$assertion	= array(
			"line1",
			"line2",
		);
		$creation	= $this->reader->getList();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSize'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSize()
	{
		$assertion	= 2;
		$creation	= $this->reader->getSize();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasItem'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasItem()
	{
		$assertion	= TRUE;
		$creation	= $this->reader->hasItem( "line1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->reader->hasItem( "line3" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRead()
	{
		$assertion	= array(
			"line1",
			"line2",
		);
		$creation	= File_List_Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	
		$fileName	= dirname( $this->fileName )."/empty.list";
		file_put_contents( $fileName, "" );
		$assertion	= array();
		$creation	= File_List_Reader::read( $fileName );
		unlink( $fileName );
	}

	/**
	 *	Tests Exception of Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadException()
	{
		$this->setExpectedException( 'Exception' );
		File_List_Reader::read( "not_existing" );
	}

	/**
	 *	Tests Method '__toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString()
	{
		$assertion	= "{line1, line2}";;
		$creation	= "".$this->reader;
		$this->assertEquals( $assertion, $creation );
	}
}
?>