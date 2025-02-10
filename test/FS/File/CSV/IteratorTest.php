<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_CSV_Iterator.
 *	@package		Tests.FS.File.CSV
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\CSV;

use CeusMedia\Common\FS\File\CSV\Iterator;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\MockAntiProtection;

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
		self::assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $mock->getProtectedVar( 'useHeaders' );
		self::assertEquals( $assertion, $creation );

		$assertion	= '|';
		$creation	= $mock->getProtectedVar( 'delimiter' );
		self::assertEquals( $assertion, $creation );

		$assertion	= '#';
		$creation	= $mock->getProtectedVar( 'enclosure' );
		self::assertEquals( $assertion, $creation );

		$assertion	= $this->filePath;
		$creation	= $mock->getProtectedVar( 'filePath' );
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$iterator	= new Iterator( $this->filePath, FALSE );
		$creation	= $iterator->getHeaders();
		self::assertEquals( [], $creation );
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
		self::assertEquals( $assertion, $creation );

		$iterator	= new Iterator( $this->filePath, TRUE, '_' );
		$assertion	= '_';
		$creation	= $iterator->getDelimiter();
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$iterator	= new Iterator( $this->filePath, TRUE, ';', '_' );
		$assertion	= '_';
		$creation	= $iterator->getEnclosure();
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= '#';
		$creation	= $this->iterator->getDelimiter();
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= '#';
		$creation	= $this->iterator->getEnclosure();
		self::assertEquals( $assertion, $creation );
	}
}
