<?php
/**
 *	TestUnit of List Editor
 *	@package		Tests.file.list
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of List Editor.
 *	@package		Tests.file.list
 *	@extends		Test_Case
 *	@uses			FS_File_List_Editor
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
class Test_FS_File_List_EditorTest extends Test_Case
{
	/**	@var	string		$fileName		URL of Archive File Name */

	/**
	 *	Set up for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->fileName	= dirname( __FILE__ )."/edit.list";
		FS_File_List_Writer::save( $this->fileName, array( "line1", "line2" ) );
		$this->editor	= new FS_File_List_Editor( $this->fileName );
	}

	/**
	 *	Tests Method 'add'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAdd()
	{
		$assertion	= TRUE;
		$creation	= $this->editor->add( 'line3' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line1", "line2", "line3" );
		$creation	= FS_File_List_Editor::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->editor->add( 'line4' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line1", "line2", "line3", "line4" );
		$creation	= FS_File_List_Editor::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'add'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddException()
	{
		$this->expectException( 'DomainException' );
		$this->editor->add( 'line1' );
	}

	/**
	 *	Tests Method 'edit'.
	 *	@access		public
	 *	@return		void
	 */
	public function testEdit()
	{
		$assertion	= TRUE;
		$creation	= $this->editor->edit( "line2", "line3" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line1", "line3" );
		$creation	= $this->editor->getList();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'edit'.
	 *	@access		public
	 *	@return		void
	 */
	public function testEditException()
	{
		$this->expectException( 'DomainException' );
		$this->editor->edit( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Method 'getList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetList()
	{
		$assertion	= array(
			"line1",
			"line2",
		);
		$creation	= $this->editor->getList();
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
		$creation	= $this->editor->remove( 'line1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line2" );
		$creation	= FS_File_List_Editor::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveException()
	{
		$this->editor->remove( 'line1' );
		$this->expectException( 'DomainException' );
		$this->editor->remove( 'line1' );
	}

	/**
	 *	Tests Method 'removeIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveIndex()
	{
		$assertion	= TRUE;
		$creation	= $this->editor->removeIndex( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line1" );
		$creation	= FS_File_List_Editor::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->editor->removeIndex( 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= FS_File_List_Editor::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'removeIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveIndexException()
	{
		$this->expectException( 'DomainException' );
		$this->editor->removeIndex( 10 );
	}

	/**
	 *	Tests Method '__toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString()
	{
		$assertion	= "{line1, line2}";;
		$creation	= "".$this->editor;
		$this->assertEquals( $assertion, $creation );
	}
}
