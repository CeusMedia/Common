<?php
/**
 *	TestUnit of Folder Indexer.
 *	@package		Tests.folder
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Folder_Indexer
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.folder.Indexer' );
/**
 *	TestUnit of Folder Indexer.
 *	@package		Tests.folder
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Folder_Indexer
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
class Tests_Folder_IndexerTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
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
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Folder_Indexer( $path );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array( 'sub1', 'sub2' );
		$creation	= $folders;
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'file1.txt' );
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
		$index	= new Folder_Indexer( "not_existing" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructFilesOnly()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Folder_Indexer( $path, TRUE, FALSE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array();
		$creation	= $folders;
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'file1.txt' );
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
		$index	= new Folder_Indexer( $path, FALSE, TRUE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array( 'sub1', 'sub2' );
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
		$index	= new Folder_Indexer( $path, FALSE, TRUE, FALSE );
		extract( $this->getListFromIndex( $index ) );

		$assertion	= array( '.hidden', 'sub1', 'sub2' );
		$creation	= $folders;
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}
}
?>