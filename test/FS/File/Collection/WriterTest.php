<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

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
	private string $fileName;

	private Writer $writer;

	/**
	 *	Tests Method 'add'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAdd(): void
	{
		self::assertIsInt( $this->writer->add( 'line1' ) );
		self::assertEquals( ['line1'], Reader::read( $this->fileName ) );
		self::assertIsInt( $this->writer->add( 'line2' ) );
		self::assertEquals( ['line1', 'line2'], Reader::read( $this->fileName ) );
	}

	/**
	 *	Tests Exception of Method 'add'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddException(): void
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
	public function testRemove(): void
	{
		$this->writer->add( 'line1' );
		$this->writer->add( 'line2' );

		self::assertIsInt( $this->writer->remove( 'line1' ) );
		self::assertEquals( ['line2'], Reader::read( $this->fileName ) );
	}

	/**
	 *	Tests Exception of Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveException(): void
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
	public function testRemoveIndex(): void
	{
		$this->writer->add( 'line1' );
		$this->writer->add( 'line2' );

		self::assertIsInt( $this->writer->removeIndex( 1 ) );
		self::assertEquals( ['line1'], Reader::read( $this->fileName ) );
		self::assertEquals( 0, $this->writer->removeIndex( 0 ) );
		self::assertEquals( [], Reader::read( $this->fileName ) );
	}

	/**
	 *	Tests Exception of Method 'removeIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveIndexException(): void
	{
		$this->expectException( 'DomainException' );
		$this->writer->removeIndex( 10 );
	}

	/**
	 *	Tests Method 'setSave'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSave(): void
	{
		$lines	= [
			'line1',
			'line2',
			'line3',
		];
		self::assertIsInt( Writer::save( $this->fileName, $lines ) );
		self::assertEquals( $lines, Reader::read( $this->fileName ) );
	}

	/**
	 *	Set up for every Test.
	 *	@access		public
	 *	@return		void
	 */
	protected function setUp(): void
	{
		$this->fileName	= dirname( __FILE__ )."/writer.list";
		$this->writer	= new Writer( $this->fileName );
	}

	/**
	 *	Clean up.
	 *	@access		public
	 *	@return		void
	 */
	protected function tearDown(): void
	{
		@unlink( $this->fileName );
	}
}
