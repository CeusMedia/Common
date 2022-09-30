<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_CSV_Reader.
 *	@package		Tests.FS.File.CSV
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\CSV;

use CeusMedia\Common\FS\File\CSV\Reader;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\MockAntiProtection;

/**
 *	TestUnit of FS_File_CSV_Reader.
 *	@package		Tests.File.CSV
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ReaderTest extends BaseCase
{
	protected $filePath;
	protected $pathName;
	protected $reader;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->pathName	= dirname( __FILE__ ).'/';
		$this->filePath	= $this->pathName.'read.csv';
		$this->reader	= new Reader( $this->filePath, TRUE, ';' );
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
		$mock		= MockAntiProtection::getInstance( Reader::class, $this->filePath, TRUE, '|', '#' );

		$assertion	= TRUE;
		$creation	= is_object( $mock );
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
		$creation	= $this->reader->getHeaders();
		$this->assertEquals( $assertion, $creation );

		$reader		= new Reader( $this->filePath, FALSE, ';' );
		$assertion	= [];
		$creation	= $reader->getHeaders();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'count'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCount()
	{
		$assertion	= 2;
		$creation	= $this->reader->count();
		$this->assertEquals( $assertion, $creation );

		$creation	= $this->reader->count();
		$this->assertEquals( $assertion, $creation, 'Not same size on 2nd attempt' );

		$reader		= new Reader( $this->filePath, FALSE, ';' );
		$assertion	= 3;
		$creation	= $reader->count();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray()
	{
		$assertion	= array(
			array(
				'id'	=> '1',
				'col1'	=> 'test1',
				'col2'	=> 'string without semicolon'
			),
			array(
				'id'	=> '2',
				'col1'	=> 'test2',
				'col2'	=> 'string with ; semicolon'
			)
		);
		$creation	= $this->reader->toArray();
//print(json_encode($creation, JSON_PRETTY_PRINT));
		$this->assertEquals( $assertion, $creation );

		$reader		= new Reader( $this->filePath, FALSE, ';' );
		$assertion	= array(
			array(
				'id', 'col1', 'col2'
			), array(
				'1', 'test1', 'string without semicolon'
			), array(
				'2', 'test2', 'string with ; semicolon'
			)
		);
		$creation	= $reader->toArray();
		$this->assertEquals( $assertion, $creation );
	}
}
