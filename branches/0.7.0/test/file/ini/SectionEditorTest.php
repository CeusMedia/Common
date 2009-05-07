<?php
/**
 *	TestUnit of Section INI Editor.
 *	@package		Tests.file.ini
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_INI_SectionEditor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.file.ini.SectionEditor' );
/**
 *	TestUnit of Section INI Reader.
 *	@package		Tests.file.ini
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_INI_SectionEditor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class File_INI_SectionEditorTest extends PHPUnit_Framework_TestCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName		= "file/ini/section.editor.ini";


	public function __construct()
	{
		$this->setUp();
		$this->editor	= new File_INI_SectionEditor( $this->fileName );
	}
	
	public function setUp()
	{
		$path	= dirname( $this->fileName )."/";
		copy( $path."section.reader.ini", $path."section.editor.ini" );
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
		$this->setExpectedException( 'InvalidArgumentException' );	
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
		$this->setExpectedException( 'InvalidArgumentException' );	
		$this->editor->removeProperty( 'invalid_section', 'not_relevant' );
	}

	/**
	 *	Tests Exception of Method 'removeProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemovePropertyException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );	
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
		$this->setExpectedException( 'InvalidArgumentException' );	
		$this->editor->removeSection( 'invalid_section' );
	}
}
?>