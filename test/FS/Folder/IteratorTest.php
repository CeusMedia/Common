<?php
/**
 *	TestUnit of Folder Iterator.
 *	@package		Tests.folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			21.04.2008
 *
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Folder Iterator.
 *	@package		Tests.folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			21.04.2008
 *
 */
class Test_FS_Folder_IteratorTest extends Test_FS_Folder_TestCase
{
	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new FS_Folder_Iterator( $path );
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
		$this->expectException( 'RuntimeException' );
		$index	= new FS_Folder_Iterator( "not_existing" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructFilesOnly()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new FS_Folder_Iterator( $path, TRUE, FALSE );
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
		$index	= new FS_Folder_Iterator( $path, FALSE, TRUE );
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
		$index	= new FS_Folder_Iterator( $path, TRUE, FALSE, FALSE );
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
		$index	= new FS_Folder_Iterator( $path, FALSE, TRUE, FALSE );
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
