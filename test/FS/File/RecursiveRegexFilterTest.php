<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_RecursiveRegexFilter.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File;

use CeusMedia\Common\FS\File\RecursiveRegexFilter;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_NameFilter.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class RecursiveRegexFilterTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path	= dirname( __FILE__ )."/FilterTest/";
		$this->tearDown();
		mkDir( $this->path );
		mkDir( $this->path."nested/" );
		file_put_contents( $this->path."test1.test", "test1" );
		file_put_contents( $this->path."test2.test", "test2" );
		file_put_contents( $this->path."nested/test3.test", "test3" );
		file_put_contents( $this->path."nested/test4.test", "test4" );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		@unlink( $this->path."test1.test" );
		@unlink( $this->path."test2.test" );
		@unlink( $this->path."nested/test3.test" );
		@unlink( $this->path."nested/test4.test" );
		@rmDir( $this->path."nested/" );
		@rmDir( $this->path );
	}

	/**
	 *	Tests Exception of Method 'construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException()
	{
		$this->expectException( 'RuntimeException' );
		$index	= new RecursiveRegexFilter( "not_existing", "@not_relevant@" );
	}

	/**
	 *	Tests Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAccept()
	{
		$search	= "@^test@";
		$filter	= new RecursiveRegexFilter( $this->path, $search );

		$files	= [];
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		sort( $files );
		$assertion	= array(
			"test1.test",
			"test2.test",
			"test3.test",
			"test4.test"
		);
		$creation	= $files;
		self::assertEquals( $assertion, $creation );


		$search	= "@^test1@";
		$filter	= new RecursiveRegexFilter( $this->path, $search );

		$files	= [];
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array( "test1.test" );
		$creation	= $files;
		self::assertEquals( $assertion, $creation );


		$search	= "@not_existing_file@";
		$filter	= new RecursiveRegexFilter( $this->path, $search );

		$files	= [];
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= [];
		$creation	= $files;
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAcceptContent()
	{
		$name	= "@^test@";
		$incode	= "@test2@";
		$filter	= new RecursiveRegexFilter( $this->path, $name, $incode );

		$files	= [];
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array( "test2.test" );
		$creation	= $files;
		self::assertEquals( $assertion, $creation );



		$incode	= "@test@";
		$filter	= new RecursiveRegexFilter( $this->path, $name, $incode );

		$files	= [];
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		sort( $files );
		$assertion	= array( "test1.test",  "test2.test",  "test3.test",  "test4.test" );
		$creation	= $files;
		self::assertEquals( $assertion, $creation );




		$incode	= "@test5@";
		$filter	= new RecursiveRegexFilter( $this->path, $name, $incode );

		$files	= [];
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= [];
		$creation	= $files;
		self::assertEquals( $assertion, $creation );
	}
}
