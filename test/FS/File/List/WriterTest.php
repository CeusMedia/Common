<?php
/**
 *	TestUnit of List Writer.
 *	@package		Tests.file.list
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of List Writer.
 *	@package		Tests.file.list
 *	@extends		Test_Case
 *	@uses			FS_File_List_Writer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
class Test_FS_File_List_WriterTest extends Test_Case
{
	/**	@var	string		$fileName		File Name of Test File */
	private $fileName;

	/**
	 *	Set up for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->fileName	= dirname( __FILE__ )."/writer.list";
		$this->writer	= new FS_File_List_Writer( $this->fileName );
	}

	/**
	 *	Clean up.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->fileName );
	}

	/**
	 *	Tests Method 'add'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAdd()
	{
		$assertion	= TRUE;
		$creation	= $this->writer->add( 'line1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line1" );
		$creation	= FS_File_List_Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->writer->add( 'line2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line1", "line2" );
		$creation	= FS_File_List_Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'add'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddException()
	{
		$this->writer->add( 'line1' );
		$this->expectException( 'DomainException' );
		$this->writer->add( 'line1' );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		$this->writer->add( 'line1' );
		$this->writer->add( 'line2' );

		$assertion	= TRUE;
		$creation	= $this->writer->remove( 'line1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line2" );
		$creation	= FS_File_List_Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveException()
	{
		$this->writer->add( 'line1' );
		$this->writer->remove( 'line1' );
		$this->expectException( 'DomainException' );
		$this->writer->remove( 'line1' );
	}

	/**
	 *	Tests Method 'removeIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveIndex()
	{
		$this->writer->add( 'line1' );
		$this->writer->add( 'line2' );

		$assertion	= TRUE;
		$creation	= $this->writer->removeIndex( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line1" );
		$creation	= FS_File_List_Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->writer->removeIndex( 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= FS_File_List_Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'removeIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveIndexException()
	{
		$this->expectException( 'DomainException' );
		$this->writer->removeIndex( 10 );
	}

	/**
	 *	Tests Method 'setSave'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSave()
	{
		$lines	= array(
			'line1',
			'line2',
			'line3',
		);
		$assertion	= TRUE;
		$creation	= FS_File_List_Writer::save( $this->fileName, $lines );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $lines;
		$creation	= FS_File_List_Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
