<?php
/**
 *	TestUnit of List Reader.
 *	@package		Tests.file.list
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of List Reader.
 *	@package		Tests.file.list
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class Test_FS_File_List_ReaderTest extends Test_Case
{
	/**	@var	string		$fileName		File Name of Test File */
	private $fileName;

	/**
	 *	Set up for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->fileName		= dirname( __FILE__ )."/read.list";
		$this->reader	= new FS_File_List_Reader( $this->fileName );
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
		$this->expectException( 'DomainException' );
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
		$creation	= FS_File_List_Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$fileName	= dirname( $this->fileName )."/empty.list";
		file_put_contents( $fileName, "" );
		$assertion	= array();
		$creation	= FS_File_List_Reader::read( $fileName );
		unlink( $fileName );
	}

	/**
	 *	Tests Exception of Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadException()
	{
		$this->expectException( 'RuntimeException' );
		FS_File_List_Reader::read( "not_existing" );
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
