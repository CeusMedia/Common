<?php
declare( strict_types = 1 );
/**
 *	TestUnit of Collection Reader.
 *	@package		Tests.FS.File.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\Collection;

use CeusMedia\Common\FS\File\Collection\Reader;
use CeusMedia\CommonTest\BaseCase;
use RuntimeException;

/**
 *	TestUnit of Collection Reader.
 *	@package		Tests.FS.File.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ReaderTest extends BaseCase
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
		$this->reader	= new Reader( $this->fileName );
	}

	/**
	 *	Tests Method 'count'.
	 *	@access		public
	 *	@return		void
	 */
	public function test_count()
	{
		$assertion	= 2;
		$creation	= $this->reader->count();
		$this->assertEquals( $assertion, $creation );
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
		$creation	= Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$fileName	= dirname( $this->fileName )."/empty.list";
		file_put_contents( $fileName, "" );
		$assertion	= [];
		$creation	= Reader::read( $fileName );
		unlink( $fileName );
	}

	/**
	 *	Tests Exception of Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadException()
	{
		$this->expectException( RuntimeException::class );
		Reader::read( "not_existing", TRUE );
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
