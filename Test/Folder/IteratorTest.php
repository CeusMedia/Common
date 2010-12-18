<?php
/**
 *	TestUnit of Folder Iterator.
 *	@package		Tests.folder
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Folder Iterator.
 *	@package		Tests.folder
 *	@extends		Test_Folder_TestCase
 *	@uses			Folder_Iterator
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
class Test_Folder_IteratorTest extends Test_Folder_TestCase
{
	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Folder_Iterator( $path );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array( 'sub1', 'sub2' );
		$creation	= $folders;
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'file1.txt', 'file2.txt' );
		$creation	= $files;
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
		$index	= new Folder_Iterator( "not_existing" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructFilesOnly()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Folder_Iterator( $path, TRUE, FALSE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array();
		$creation	= $folders;
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'file1.txt', 'file2.txt' );
		$creation	= $files;
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
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Folder_Iterator( $path, FALSE, TRUE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array( 'sub1', 'sub2' );
		$creation	= $folders;
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $files;
		sort( $creation );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructShowHiddenFiles()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Folder_Iterator( $path, TRUE, FALSE, FALSE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array(
			'file1.txt',
			'file2.txt',
			'.file3.txt'
		);
		$creation	= $files;
		sort( $assertion );
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $folders;
		sort( $creation );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructShowHiddenFolders()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Folder_Iterator( $path, FALSE, TRUE, FALSE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array(
			'sub1',
			'sub2',
			'.sub3'
		);
		$creation	= $folders;
		sort( $assertion );
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $files;
		sort( $creation );
		$this->assertEquals( $assertion, $creation );
	}
}
?>