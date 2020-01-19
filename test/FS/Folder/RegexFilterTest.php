<?php
/**
 *	TestUnit of RegexFilter for Folders.
 *	@package		Tests.folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
require_once dirname( dirname( __DIR__ ) ).'/initLoaders.php';
/**
 *	TestUnit of RegexFilter for Folders.
 *	@package		Tests.folder
 *	@extends		Test_FS_Folder_TestCase
 *	@uses			FS_Folder_RegexFilter
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
class Test_FS_Folder_RegexFilterTest extends Test_FS_Folder_TestCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->path	= str_replace( "\\", "/", dirname( __FILE__ ) )."/";
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$folders	= array();
		$files		= array();
		$path		= $this->path."folder";
		$index		= new FS_Folder_RegexFilter( $path, "@.*@" );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array(
			'sub1',
			'sub2'
		);
		$creation	= $folders;
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'file1.txt',
			'file2.txt'
		);
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
		$index	= new FS_Folder_RegexFilter( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructTextFilesOnly()
	{
		$path		= $this->path."folder";
		$index		= new FS_Folder_RegexFilter( $path, "@\.txt$@", TRUE, FALSE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array();
		$creation	= $folders;
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'file1.txt',
			'file2.txt'
		);
		$creation	= $files;
		sort( $creation );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructFilesOnly()
	{
		$path		= $this->path."folder";
		$index		= new FS_Folder_RegexFilter( $path, "@file@", TRUE, FALSE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array();
		$creation	= $folders;
		sort( $creation );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'file1.txt',
			'file2.txt'
			);
		$creation	= $files;
		sort( $creation );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructPhpFilesOnly()
	{
		$path		= $this->path."folder";
		$index		= new FS_Folder_RegexFilter( $path, "@\.php$@", TRUE, FALSE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array();
		$creation	= $folders;
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructFoldersOnly()
	{
		$path		= $this->path."folder";
		$index		= new FS_Folder_RegexFilter( $path, "@.*@", FALSE, TRUE );
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
	public function testConstructSubFoldersOnly()
	{
		$path		= $this->path."folder";
		$index		= new FS_Folder_RegexFilter( $path, "@^sub@", FALSE, TRUE );
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
	public function testConstructShowHiddenFolders()
	{
		$path		= $this->path."folder";
		$index		= new FS_Folder_RegexFilter( $path, "@.*@", FALSE, TRUE, FALSE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array(
			'sub1',
			'sub2',
			'.sub3',
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
