<?php
/**
 *	TestUnit of Folder Editor.
 *	@package		Tests.folder
 *	@extends		Folder_TestCase
 *	@uses			Folder_Editor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
require_once( 'folder/TestCase.php' );
import( 'de.ceus-media.folder.Editor' );
/**
 *	TestUnit of Folder Editor.
 *	@package		Tests.folder
 *	@extends		Folder_TestCase
 *	@uses			Folder_Editor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.04.2008
 *	@version		0.1
 */
class Folder_EditorTest extends Folder_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->path		= dirname( __FILE__ )."/";
		$this->path		= "folder/";
		$this->folder	= $this->path."folder";
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->editor	= new Folder_Editor( $this->folder );
		$this->tearDown();
	}
	
	public function tearDown()
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
		$assertion	= TRUE;
		$creation	= Folder_Editor::createFolder( $this->path."created" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= Folder_Editor::createFolder( $this->path."created/sub1/sub1sub2" );
		$this->assertEquals( $assertion, $creation );
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
		$this->assertEquals( $assertion, $creation );

		$assertion	= $this->folder;
		$creation	= $this->editor->getFolderName();
		$this->assertEquals( $assertion, $creation );


		$this->removeFolder( $this->path."copy", TRUE );
		$assertion	= 31;
		$creation	= $this->editor->copy( $this->path."copy", FALSE, FALSE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $this->folder;
		$creation	= $this->editor->getFolderName();
		$this->assertEquals( $assertion, $creation );


		$this->removeFolder( $this->path."copy", TRUE );
		$assertion	= 16;
		$creation	= $this->editor->copy( $this->path."copy", FALSE, TRUE, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $this->path."copy";
		$creation	= $this->editor->getFolderName();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'copyFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFolder()
	{
		$assertion	= 31;
		$creation	= Folder_Editor::copyFolder( $this->path."folder", $this->path."copy", FALSE, FALSE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 21;
		$creation	= Folder_Editor::copyFolder( $this->path."folder", $this->path."copy", TRUE, FALSE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'copyFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFolderException()
	{
		$this->setExpectedException( 'RuntimeException' );
		Folder_Editor::copyFolder( $this->path."folder", $this->path."copy" );
		Folder_Editor::copyFolder( $this->path."folder", $this->path."copy" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'move'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMove()
	{
		$this->editor->copy( $this->path."copy" );
		$editor	= new Folder_Editor( $this->path."copy" );

		$assertion	= TRUE;
		$creation	= $editor->move( $this->path."moved", FALSE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $this->path."moved";
		$creation	= $editor->getFolderName();
		$this->assertEquals( $assertion, $creation );


		$this->removeFolder( $this->path."moved", TRUE );
		$this->editor->copy( $this->path."copy" );
		$editor	= new Folder_Editor( $this->path."copy" );

		$assertion	= TRUE;
		$creation	= $editor->move( $this->path."moved", TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $this->path."moved";
		$creation	= $editor->getFolderName();
		$this->assertEquals( $assertion, $creation );


		$this->removeFolder( $this->path."moved", TRUE );
		$this->editor->copy( $this->path."copy" );
		$editor	= new Folder_Editor( $this->path."copy" );

		$assertion	= FALSE;
		$creation	= $editor->move( $this->path."copy", TRUE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'moveFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMoveFolder()
	{
		$this->editor->createFolder( $this->path."copy" );

		$assertion	= TRUE;
		$creation	= Folder_Editor::moveFolder( $this->path."copy", $this->path."test" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= Folder_Editor::moveFolder( $this->path."test", $this->path."test" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'moveFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMoveFolderException()
	{
		$this->editor->createFolder( $this->path."copy" );
		Folder_Editor::moveFolder( $this->path."copy", $this->path."test" );
		$this->editor->createFolder( $this->path."copy" );

		$this->setExpectedException( 'RuntimeException' );
		Folder_Editor::moveFolder( $this->path."copy", $this->path."test" );
	}

	/**
	 *	Tests Method 'rename'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRename()
	{
		$this->editor->copy( $this->path."rename" );
		$editor	= new Folder_Editor( $this->path."rename" );
		
		$assertion	= TRUE;
		$creation	= $editor->rename( $this->path."renamed" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= TRUE;
		$creation	= file_exists( $this->path."renamed" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= $this->path."renamed";
		$creation	= $editor->getFolderName();
		$this->assertEquals( $assertion, $creation );


		$this->removeFolder( $this->path."renamed", TRUE );
		$this->editor->copy( $this->path."rename" );
		$editor	= new Folder_Editor( $this->path."rename" );
		
		$assertion	= TRUE;
		$creation	= $editor->rename( "renamed" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= TRUE;
		$creation	= file_exists( $this->path."renamed" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= $this->path."renamed";
		$creation	= $editor->getFolderName();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'renameFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameFolder()
	{
		$this->editor->createFolder( $this->path."test1" );

		$assertion	= TRUE;
		$creation	= Folder_Editor::renameFolder( $this->path."test1", "test2" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= TRUE;
		$creation	= file_exists( $this->path."test2" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= FALSE;
		$creation	= Folder_Editor::renameFolder( $this->path."folder", $this->path."folder" );
		$this->assertEquals( $assertion, $creation );

		rmDir( $this->path."test2" );
	}

	/**
	 *	Tests Exception of Method 'renameFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameFolderException1()
	{
		$this->setExpectedException( 'RuntimeException' );
		Folder_Editor::renameFolder( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'renameFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameFolderException2()
	{
		Folder_Editor::createFolder( $this->path."test" );
		Folder_Editor::createFolder( $this->path."renamed" );

		$this->setExpectedException( 'RuntimeException' );
		Folder_Editor::renameFolder( $this->path."test", "renamed" );
	}

	/**
	 *	Tests Exception of Method 'renameFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameFolderException()
	{
		$this->setExpectedException( 'RuntimeException' );
		Folder_Editor::renameFolder( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		Folder_Editor::copyFolder( $this->path."folder", $this->path."remove" );
		
		$editor	= new Folder_Editor( $this->path."remove" );

		$assertion	= 16;
		$creation	= $editor->remove( TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->path."remove" );
		$this->assertEquals( $assertion, $creation );
	}
		
	/**
	 *	Tests Method 'removeFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveFolder()
	{
		$this->editor->copyFolder( $this->path."folder", $this->path."remove" );

		$assertion	= TRUE;
		$creation	= file_exists( $this->path."remove" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 16;
		$creation	= Folder_Editor::removeFolder( $this->path."remove", TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->path."remove" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'removeFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveFolderException()
	{
		$this->editor->copyFolder( $this->path."folder", $this->path."remove" );
		
		$this->setExpectedException( 'RuntimeException' );
		Folder_Editor::removeFolder( $this->path."remove" );
	}
}
?>