<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_CSV_Iterator.
 *	@package		Tests.FS.File.CSV
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\FS\File\CSV;

use CeusMedia\Common\FS\File\CSV\Iterator;
use CeusMedia\Common\Test\BaseCase;
use CeusMedia\Common\Test\MockAntiProtection;

/**
 *	TestUnit of FS_File_CSV_Iterator.
 *	@package		Tests.FS:File.CSV
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class IteratorTest extends BaseCase
{
	protected $filePath;
	protected $iterator;
	protected $pathName;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->pathName	= dirname( __FILE__ ).'/';
		$this->filePath	= $this->pathName.'read.csv';
		$this->iterator	= new Iterator( $this->filePath, TRUE, ';', '"' );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$mock		= MockAntiProtection::getInstance( Iterator::class, $this->filePath, TRUE, '|', '#' );

		$assertion	= TRUE;
		$creation	= is_object( $mock );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $mock->getProtectedVar( 'useHeaders' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '|';
		$creation	= $mock->getProtectedVar( 'delimiter' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '#';
		$creation	= $mock->getProtectedVar( 'enclosure' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $this->filePath;
		$creation	= $mock->getProtectedVar( 'filePath' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getHeaders'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetHeaders()
	{
		$assertion	= array( 'id', 'col1', 'col2' );
		$creation	= $this->iterator->getHeaders();
		$this->assertEquals( $assertion, $creation );

		$iterator	= new Iterator( $this->filePath, FALSE );
		$creation	= $iterator->getHeaders();
		$this->assertEquals( [], $creation );
	}

	/**
	 *	Tests Method 'getDelimiter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDelimiter()
	{
		$assertion	= ';';
		$creation	= $this->iterator->getDelimiter();
		$this->assertEquals( $assertion, $creation );

		$iterator	= new Iterator( $this->filePath, TRUE, '_' );
		$assertion	= '_';
		$creation	= $iterator->getDelimiter();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEnclosure'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEnclosure()
	{
		$assertion	= '"';
		$creation	= $this->iterator->getEnclosure();
		$this->assertEquals( $assertion, $creation );

		$iterator	= new Iterator( $this->filePath, TRUE, ';', '_' );
		$assertion	= '_';
		$creation	= $iterator->getEnclosure();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setDelimiter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetDelimiter()
	{
		$assertion	= $this->iterator;
		$creation	= $this->iterator->setDelimiter( '#' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '#';
		$creation	= $this->iterator->getDelimiter();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setEnclosure'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetEnclosure()
	{
		$assertion	= $this->iterator;
		$creation	= $this->iterator->setEnclosure( '#' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '#';
		$creation	= $this->iterator->getEnclosure();
		$this->assertEquals( $assertion, $creation );
	}
}
