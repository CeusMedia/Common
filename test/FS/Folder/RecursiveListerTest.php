<?php
/**
 *	TestUnit of recursive Folder Indexer.
 *	@package		Tests.folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/TestCase.php';

/**
 *	TestUnit of recursive Folder Indexer.
 *	@package		Tests.folder
 *	@extends		Test_FS_Folder_TestCase
 *	@uses			FS_Folder_RecursiveLister
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
class Test_FS_Folder_RecursiveListerTest extends Test_FS_Folder_TestCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		parent::setUp();
		$this->lister1	= new FS_Folder_RecursiveLister( $this->folder );
		$this->lister2	= new FS_Folder_RecursiveLister( "not_existing" );
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
			'sub1sub1',
			'sub1sub2',
			'sub2',
			'sub2sub1',
		);
		$creation	= $list['folders'];
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

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
		$this->assertEquals( $assertion, $creation );
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

		$assertion	= array();
		$creation	= $list['folders'];
		$this->assertEquals( $assertion, $creation );

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
		$this->assertEquals( $assertion, $creation );

		$this->lister1->setExtensions( array( "php" ) );
		$index	= $this->lister1->getList();
		$list	= $this->getListFromIndex( $index );

		$assertion	= array();
		$creation	= $list['folders'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFileList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileList()
	{
		$index	= FS_Folder_RecursiveLister::getFileList( $this->folder );
		$list	= $this->getListFromIndex( $index );
		$assertion	= array();
		$creation	= $list['folders'];
		$this->assertEquals( $assertion, $creation );

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
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getFileList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileListException()
	{
		$this->expectException( 'RuntimeException' );
		$index	= FS_Folder_RecursiveLister::getFileList( "not_existing" );
	}

	/**
	 *	Tests Method 'getFileList' with Patterns.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileListPatterns()
	{
		$index	= FS_Folder_RecursiveLister::getFileList( $this->folder, "@^file2@" );
		$list	= $this->getListFromIndex( $index );
		$assertion	= array(
			'file2.txt',
			'file2_1.txt',
			'file2_1_1.txt',
		);
		$creation	= $list['files'];
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$index	= FS_Folder_RecursiveLister::getFileList( $this->folder, "@^file$@" );
		$list	= $this->getListFromIndex( $index );
		$assertion	= array();
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFolderList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFolderList()
	{
		$index	= FS_Folder_RecursiveLister::getFolderList( $this->folder );
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
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		sort( $creation );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getFolderList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFolderListException()
	{
		$this->expectException( 'RuntimeException' );
		$index	= FS_Folder_RecursiveLister::getFolderList( "not_existing" );
	}

	/**
	 *	Tests Method 'getFolderList' with Patterns.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFolderListPatterns()
	{
		$index	= FS_Folder_RecursiveLister::getFolderList( $this->folder, "@sub2@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub1sub2',
			'sub2',
			'sub2sub1',
		);
		$creation	= $list['folders'];
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$index	= FS_Folder_RecursiveLister::getFolderList( $this->folder, "@^sub2$@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub2',
		);
		$creation	= $list['folders'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		sort( $creation );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFolderList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMixedList()
	{
		$index	= FS_Folder_RecursiveLister::getMixedList( $this->folder );
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
		$this->assertEquals( $assertion, $creation );

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
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getMixedList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMixedListException()
	{
		$this->expectException( 'RuntimeException' );
		$index	= FS_Folder_RecursiveLister::getMixedList( "not_existing" );
	}

	/**
	 *	Tests Method 'getMixedList' with Patterns.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMixedListPatterns()
	{
		$index	= FS_Folder_RecursiveLister::getMixedList( $this->folder, "@sub1@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub1',
			'sub1sub1',
			'sub1sub2',
			'sub2sub1',
		);
		$creation	= $list['folders'];
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$index	= FS_Folder_RecursiveLister::getMixedList( $this->folder, "@^sub1$@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub1',
		);
		$creation	= $list['folders'];
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$index	= FS_Folder_RecursiveLister::getMixedList( $this->folder, "@^file@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array();
		$creation	= $list['folders'];
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

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
		$this->assertEquals( $assertion, $creation );

		$index	= FS_Folder_RecursiveLister::getMixedList( $this->folder, "@^file$@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array();
		$creation	= $list['folders'];
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		sort( $creation );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getMixedList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMixedListShowHidden()
	{
		$index	= FS_Folder_RecursiveLister::getMixedList( $this->folder, NULL, FALSE );
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
		$this->assertEquals( $assertion, $creation );

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
		$this->assertEquals( $assertion, $creation );

		$index	= FS_Folder_RecursiveLister::getMixedList( $this->folder, "@sub3$@", FALSE );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array( '.sub3' );
		$creation	= $list['folders'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );
	}
}
