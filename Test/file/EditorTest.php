<?php
/**
 *	TestUnit of File_Editor.
 *	@package		Tests.file
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.07.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of File_Editor.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Editor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.07.2008
 *	@version		0.1
 */
class Test_File_EditorTest extends PHPUnit_Framework_TestCase
{
	/**	@var	File_Editor	$editor			Instance of File Editor */
	private $editor			= NULL;
	/**	@var	string		$fileName		File Name of Test File */
	private $fileName		= "editor.test";
	/**	@var	string		$fileContent	Content of Test File */
	private $fileContent	= "line1\nline2\n";
	/**	@var	string		$path			Path to work in */
	private $path;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path.$this->fileName;
		$this->editor	= new File_Editor( $this->fileName );
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		file_put_contents( $this->fileName, $this->fileContent );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->fileName );
		@unlink( $this->path."renamed.txt" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->editor->__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'changeGroup'.
	 *	@access		public
	 *	@return		void
	 */
	public function testChangeGroup()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->editor->changeGroup();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'changeGroup'.
	 *	@access		public
	 *	@return		void
	 */
	public function testChangeGroupException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->editor->changeGroup();
	}

	/**
	 *	Tests Method 'changeMode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testChangeMode()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->editor->changeMode();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'changeMode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testChangeModeException1()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->editor->changeMode();
	}

	/**
	 *	Tests Exception of Method 'changeMode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testChangeModeException2()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->editor->changeMode();
	}

	/**
	 *	Tests Exception of Method 'changeMode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testChangeModeException3()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->editor->changeMode();
	}

	/**
	 *	Tests Method 'changeOwner'.
	 *	@access		public
	 *	@return		void
	 */
	public function testChangeOwner()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->editor->changeOwner();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'changeOwner'.
	 *	@access		public
	 *	@return		void
	 */
	public function testChangeOwnerException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->editor->changeOwner();
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
	public function testRenameException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->editor->rename( NULL );
	}

	/**
	 *	Tests Exception of Method 'rename'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameException2()
	{
		$this->setExpectedException( 'RuntimeException' );
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
?>