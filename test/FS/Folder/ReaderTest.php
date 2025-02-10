<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Folder Reader.
 *	@package		Tests.FS.Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\Folder;

use CeusMedia\Common\FS\Folder\Reader;

/**
 *	TestUnit of Folder Reader.
 *	@package		Tests.FS.Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ReaderTest extends TestCase
{
	protected $reader1;

	protected $reader2;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		parent::setUp();
		//  valid Folder Reader
		$this->reader1	= new Reader( $this->path."folder" );
		//  invalid Folder Reader
		$this->reader2	= new Reader( $this->path."not_existing" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$reader	= new Reader( "test123" );
		$assertion	= "test123";
		$creation	= $reader->getFolderName();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'exists'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExists()
	{
		$creation	= $this->reader1->exists();
		self::assertTrue( $creation );

		$creation	= $this->reader2->exists();
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Method 'getCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetCount()
	{
		$assertion	= 4;
		$creation	= $this->reader1->getCount();
		self::assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $this->reader1->getCount( "@sub@" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetCountException()
	{
		$this->expectException( "RuntimeException" );
		$creation	= $this->reader2->getCount();
	}

	/**
	 *	Tests Method 'getFileCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileCount()
	{
		$assertion	= 2;
		$creation	= $this->reader1->getFileCount();
		self::assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $this->reader1->getFileCount( "@file@" );
		self::assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $this->reader1->getFileCount( "@2_1@" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getFileCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileCountException()
	{
		$this->expectException( "RuntimeException" );
		$this->reader2->getFileCount();
	}

	/**
	 *	Tests Method 'getFileList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileList()
	{
		$index	= $this->reader1->getFileList();
		$list	= $this->getListFromIndex( $index );
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
		$this->expectException( "RuntimeException" );
		$this->reader2->getFileList();
	}

	/**
	 *	Tests Method 'getFileListByExtensions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileListByExtensions()
	{
		$index	= $this->reader1->getFileListByExtensions( array( 'txt' ) );
		$list	= $this->getListFromIndex( $index );
		$assertion	= array(
			'file1.txt',
			'file2.txt',
		);
		$creation	= $list['files'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$index	= $this->reader1->getFileListByExtensions( array( 'php' ) );
		$list	= $this->getListFromIndex( $index );
		$assertion	= [];
		$creation	= $list['files'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getFileListByExtensions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileListByExtensionsExecption()
	{
		$this->expectException( "RuntimeException" );
		$creation	= $this->reader2->getFileListByExtensions( array( "*" ) );
	}

	/**
	 *	Tests Method 'getFolderCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFolderCount()
	{
		$assertion	= 2;
		$creation	= $this->reader1->getFolderCount();
		self::assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->reader1->getFolderCount( "@sub1@" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getFolderCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFolderCountException()
	{
		$this->expectException( "RuntimeException" );
		$creation	= $this->reader2->getFolderCount();
	}

	/**
	 *	Tests Method 'getFolderList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFolderList()
	{
		$index	= $this->reader1->getFolderList();
		$list	= $this->getListFromIndex( $index );
		$assertion	= array( 'sub1', 'sub2' );
		$creation	= $list['folders'];
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
		$this->expectException( "RuntimeException" );
		$index	= $this->reader2->getFolderList();
	}

	/**
	 *	Tests Method 'getFolderName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFolderName()
	{
		$assertion	= $this->path."folder";
		$creation	= $this->reader1->getFolderName();
		self::assertEquals( $assertion, $creation );

		$assertion	= $this->path."not_existing";
		$creation	= $this->reader2->getFolderName();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetList()
	{
		$index	= $this->reader1->getList();
		$list	= $this->getListFromIndex( $index );

		$assertion	= array( 'sub1', 'sub2' );
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

		$index	= $this->reader1->getList( "@xyz@" );
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
	 *	Tests Exception of Method 'getList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetListException()
	{
		$this->expectException( "RuntimeException" );
		$index	= $this->reader2->getList();
	}

	/**
	 *	Tests Method 'getName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetName()
	{
		$assertion	= "folder";
		$creation	= $this->reader1->getName();
		self::assertEquals( $assertion, $creation );

		$assertion	= "not_existing";
		$creation	= $this->reader2->getName();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPath()
	{
		$assertion	= $this->path;
		$creation	= $this->reader1->getPath();
		self::assertEquals( $assertion, $creation );

		$assertion	= $this->path;
		$creation	= $this->reader2->getPath();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getRealPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRealPath()
	{
		$assertion	= dirname( realpath( __FILE__ ) )."/";
		$creation	= $this->reader1->getRealPath();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getRealPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRealPathException()
	{
		$this->expectException( "RuntimeException" );
		$creation	= $this->reader2->getRealPath();
	}

	/**
	 *	Tests Method 'getNestedCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNestedCount()
	{
		$assertion	= 15;
		$creation	= $this->reader1->getNestedCount();
		self::assertEquals( $assertion, $creation );

		$assertion	= 5;
		$creation	= $this->reader1->getNestedCount( "@sub@" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getNestedCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNestedCountException()
	{
		$this->expectException( "RuntimeException" );
		$index	= $this->reader2->getNestedCount();
	}

	/**
	 *	Tests Method 'getNestedFileCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNestedFileCount()
	{
		$assertion	= 10;
		$creation	= $this->reader1->getNestedFileCount();
		self::assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= $this->reader1->getNestedFileCount( "@2_1@" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getNestedFileCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNestedFileCountException()
	{
		$this->expectException( "RuntimeException" );
		$index	= $this->reader2->getNestedFileCount();
	}

	/**
	 *	Tests Method 'getNestedFileList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNestedFileList()
	{
		$index	= $this->reader1->getNestedFileList();
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
		);
		$creation	= $list['files'];
		sort( $assertion );
		sort( $creation );
		self::assertEquals( $assertion, $creation );

		$index	= $this->reader1->getNestedFileList( "@not_existing@" );
		$list	= $this->getListFromIndex( $index );
		$assertion	= [];
		$creation	= $list['files'];
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getNestedFileList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNestedFileListException()
	{
		$this->expectException( "RuntimeException" );
		$index	= $this->reader2->getNestedFileList();
	}

	/**
	 *	Tests Method 'getNestedFolderCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNestedFolderCount()
	{
		$assertion	= 5;
		$creation	= $this->reader1->getNestedFolderCount();
		self::assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= $this->reader1->getNestedFolderCount( "@sub1@" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getNestedFolderCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNestedFolderCountException()
	{
		$this->expectException( "RuntimeException" );
		$index	= $this->reader2->getNestedFolderCount();
	}

	/**
	 *	Tests Method 'getNestedFolderList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNestedFolderList()
	{
		$index	= $this->reader1->getNestedFolderList();
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

		$index	= $this->reader1->getNestedFolderList( "@not_existing@" );
		$list	= $this->getListFromIndex( $index );
		$assertion	= [];
		$creation	= $list['folders'];
		sort( $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getNestedFolderList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNestedFolderListException()
	{
		$this->expectException( "RuntimeException" );
		$index	= $this->reader2->getNestedFolderList();
	}

	/**
	 *	Tests Method 'getNestedList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNestedList()
	{
		$index	= $this->reader1->getNestedList();
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
	 *	Tests Exception of Method 'getNestedList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNestedListException()
	{
		$this->expectException( "RuntimeException" );
		$index	= $this->reader2->getNestedList();
	}

	/**
	 *	Tests Method 'getNestedSize'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNestedSize()
	{
		$assertion	= 40;
		$creation	= $this->reader1->getNestedSize();
		self::assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $this->reader1->getNestedSize( "@not_existing@" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getNestedSize'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNestedSizeException()
	{
		$this->expectException( "RuntimeException" );
		$index	= $this->reader2->getNestedSize();
	}

	/**
	 *	Tests Method 'getSize'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSize()
	{
		$assertion	= 8;
		$creation	= $this->reader1->getSize();
		self::assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $this->reader1->getSize( "@not_existing@" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getSize'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSizeException()
	{
		$this->expectException( "RuntimeException" );
		$creation	= $this->reader2->getSize();
	}

	/**
	 *	Tests Method 'isFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFolder()
	{
		$assertion	= true;
		$creation	= Reader::isFolder( $this->path."folder" );
		self::assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Reader::isFolder( $this->path."not_existing" );
		self::assertEquals( $assertion, $creation );
	}
}
