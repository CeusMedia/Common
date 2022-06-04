<?php
declare( strict_types = 1 );
/**
 *	TestUnit of Folder Iterator.
 *	@package		Tests.FS.Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\FS\Folder;

use CeusMedia\Common\FS\Folder\Iterator;
use CeusMedia\Common\Test\FS\Folder\TestCase;

/**
 *	TestUnit of Folder Iterator.
 *	@package		Tests.FS.Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class IteratorTest extends TestCase
{
	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Iterator( $path );
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
		$index	= new Iterator( "not_existing" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructFilesOnly()
	{
		$path		= str_replace( "\\", "/", $this->path."folder" );
		$index	= new Iterator( $path, TRUE, FALSE );
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
		$index	= new Iterator( $path, FALSE, TRUE );
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
		$index	= new Iterator( $path, TRUE, FALSE, FALSE );
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
		$index	= new Iterator( $path, FALSE, TRUE, FALSE );
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
