<?php
/**
 *	TestUnit of recursive Folder Indexer.
 *	@package		Tests.folder
 *	@extends		Tests_Folder_TestCase
 *	@uses			Folder_RecursiveLister
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
require_once( 'Tests/folder/TestCase.php' );
import( 'de.ceus-media.folder.RecursiveLister' );
/**
 *	TestUnit of recursive Folder Indexer.
 *	@package		Tests.folder
 *	@extends		Tests_Folder_TestCase
 *	@uses			Folder_RecursiveLister
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
class Tests_Folder_RecursiveListerTest extends Tests_Folder_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->path	= dirname( __FILE__ )."/folder";
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->lister1	= new Folder_RecursiveLister( $this->path );
		$this->lister2	= new Folder_RecursiveLister( "not_existing" );
	}

	/**
	 *	Returns Array of plain File and Folder Lists from Directory Iterator or Filter Iterator.
	 *	@access		private
	 *	@return		array
	 */
	private function getListFromIndex( $index )
	{
		$folders	= array();
		$files		= array();
		foreach( $index as $entry )
		{
			if( $entry->isDot() )
				continue;
			$name	= $entry->getFilename();
			if( $entry->isDir() )
				$folders[]	= $name;
			else if( $entry->isFile() )
				$files[]	= $name;
		}
		return array(
			'folders'	=> $folders,
			'files'		=> $files,
		);
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
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'file1.txt',
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
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Exception of Method 'getList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetListException()
	{
		$this->setExpectedException( 'RuntimeException' );
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
		$index	= $this->lister1->getList( );
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
			'file2_1.txt',
			'file2_1_1.txt',
		);
		$creation	= $list['files'];
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
		$index	= Folder_RecursiveLister::getFileList( $this->path );
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
			'file2_1.txt',
			'file2_1_1.txt',
		);
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getFileList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileListException()
	{
		$this->setExpectedException( 'RuntimeException' );
		$index	= Folder_RecursiveLister::getFileList( "not_existing" );
	}

	/**
	 *	Tests Method 'getFileList' with Patterns.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileListPatterns()
	{
		$index	= Folder_RecursiveLister::getFileList( $this->path, "@^file2@" );
		$list	= $this->getListFromIndex( $index );
		$assertion	= array(
			'file2_1.txt',
			'file2_1_1.txt',
		);
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );

		$index	= Folder_RecursiveLister::getFileList( $this->path, "@^file$@" );
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
		$index	= Folder_RecursiveLister::getFolderList( $this->path );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub1',
			'sub1sub1',
			'sub1sub2',
			'sub2',
			'sub2sub1',
		);
		$creation	= $list['folders'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Exception of Method 'getFolderList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFolderListException()
	{
		$this->setExpectedException( 'RuntimeException' );
		$index	= Folder_RecursiveLister::getFolderList( "not_existing" );
	}

	/**
	 *	Tests Method 'getFolderList' with Patterns.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFolderListPatterns()
	{
		$index	= Folder_RecursiveLister::getFolderList( $this->path, "@sub2@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub1sub2',
			'sub2',
			'sub2sub1',
		);
		$creation	= $list['folders'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );

		$index	= Folder_RecursiveLister::getFolderList( $this->path, "@^sub2$@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub2',
		);
		$creation	= $list['folders'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'getFolderList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMixedList()
	{
		$index	= Folder_RecursiveLister::getMixedList( $this->path );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub1',
			'sub1sub1',
			'sub1sub2',
			'sub2',
			'sub2sub1',
		);
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
			'file2_1.txt',
			'file2_1_1.txt',
		);
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );
	}
		
	/**
	 *	Tests Exception of Method 'getMixedList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMixedListException()
	{
		$this->setExpectedException( 'RuntimeException' );
		$index	= Folder_RecursiveLister::getMixedList( "not_existing" );
	}

	/**
	 *	Tests Method 'getMixedList' with Patterns.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMixedListPatterns()
	{
		$index	= Folder_RecursiveLister::getMixedList( $this->path, "@sub1@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub1',
			'sub1sub1',
			'sub1sub2',
			'sub2sub1',
		);
		$creation	= $list['folders'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );

		$index	= Folder_RecursiveLister::getMixedList( $this->path, "@^sub1$@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'sub1',
		);
		$creation	= $list['folders'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );

		$index	= Folder_RecursiveLister::getMixedList( $this->path, "@^file@" );
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
			'file2_1.txt',
			'file2_1_1.txt',
		);
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );

		$index	= Folder_RecursiveLister::getMixedList( $this->path, "@^file$@" );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array();
		$creation	= $list['folders'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getMixedList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetMixedListShowHidden()
	{
		$index	= Folder_RecursiveLister::getMixedList( $this->path, NULL, FALSE );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array(
			'.hidden',
			'sub1',
			'sub1sub1',
			'sub1sub2',
			'sub2',
			'sub2sub1',
		);
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
			'file2_1.txt',
			'file2_1_1.txt',
		);
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );

		$index	= Folder_RecursiveLister::getMixedList( $this->path, "@hidden$@", FALSE );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array( '.hidden' );
		$creation	= $list['folders'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );
	}
}
?>