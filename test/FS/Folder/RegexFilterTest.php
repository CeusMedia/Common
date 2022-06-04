<?php
declare( strict_types = 1 );
/**
 *	TestUnit of RegexFilter for Folders.
 *	@package		Tests.FS.Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\FS\Folder;

use CeusMedia\Common\FS\Folder\RegexFilter;
use CeusMedia\Common\Test\FS\Folder\TestCase;

/**
 *	TestUnit of RegexFilter for Folders.
 *	@package		Tests.FS.Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class RegexFilterTest extends TestCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		parent::setUp();
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
		$index		= new RegexFilter( $path, "@.*@" );
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
		$index	= new RegexFilter( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructTextFilesOnly()
	{
		$path		= $this->path."folder";
		$index		= new RegexFilter( $path, "@\.txt$@", TRUE, FALSE );
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
		$index		= new RegexFilter( $path, "@file@", TRUE, FALSE );
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
		$index		= new RegexFilter( $path, "@\.php$@", TRUE, FALSE );
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
		$index		= new RegexFilter( $path, "@.*@", FALSE, TRUE );
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
		$index		= new RegexFilter( $path, "@^sub@", FALSE, TRUE );
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
		$index		= new RegexFilter( $path, "@.*@", FALSE, TRUE, FALSE );
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
