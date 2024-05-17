<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Folder Indexer.
 *	@package		Tests.FS.Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\Folder;

use CeusMedia\Common\FS\Folder\Lister;
use CeusMedia\CommonTest\FS\Folder\TestCase;

/**
 *	TestUnit of Folder Indexer.
 *	@package		Tests.FS.Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ListerTest extends TestCase
{
	protected $lister1;

	protected $lister2;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		parent::setUp();
		$this->lister1	= new Lister( $this->folder );
		$this->lister2	= new Lister( "not_existing" );
	}

	/**
	 *	Tests Method 'getList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetList()
	{
		$index	= $this->lister1->getList();
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub1',
			'sub2'
		);
		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'file1.txt',
			'file2.txt',
		);
		$creation	= $list['files'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetListException()
	{
		$this->expectException( 'RuntimeException' );
		$this->lister2->getList( "not_relevant" );
	}

	/**
	 *	Tests Method 'getList' with Extensions.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetListExtensions()
	{
		$this->lister1->setExtensions( array( "txt", "php" ) );
		$index	= $this->lister1->getList();
		$list	= $this->getListFromIndex( $index );

		$assertion	= [];
		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'file1.txt',
			'file2.txt',
		);
		$creation	= $list['files'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$this->lister1->setExtensions( array( "php" ) );
		$index	= $this->lister1->getList();
		$list	= $this->getListFromIndex( $index );

		$assertion	= [];
		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= [];
		$creation	= $list['files'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFileList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileList()
	{
		$index	= Lister::getFileList( $this->folder );
		$list	= $this->getListFromIndex( $index );

		$assertion	= [];
		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'file1.txt',
			'file2.txt',
		);
		$creation	= $list['files'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getFileList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileListException()
	{
		$this->expectException( 'RuntimeException' );
		$index	= Lister::getFileList( "not_existing" );
	}

	/**
	 *	Tests Method 'getFileList' with Patterns.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileListPatterns()
	{
		$index	= Lister::getFileList( $this->folder, "@^file@" );
		$list	= $this->getListFromIndex( $index );
		$assertion	= array(
			'file1.txt',
			'file2.txt',
		);
		$creation	= $list['files'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$index	= Lister::getFileList( $this->folder, "@^file$@" );
		$list	= $this->getListFromIndex( $index );
		$assertion	= [];
		$creation	= $list['files'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFolderList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFolderList()
	{
		$index	= Lister::getFolderList( $this->folder );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array( 'sub1', 'sub2' );
		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= [];
		$creation	= $list['files'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getFolderList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFolderListException()
	{
		$this->expectException( 'RuntimeException' );
		$index	= Lister::getFolderList( "not_existing" );
	}

	/**
	 *	Tests Method 'getFolderList' with Patterns.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFolderListPatterns()
	{
		$index	= Lister::getFolderList( $this->folder, "@sub@" );
		$list	= $this->getListFromIndex( $index );
		$assertion	= array( 'sub1', 'sub2' );
		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$index	= Lister::getFolderList( $this->folder, "@^sub1$@" );
		$list	= $this->getListFromIndex( $index );
		$assertion	= array( 'sub1' );
		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFolderList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMixedList()
	{
		$index	= Lister::getMixedList( $this->folder );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub1',
			'sub2'
		);
		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'file1.txt',
			'file2.txt',
		);
		$creation	= $list['files'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getMixedList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMixedListException()
	{
		$this->expectException( 'RuntimeException' );
		Lister::getMixedList( "not_existing" );
	}

	/**
	 *	Tests Method 'getMixedList' with Patterns.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMixedListPatterns()
	{
		$index	= Lister::getMixedList( $this->folder, "@sub@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array( 'sub1', 'sub2' );
		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= [];
		$creation	= $list['files'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$index	= Lister::getMixedList( $this->folder, "@^sub1$@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array( 'sub1' );
		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= [];
		$creation	= $list['files'];
		self::assertEquals( $assertion, $creation );

		$index	= Lister::getMixedList( $this->folder, "@^file@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= [];
		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'file1.txt',
			'file2.txt',
		);
		$creation	= $list['files'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$index	= Lister::getMixedList( $this->folder, "@^file$@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= [];
		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= [];
		$creation	= $list['files'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getMixedList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMixedListShowHidden()
	{
		$index	= Lister::getMixedList( $this->folder, NULL, FALSE );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub1',
			'sub2',
			'.sub3',
		);
		$creation	= $list['folders'];
		sort( $assertion );
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'file1.txt',
			'file2.txt',
			'.file3.txt',
		);
		$creation	= $list['files'];
		sort( $assertion );
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$index	= Lister::getMixedList( $this->folder, "@sub3$@", FALSE );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array( '.sub3' );
		$creation	= $list['folders'];
		self::assertEquals( $assertion, $creation );

		$assertion	= [];
		$creation	= $list['files'];
		self::assertEquals( $assertion, $creation );
	}
}
