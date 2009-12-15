<?php
/**
 *	TestUnit of List Writer.
 *	@package		Tests.file.list
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'test/initLoaders.php5'; 
/**
 *	TestUnit of List Writer.
 *	@package		Tests.file.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_List_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Test_File_List_WriterTest extends PHPUnit_Framework_TestCase
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
		$this->writer	= new File_List_Writer( $this->fileName );
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
		$creation	= File_List_Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->writer->add( 'line2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line1", "line2" );
		$creation	= File_List_Reader::read( $this->fileName );
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
		$this->setExpectedException( 'DomainException' );
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
		$creation	= File_List_Reader::read( $this->fileName );
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
		$this->setExpectedException( 'DomainException' );
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
		$creation	= File_List_Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= TRUE;
		$creation	= $this->writer->removeIndex( 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= File_List_Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'removeIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveIndexException()
	{
		$this->setExpectedException( 'DomainException' );
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
		$creation	= File_List_Writer::save( $this->fileName, $lines );
		$this->assertEquals( $assertion, $creation );
	
		$assertion	= $lines;
		$creation	= File_List_Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
?>