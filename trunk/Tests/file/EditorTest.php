<?php
/**
 *	TestUnit of File_Editor.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Editor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.07.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.file.Editor' );
/**
 *	TestUnit of File_Editor.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Editor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.07.2008
 *	@version		0.1
 */
class Tests_File_EditorTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
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
		$creation	= File_Editor::__construct();
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
		$creation	= File_Editor::changeGroup();
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
		File_Editor::changeGroup();
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
		$creation	= File_Editor::changeMode();
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
		File_Editor::changeMode();
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
		File_Editor::changeMode();
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
		File_Editor::changeMode();
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
		$creation	= File_Editor::changeOwner();
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
		File_Editor::changeOwner();
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_Editor::remove();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'rename'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRename()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_Editor::rename();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'rename'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameException1()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->setExpectedException( 'InvalidArgumentException' );
		File_Editor::rename();
	}

	/**
	 *	Tests Exception of Method 'rename'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameException2()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->setExpectedException( 'RuntimeException' );
		File_Editor::rename();
	}

	/**
	 *	Tests Method 'writeArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteArray()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_Editor::writeArray();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'writeString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteString()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_Editor::writeString();
		$this->assertEquals( $assertion, $creation );
	}
}
?>