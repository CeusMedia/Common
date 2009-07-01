<?php
/**
 *	TestUnit of recursive Folder Iterator.
 *	@package		Tests.folder
 *	@extends		Tests_Folder_TestCase
 *	@uses			Folder_RecursiveIterator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
require_once( 'Tests/folder/TestCase.php' );
import( 'de.ceus-media.folder.RecursiveIterator' );
/**
 *	TestUnit of recursive Folder Iterator.
 *	@package		Tests.folder
 *	@extends		Tests_Folder_TestCase
 *	@uses			Folder_RecursiveIterator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
class Tests_Folder_RecursiveIteratorTest extends Tests_Folder_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$index	= new Folder_RecursiveIterator( $this->folder );
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
	 *	Tests Exception of Method 'construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException()
	{
		$this->setExpectedException( 'RuntimeException' );
		$index	= new Folder_RecursiveIterator( "not_existing" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructFilesOnly()
	{
		$index	= new Folder_RecursiveIterator( $this->folder, TRUE, FALSE );
		$list	= $this->getListFromIndex( $index );

		$assertion	= array();
		$creation	= $list['folders'];
		$this->assertEquals( $assertion, $creation );
		
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
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructFoldersOnly()
	{
		$index	= new Folder_RecursiveIterator( $this->folder, FALSE, TRUE );
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
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructHiddenFilesAlso()
	{
		$index	= new Folder_RecursiveIterator( $this->folder, TRUE, FALSE, FALSE );
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
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= array();
		$creation	= $list['folders'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructHiddenFoldersAlso()
	{
		$index	= new Folder_RecursiveIterator( $this->folder, FALSE, TRUE, FALSE );
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
		
		$assertion	= array();
		$creation	= $list['files'];
		$this->assertEquals( $assertion, $creation );
	}
}
?>