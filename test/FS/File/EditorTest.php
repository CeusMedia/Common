<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_Editor.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File;

use CeusMedia\Common\FS\File\Editor;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_Editor.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class EditorTest extends BaseCase
{
	/**	@var	Editor		$editor			Instance of File Editor */
	private $editor			= NULL;
	/**	@var	string		$fileName		File Name of Test File */
	private $fileName		= "editor.test";
	/**	@var	string		$fileContent	Content of Test File */
	private $fileContent	= "line1\nline2\n";
	/**	@var	string		$path			Path to work in */
	private $path;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path.$this->fileName;
		file_put_contents( $this->fileName, $this->fileContent );
		$this->editor	= new Editor( $this->fileName );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		@unlink( $this->fileName );
		@unlink( $this->path."renamed.txt" );
	}

	/**
	 *	Tests Method 'setGroup'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDelete()
	{
		$assertion	= TRUE;
		$creation	= $this->editor->exists();
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= Editor::delete( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->editor->exists();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setGroup'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetGroup()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->editor->setGroup();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setOwner'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOwner()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->editor->setOwner();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setPermissions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPermissions()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->editor->setPermissions();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		$assertion	= TRUE;
		$creation	= $this->editor->exists();
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->editor->remove();
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->editor->exists();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'rename'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRename()
	{
		$fileName	= $this->path."renamed.txt";

		$assertion	= TRUE;
		$creation	= $this->editor->exists();
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->editor->rename( $fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $fileName;
		$creation	= $this->editor->getFileName();
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->editor->exists();
		$this->assertEquals( $assertion, $creation );
}

	/**
	 *	Tests Exception of Method 'rename'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameException2()
	{
		$this->expectException( 'RuntimeException' );
		$this->editor->rename( "not_existing_path/not_relevant.txt" );
	}

	/**
	 *	Tests Method 'writeArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteArray()
	{
		$lines		= array( "line3", "line4" );

		$assertion	= TRUE;
		$creation	= (bool) $this->editor->writeArray( $lines );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $lines;
		$creation	= $this->editor->readArray();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'writeString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteString()
	{
		$string		= "test string 123";

		$assertion	= TRUE;
		$creation	= (bool) $this->editor->writeString( $string );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $string;
		$creation	= $this->editor->readString();
		$this->assertEquals( $assertion, $creation );
	}
}
