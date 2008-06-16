<?php
/**
 *	TestUnit of recursive RegexFilter for Folders.
 *	@package		Tests.folder
 *	@extends		Tests_Folder_TestCase
 *	@uses			Folder_RecursiveRegexFilter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
require_once( 'Tests/folder/TestCase.php' );
import( 'de.ceus-media.folder.RecursiveRegexFilter' );
/**
 *	TestUnit of recursive RegexFilter for Folders.
 *	@package		Tests.folder
 *	@extends		Tests_Folder_TestCase
 *	@uses			Folder_RecursiveRegexFilter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
class Tests_Folder_RecursiveRegexFilterTest extends Tests_Folder_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->path	= dirname( __FILE__ )."/";
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
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$folders	= array();
		$files		= array();
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Folder_RecursiveRegexFilter( $path, "@.*@" );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array(
			'sub1',
			'sub1sub1',
			'sub1sub2',
			'sub2',
			'sub2sub1',
		);
		$creation	= $folders;
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
		$creation	= $files;
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
		$index	= new Folder_RecursiveRegexFilter( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructTextFilesOnly()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Folder_RecursiveRegexFilter( $path, "@\.txt$@", TRUE, FALSE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array();
		$creation	= $folders;
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
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructFilesOnly()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Folder_RecursiveRegexFilter( $path, "@file@", TRUE, FALSE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array();
		$creation	= $folders;
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
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructPhpFilesOnly()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Folder_RecursiveRegexFilter( $path, "@\.php$@", TRUE, FALSE );
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
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Folder_RecursiveRegexFilter( $path, "@.*@", FALSE, TRUE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array(
			'sub1',
			'sub1sub1',
			'sub1sub2',
			'sub2',
			'sub2sub1',
		);
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
	public function a_testConstructSub1FoldersOnly()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Folder_RecursiveRegexFilter( $path, "@^sub1@", FALSE, TRUE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array(
			'sub1',
			'sub1sub1',
			'sub1sub2',
		);
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
	public function testConstructShowHiddenFolders()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Folder_RecursiveRegexFilter( $path, "@hidden@", FALSE, TRUE, FALSE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array( '.hidden' );
		$creation	= $folders;
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}
}
?>