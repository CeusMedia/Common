<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Collection Editor
 *	@package		Tests.FS.File.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\Collection;

use CeusMedia\Common\FS\File\Collection\Editor;
use CeusMedia\Common\FS\File\Collection\Writer;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Collection Editor.
 *	@package		Tests.FS.File.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class EditorTest extends BaseCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private string $fileName;

	private Editor $editor;

	/**
	 *	Tests Method 'add'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAdd(): void
	{
		$assertion	= TRUE;
		$creation	= $this->editor->add( 'line3' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line1", "line2", "line3" );
		$creation	= Editor::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->editor->add( 'line4' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line1", "line2", "line3", "line4" );
		$creation	= Editor::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'add'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddException(): void
	{
		$this->expectException( 'DomainException' );
		$this->editor->add( 'line1' );
	}

	/**
	 *	Tests Method 'edit'.
	 *	@access		public
	 *	@return		void
	 */
	public function testEdit(): void
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
	public function testEditException(): void
	{
		$this->expectException( 'DomainException' );
		$this->editor->edit( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Method 'getList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetList(): void
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
	public function testRemove(): void
	{
		$assertion	= TRUE;
		$creation	= $this->editor->remove( 'line1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line2" );
		$creation	= Editor::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveException(): void
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
	public function testRemoveIndex(): void
	{
		$assertion	= TRUE;
		$creation	= $this->editor->removeIndex( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "line1" );
		$creation	= Editor::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$creation	= $this->editor->removeIndex( 0 );
		$this->assertEquals( 0, $creation );

		$assertion	= [];
		$creation	= Editor::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'removeIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveIndexException(): void
	{
		$this->expectException( 'DomainException' );
		$this->editor->removeIndex( 10 );
	}

	/**
	 *	Tests Method '__toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString(): void
	{
		$assertion	= "{line1, line2}";
		$creation	= "".$this->editor;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Set up for every Test.
	 *	@access		public
	 *	@return		void
	 */
	protected function setUp(): void
	{
		$this->fileName	= dirname( __FILE__ )."/edit.list";
		Writer::save( $this->fileName, array( "line1", "line2" ) );
		$this->editor	= new Editor( $this->fileName );
	}
}
