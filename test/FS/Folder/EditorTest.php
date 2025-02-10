<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Folder Editor.
 *	@package		Tests.FS.Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\Folder;

use CeusMedia\Common\FS\Folder\Editor;

/**
 *	TestUnit of Folder Editor.
 *	@package		Tests.FS.Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class EditorTest extends TestCase
{
	protected $editor;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->tearDown();
		parent::setUp();
		$this->editor	= new Editor( $this->folder );

	}

	public function tearDown(): void
	{
		if( file_exists( $this->path."copy" ) )
			$this->removeFolder( $this->path."copy", true);
		if( file_exists( $this->path."moved" ) )
			$this->removeFolder( $this->path."moved", true );
		if( file_exists( $this->path."renamed" ) )
			$this->removeFolder( $this->path."renamed", true );
		if( file_exists( $this->path."test" ) )
			$this->removeFolder( $this->path."test", true );
		if( file_exists( $this->path."remove" ) )
			$this->removeFolder( $this->path."remove", true );
		if( file_exists( $this->path."created" ) )
			$this->removeFolder( $this->path."created", true );
	}

	/**
	 *	Tests Method 'createFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreateFolder()
	{
		$creation	= Editor::createFolder( $this->path."created" );
		self::assertTrue( $creation );

		$creation	= Editor::createFolder( $this->path."created/sub1/sub1sub2" );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests Method 'copy'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopy()
	{
		$assertion	= 16;
		$creation	= $this->editor->copy( $this->path."copy" );
		self::assertEquals( $assertion, $creation );

		$assertion	= $this->folder;
		$creation	= $this->editor->getFolderName();
		self::assertEquals( $assertion, $creation );


		$this->removeFolder( $this->path."copy", TRUE );
		$assertion	= 31;
		$creation	= $this->editor->copy( $this->path."copy", FALSE, FALSE );
		self::assertEquals( $assertion, $creation );

		$assertion	= $this->folder;
		$creation	= $this->editor->getFolderName();
		self::assertEquals( $assertion, $creation );


		$this->removeFolder( $this->path."copy", TRUE );
		$assertion	= 16;
		$creation	= $this->editor->copy( $this->path."copy", FALSE, TRUE, TRUE );
		self::assertEquals( $assertion, $creation );

		$assertion	= $this->path."copy";
		$creation	= $this->editor->getFolderName();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'copyFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFolder()
	{
		$assertion	= 31;
		$creation	= Editor::copyFolder( $this->path."folder", $this->path."copy", FALSE, FALSE );
		self::assertEquals( $assertion, $creation );

		$assertion	= 21;
		$creation	= Editor::copyFolder( $this->path."folder", $this->path."copy", TRUE, FALSE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'copyFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFolderException()
	{
		$this->expectException( 'RuntimeException' );
		Editor::copyFolder( $this->path."folder", $this->path."copy" );
		Editor::copyFolder( $this->path."folder", $this->path."copy" );
	}

	/**
	 *	Tests Method 'move'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMove()
	{
		$this->editor->copy( $this->path."copy" );
		$editor	= new Editor( $this->path."copy" );

		$creation	= $editor->move( $this->path."moved", FALSE );
		self::assertTrue( $creation );

		$assertion	= $this->path."moved";
		$creation	= $editor->getFolderName();
		self::assertEquals( $assertion, $creation );


		$this->removeFolder( $this->path."moved", TRUE );
		$this->editor->copy( $this->path."copy" );
		$editor	= new Editor( $this->path."copy" );

		$creation	= $editor->move( $this->path."moved", TRUE );
		self::assertTrue( $creation );

		$assertion	= $this->path."moved";
		$creation	= $editor->getFolderName();
		self::assertEquals( $assertion, $creation );

		$this->removeFolder( $this->path."moved", TRUE );
		$this->editor->copy( $this->path."copy" );
		$editor	= new Editor( $this->path."copy" );

		$creation	= $editor->move( $this->path."copy", TRUE );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Method 'moveFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMoveFolder()
	{
		$this->editor->createFolder( $this->path."copy" );

		$creation	= Editor::moveFolder( $this->path."copy", $this->path."test" );
		self::assertTrue( $creation );

		$creation	= Editor::moveFolder( $this->path."test", $this->path."test" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Exception of Method 'moveFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMoveFolderException()
	{
		$this->editor->createFolder( $this->path."copy" );
		Editor::moveFolder( $this->path."copy", $this->path."test" );
		$this->editor->createFolder( $this->path."copy" );

		$this->expectException( 'RuntimeException' );
		Editor::moveFolder( $this->path."copy", $this->path."test" );
	}

	/**
	 *	Tests Method 'rename'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRename()
	{
		$this->editor->copy( $this->path."rename" );
		$editor	= new Editor( $this->path."rename" );

		$creation	= $editor->rename( $this->path."renamed" );
		self::assertTrue( $creation );
		self::assertFileExists( $this->path."renamed" );

		$assertion	= $this->path."renamed";
		$creation	= $editor->getFolderName();
		self::assertEquals( $assertion, $creation );

		$this->removeFolder( $this->path."renamed", TRUE );
		$this->editor->copy( $this->path."rename" );
		$editor	= new Editor( $this->path."rename" );

		$creation	= $editor->rename( "renamed" );
		self::assertTrue( $creation );
		self::assertFileExists( $this->path."renamed" );

		$assertion	= $this->path."renamed";
		$creation	= $editor->getFolderName();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'renameFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameFolder()
	{
		$this->editor->createFolder( $this->path."test1" );

		$creation	= Editor::renameFolder( $this->path."test1", "test2" );
		self::assertTrue( $creation );
		self::assertFileExists( $this->path."test2" );

		$creation	= Editor::renameFolder( $this->path."folder", $this->path."folder" );
		self::assertFalse( $creation );

		rmDir( $this->path."test2" );
	}

	/**
	 *	Tests Exception of Method 'renameFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameFolderException1()
	{
		$this->expectException( 'RuntimeException' );
		Editor::renameFolder( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'renameFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameFolderException2()
	{
		Editor::createFolder( $this->path."test" );
		Editor::createFolder( $this->path."renamed" );

		$this->expectException( 'RuntimeException' );
		Editor::renameFolder( $this->path."test", "renamed" );
	}

	/**
	 *	Tests Exception of Method 'renameFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameFolderException()
	{
		$this->expectException( 'RuntimeException' );
		Editor::renameFolder( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		Editor::copyFolder( $this->path."folder", $this->path."remove" );

		$editor		= new Editor( $this->path."remove" );

		$assertion	= 16;
		$creation	= $editor->remove( TRUE );
		self::assertEquals( $assertion, $creation );
		self::assertFileDoesNotExist( $this->path."remove" );
	}

	/**
	 *	Tests Method 'removeFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveFolder()
	{
		$this->editor->copyFolder( $this->path."folder", $this->path."remove" );
		self::assertTrue( file_exists( $this->path."remove" ) );

		$assertion	= 16;
		$creation	= Editor::removeFolder( $this->path."remove", TRUE );
		self::assertEquals( $assertion, $creation );
		self::assertFileDoesNotExist( $this->path."remove" );
	}

	/**
	 *	Tests Exception of Method 'removeFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveFolderException()
	{
		$this->editor->copyFolder( $this->path."folder", $this->path."remove" );

		$this->expectException( 'RuntimeException' );
		Editor::removeFolder( $this->path."remove" );
	}
}
