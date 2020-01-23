<?php
/**
 *	TestUnit of FS_File_INI_Editor.
 *	@package		Tests.{classPackage}
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of FS_File_INI_Editor.
 *	@package		Tests.{classPackage}
 *	@extends		Test_Case
 *	@uses			FS_File_INI_Editor
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_FS_File_INI_EditorTest extends Test_Case
{
	protected $fileList;
	protected $fileSections;

	/**	@var		FS_File_INI_Editor		$list			Editor for INI file without sections */
	protected $list;

	/**	@var		FS_File_INI_Editor		$sections		Editor for INI file with sections */
	protected $sections;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path	= dirname( __FILE__ )."/";
		$this->fileList		= $this->path."editor.list.ini";
		$this->fileSections	= $this->path."editor.sections.ini";

		copy( $this->path."reader.ini", $this->fileList );
		copy( $this->path."reader.ini", $this->fileSections );
		$this->list		= new FS_File_INI_Editor( $this->fileList, FALSE );
		$this->sections	= new FS_File_INI_Editor( $this->fileSections, TRUE );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		@unlink( $this->fileList );
		@unlink( $this->fileSections );
	}

	/**
	 *	At first test protected write method.
	 *	Check that the write result of an "unchanged" data set still looks the same.
	 *	The idempotency of the write method is elementary for all other tests.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteIdempotency()
	{
		$assertion	= FS_File_Reader::load( $this->fileList );
		$this->list->setProperty( "key1", "value1", "not_important" );
		$creation	= FS_File_Reader::load( $this->fileList );

		$assertion	= FS_File_Reader::load( $this->fileSections );
		$this->sections->setProperty( "key1", "value1", "section1" );
		$creation	= FS_File_Reader::load( $this->fileSections );
	}

	/**
	 *	Tests Method 'activateProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testActivateProperty()
	{
		$assertion	= TRUE;
		$creation	= $this->list->activateProperty( 'key5' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= in_array( 'key5', array_keys( $this->list->getProperties( TRUE ) ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->activateProperty( 'key5', 'section2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= in_array( 'key5', array_keys( $this->sections->getProperties( TRUE, 'section2' ) ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'activateProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testActivatePropertyException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->list->activateProperty( 'invalid_key' );
	}

	/**
	 *	Tests Exception of Method 'activateProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testActivatePropertyException2()
	{
		$this->expectException( 'LogicException' );
		$this->list->activateProperty( 'key1' );
	}

	/**
	 *	Tests Exception of Method 'activateProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testActivatePropertyException3()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->sections->activateProperty( 'invalid_key', 'section1' );
	}

	/**
	 *	Tests Exception of Method 'activateProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testActivatePropertyException4()
	{
		$this->expectException( 'LogicException' );
		$this->sections->activateProperty( 'key1', 'section1' );
	}

	/**
	 *	Tests Method 'addProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddProperty()
	{
		$assertion	= TRUE;
		$creation	= $this->list->addProperty( 'key6', "new_value6" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= in_array( 'key6', array_keys( $this->list->getProperties( TRUE ) ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->addProperty( 'key6', "new_value6", NULL, TRUE, 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= in_array( 'key6', array_keys( $this->sections->getProperties( TRUE, 'section1' ) ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->addProperty( 'key7', "new_value7", 'section2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= in_array( 'key7', array_keys( $this->sections->getProperties( TRUE, 'section2' ) ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddSection()
	{
		$assertion	= TRUE;
		$creation	= $this->sections->addSection( 'test_section' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->hasSection( 'test_section' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'addSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddSectionException()
	{
		$this->expectException( 'RuntimeException' );
		$this->list->addSection( "not_relevant" );
	}

	/**
	 *	Tests Method 'deactivateProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeactivateProperty()
	{
		$assertion	= TRUE;
		$creation	= $this->list->deactivateProperty( 'key2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->list->isActiveProperty( 'key2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->deactivateProperty( 'key3', 'section2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->sections->isActiveProperty( 'key3', 'section2' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'deactivateProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeactivatePropertyException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->list->deactivateProperty( 'invalid_key' );
	}

	/**
	 *	Tests Exception of Method 'deactivateProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeactivatePropertyException2()
	{
		$this->expectException( 'LogicException' );
		$this->list->deactivateProperty( 'key5' );
	}

	/**
	 *	Tests Exception of Method 'deactivateProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeactivatePropertyException3()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->sections->deactivateProperty( 'invalid_key', 'section2' );
	}

	/**
	 *	Tests Exception of Method 'deactivateProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeactivatePropertyException4()
	{
		$this->expectException( 'LogicException' );
		$this->sections->deactivateProperty( 'key5', 'section2' );
	}

	/**
	 *	Tests Method 'deleteProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeleteProperty()
	{
		$assertion	= TRUE;
		$creation	= $this->list->deleteProperty( 'key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->list->hasProperty( 'key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->deleteProperty( 'key3', 'section2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->sections->hasProperty( 'key3', 'section2' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'deleteProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeletePropertyException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->list->deleteProperty( 'invalid_key' );
	}

	/**
	 *	Tests Exception of Method 'deleteProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeletePropertyException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->sections->deleteProperty( 'invalid_key', 'section2' );
	}

	/**
	 *	Tests Method 'renameProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameProperty()
	{
		$assertion	= TRUE;
		$creation	= $this->list->renameProperty( 'key2', 'key2renamed' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->list->hasProperty( 'key2renamed' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->list->hasProperty( 'key2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->renameProperty( 'key3', 'key3renamed', 'section2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->hasProperty( 'key3renamed', 'section2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->sections->hasProperty( 'key3', 'section2' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'renameProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenamePropertyException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->list->renameProperty( 'invalid_key', "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'renameProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenamePropertyException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->sections->renameProperty( 'invalid_key', "not_relevant", 'section1' );
	}

	/**
	 *	Tests Method 'renameSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameSection()
	{
		$assertion	= TRUE;
		$creation	= $this->sections->renameSection( 'section2', 'section2renamed' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->hasSection( 'section2renamed' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->sections->hasSection( 'section2' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'renameSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameSectionException()
	{
		$this->expectException( 'RuntimeException' );
		$this->list->renameSection( "not_relevant", "not_relevant" );
	}

	/**
	 *	Tests Method 'removeSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveSection()
	{
		$assertion	= TRUE;
		$creation	= $this->sections->removeSection( 'section2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->sections->hasSection( 'section2' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'removeSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveSectionException1()
	{
		$this->expectException( 'RuntimeException' );
		$this->list->removeSection( "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'removeSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveSectionException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->sections->removeSection( 'invalid_section' );
	}

	/**
	 *	Tests Method 'setComment'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetComment()
	{
		$assertion	= TRUE;
		$creation	= $this->list->setComment( 'key1', "new comment of key 1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "new comment of key 1";
		$creation	= $this->list->getComment( 'key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->setComment( 'key3', "new comment of key 3", 'section2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "new comment of key 3";
		$creation	= $this->sections->getComment( 'key3', 'section2' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setComment'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetCommentException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->list->setComment( 'invalid_key', "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'setComment'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetCommentException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->sections->setComment( 'invalid_key', "not_relevant", 'section1' );
	}

	/**
	 *	Tests list mode of method 'setProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetProperty_List()
	{
		$assertion	= TRUE;
		$creation	= $this->list->setProperty( 'key2', "new_value_of KEY 2" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "new_value_of KEY 2";
		$creation	= $this->list->getProperty( 'key2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->list->setProperty( 'key2', "new_value_of KEY '2'" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "new_value_of KEY '2'";
		$creation	= $this->list->getProperty( 'key2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->list->setProperty( 'key_x', "new_value_of KEY 'x'" );
		$this->assertEquals( $assertion, $creation );

#remark( );

		$assertion	= "new_value_of KEY 'x'";
		$creation	= $this->list->getProperty( 'key_x' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests section mode of method 'setProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetProperty_Section()
	{

		$assertion	= TRUE;
		$creation	= $this->sections->setProperty( 'key3', "new_value_of KEY 3", 'section2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "new_value_of KEY 3";
		$creation	= $this->sections->getProperty( 'key3', 'section2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->setProperty( 'key_x', "new_value_of KEY 'x'", 'section2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "new_value_of KEY 'x'";
		$creation	= $this->sections->getProperty( 'key_x', 'section2' );
		$this->assertEquals( $assertion, $creation );
	}
}
