<?php
/**
 *	TestUnit of SectionList
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_List_SectionList
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
import( 'de.ceus-media.adt.list.SectionList' );
/**
 *	TestUnit of SectionList
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_List_SectionList
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_ADT_List_SectionListTest extends PHPUnit_Framework_TestCase
{
	/**	@var	array		$list		Instance of SectionList */
	private $list;
	
	public function setUp()
	{
		$this->list	= new ADT_List_SectionList();
		$this->list->addEntry( 'entry11', 'section1' );
		$this->list->addEntry( 'entry12', 'section1' );
		$this->list->addEntry( 'entry21', 'section2' );
		$this->list->addEntry( 'entry22', 'section2' );
		$this->list->addEntry( 'entry23', 'section2' );
	}
	
	public function testAddEntry()
	{
		$this->list->addEntry( 'entry13', 'section1' );
		$assertion	= 3;
		$creation	= $this->list->getSectionSize( 'section1' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testAddSection()
	{
		$this->list->addSection( 'section3' );
		$assertion	= 3;
		$creation	= $this->list->getSectionsSize();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetEntry()
	{
		$assertion	= "entry11";
		$creation	= $this->list->getEntry( 0, 'section1' );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testGetEntries()
	{
		$assertion	= array( "entry11", "entry12" );
		$creation	= $this->list->getEntries( 'section1' );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testGetSectionOfEntry()
	{
		$assertion	= "section2";
		$creation	= $this->list->getSectionOfEntry( "entry21" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetSections()
	{
		$assertion	= array( "section1", "section2" );
		$creation	= $this->list->getSections();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetSectionSize()
	{
		$assertion	= 2;
		$creation	= $this->list->getSectionSize( "section1" );
		$this->assertEquals( $assertion, $creation );
		$assertion	= 3;
		$creation	= $this->list->getSectionSize( "section2" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetSectionsSize()
	{
		$assertion	= 2;
		$creation	= $this->list->getSectionsSize();
		$this->assertEquals( $assertion, $creation );
	}

	public function testRemoveEntry()
	{
		$this->list->removeEntry( "entry11", "section1" );
		$assertion	= array( "entry12" );
		$creation	= $this->list->getEntries( "section1" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testRemoveSection()
	{
		$this->list->removeSection( "section1" );
		$assertion	= array( "section2" );
		$creation	= $this->list->getSections();
		$this->assertEquals( $assertion, $creation );
	}
}
?>