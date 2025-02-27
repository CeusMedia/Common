<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of recursive Folder Iterator.
 *	@package		Tests.FS.Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\Folder;

use CeusMedia\Common\FS\Folder\RecursiveIterator;

/**
 *	TestUnit of recursive Folder Iterator.
 *	@package		Tests.FS.Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class RecursiveIteratorTest extends TestCase
{
	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new RecursiveIterator( $path );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub1',
			'sub1sub1',
			'sub1sub2',
			'sub2',
			'sub2sub1',
		);

		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'file1.txt',
			'file1_1.txt',
			'file1_2.txt',
			'file1_1_1.txt',
			'file1_1_2.txt',
			'file1_2_1.txt',
			'file1_2_2.txt',
			'file2.txt',
			'file2_1.txt',
			'file2_1_1.txt',
		);
		$creation	= $list['files'];
		sort( $assertion );
		sort( $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException()
	{
		$this->expectException( 'RuntimeException' );
		new RecursiveIterator( "not_existing" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructFilesOnly()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new RecursiveIterator( $path, TRUE, FALSE );
		$list	= $this->getListFromIndex( $index );

		$assertion	= [];
		$creation	= $list['folders'];
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'file1.txt',
			'file2.txt',
			'file1_1.txt',
			'file1_2.txt',
			'file1_1_1.txt',
			'file1_1_2.txt',
			'file1_2_1.txt',
			'file1_2_2.txt',
			'file2_1.txt',
			'file2_1_1.txt',
		);
		$creation	= $list['files'];
		sort( $assertion );
		sort( $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructFoldersOnly()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new RecursiveIterator( $path, FALSE, TRUE );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub1',
			'sub1sub1',
			'sub1sub2',
			'sub2',
			'sub2sub1',
		);
		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= [];
		$creation	= $list['files'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructHiddenFilesAlso()
	{
		$path	= str_replace( "\\", "/", $this->path."folder" );
		$index	= new RecursiveIterator( $path, TRUE, FALSE, FALSE );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'file1.txt',
			'file1_1.txt',
			'file1_2.txt',
			'file1_1_1.txt',
			'file1_1_2.txt',
			'file1_2_1.txt',
			'file1_2_2.txt',
			'file2.txt',
			'file2_1.txt',
			'file2_1_1.txt',
			'.file2_1_2.txt',
			'.file2_2.txt',
			'file2_2_1.txt',
			'.file2_2_2.txt',
			'.file3.txt',
			'file3_1.txt',
			'file3_1_1.txt',
			'.file3_1_2.txt',
			'.file3_2.txt',
			'file3_2_1.txt',
			'.file3_2_2.txt',
		);
		$creation	= $list['files'];
		sort( $assertion );
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= [];
		$creation	= $list['folders'];
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructHiddenFoldersAlso()
	{
		$path	= str_replace( "\\", "/", $this->path."folder" );
		$index	= new RecursiveIterator( $path, FALSE, TRUE, FALSE );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub1',
			'sub1sub1',
			'sub1sub2',
			'sub2',
			'sub2sub1',
			'.sub2sub2',
			'.sub3',
			'sub3sub1',
			'.sub3sub2',
		);
		$creation	= $list['folders'];
		sort( $assertion );
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= [];
		$creation	= $list['files'];
		self::assertEquals( $assertion, $creation );
	}
}
