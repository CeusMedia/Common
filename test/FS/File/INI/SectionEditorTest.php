<?php
/**
 *	TestUnit of Section INI Editor.
 *	@package		Tests.file.ini
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Section INI Reader.
 *	@package		Tests.file.ini
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class Test_FS_File_INI_SectionEditorTest extends Test_Case
{
	/**	@var	string		$fileName		File Name of Test File */
	private $fileName;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->fileName		= dirname( __FILE__ )."/section.editor.ini";
		$path	= dirname( $this->fileName )."/";
		copy( $path."section.reader.ini", $path."section.editor.ini" );
		$this->editor	= new FS_File_INI_SectionEditor( $this->fileName );
	}

	/**
	 *	Tests Method 'addSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddSection()
	{
		$assertion	= TRUE;
		$creation	= $this->editor->addSection( 'section3' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= in_array( 'section3', $this->editor->getSections() );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'addSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddSectionException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$creation	= $this->editor->addSection( 'section1' );
	}

	/**
	 *	Tests Method 'setProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetProperty()
	{
		$assertion	= TRUE;
		$creation	= $this->editor->setProperty( 'section1', 'key_new', 'value_new' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->editor->hasProperty( 'section1', 'key_new' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'value_new';
		$creation	= $this->editor->getProperty( 'section1', 'key_new' );
		$this->assertEquals( $assertion, $creation );


		$assertion	= TRUE;
		$creation	= $this->editor->setProperty( 'section4', 'key41', 'value41' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= array_key_exists( 'key41', $this->editor->getProperties( 'section4' ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'value41';
		$creation	= $this->editor->getProperty( 'section4', 'key41' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'removeProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveProperty()
	{
		$assertion	= TRUE;
		$creation	= $this->editor->removeProperty( 'section1', 'key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->editor->hasProperty( 'section1', 'key1' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'removeProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemovePropertyException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->editor->removeProperty( 'invalid_section', 'not_relevant' );
	}

	/**
	 *	Tests Exception of Method 'removeProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemovePropertyException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->editor->removeProperty( 'section1', 'invalid_key' );
	}

	/**
	 *	Tests Method 'removeSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveSection()
	{
		$assertion	= TRUE;
		$creation	= $this->editor->removeSection( 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->editor->hasSection( 'section1' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'removeSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveSectionException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->editor->removeSection( 'invalid_section' );
	}
}
