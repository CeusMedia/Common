<?php
declare( strict_types = 1 );
/**
 *	TestUnit of List Writer.
 *	@package		Tests.FS.File.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\Collection;

use CeusMedia\Common\FS\File\Collection\Reader;
use CeusMedia\Common\FS\File\Collection\Writer;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of List Writer.
 *	@package		Tests.FS.File.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class WriterTest extends BaseCase
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
		$this->fileName	= dirname( __FILE__ )."/writer.list";
		$this->writer	= new Writer( $this->fileName );
	}

	/**
	 *	Clean up.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
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
		$creation	= Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->writer->add( 'line2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line1", "line2" );
		$creation	= Reader::read( $this->fileName );
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
		$creation	= Reader::read( $this->fileName );
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
		$creation	= Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$creation	= $this->writer->removeIndex( 0 );
		$this->assertEquals( 0, $creation );

		$assertion	= array();
		$creation	= Reader::read( $this->fileName );
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
		$creation	= Writer::save( $this->fileName, $lines );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $lines;
		$creation	= Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
