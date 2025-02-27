<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of ADT\Collection\SectionList.
 *	@package		Tests.adt.list
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\ADT\Collection;

use CeusMedia\Common\ADT\Collection\SectionList;
use CeusMedia\CommonTest\BaseCase;
use OutOfBoundsException;

/**
*	TestUnit of ADT\Collection\SectionList.
 *	@package		Tests.adt.list
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class SectionListTest extends BaseCase
{
	/**	@var	SectionList		$list		Instance of SectionList */
	private SectionList $list;

	/**
	 *	Tests Method 'addEntry'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddEntry()
	{
		$this->list->addEntry( 'entry13', 'section1' );
		$assertion	= 3;
		$creation	= $this->list->countEntries( 'section1' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'addEntry'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddEntryException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->list->addEntry( "entry11", "section1" );
	}

	/**
	 *	Tests Method 'addSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddSection()
	{
		$this->list->addSection( 'section3' );
		$assertion	= 3;
		$creation	= $this->list->countSections();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct(): void
	{
		$initial	= ['section1' => ['item 1'], 'section2' => ['item 2', 'item 3']];
		$list = new SectionList( $initial );
		self::assertEquals( $initial, $list->getList() );
	}

	/**
	 *	Tests Method 'clear'.
	 *	@access		public
	 *	@return		void
	 */
	public function testClear()
	{
		$this->list->clear();

		$assertion	= 0;
		$creation	= $this->list->countSections();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'countEntries'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCountEntries()
	{
		$assertion	= 2;
		$creation	= $this->list->countEntries( "section1" );
		self::assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= $this->list->countEntries( "section2" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'countSections'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCountSections()
	{
		$assertion	= 2;
		$creation	= $this->list->countSections();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntry'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntry()
	{
		$assertion	= "entry11";
		$creation	= $this->list->getEntry( 0, 'section1' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getEntry'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntryException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->list->getEntry( 9999, "section1" );
	}

	/**
	 *	Tests Method 'getEntries'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntries()
	{
		$assertion	= array( "entry11", "entry12" );
		$creation	= $this->list->getEntries( 'section1' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getEntries'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntriesException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->list->getEntries( "invalid" );
	}

	/**
	 *	Tests Method 'getIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIndex()
	{
		$assertion	= 1;
		$creation	= $this->list->getIndex( "entry12" );
		self::assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->list->getIndex( "entry12", "section1" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIndex_OutOfBoundsException()
	{
		$this->expectException( OutOfBoundsException::class );
		$this->list->getIndex( "entry12", "invalid_section" );
	}

	/**
	 *	Tests Method 'getList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetList()
	{
		$assertion	= array(
			"section1"	=> array(
				"entry11",
				"entry12",
			),
			"section2"	=> array(
				"entry21",
				"entry22",
				"entry23",
			)
		);
		$creation	= $this->list->getList();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSectionOfEntry'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSectionOfEntry()
	{
		$assertion	= "section2";
		$creation	= $this->list->getSectionOfEntry( "entry21" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getSectionOfEntry'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSectionOfEntryException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->list->getSectionOfEntry( "invalid" );
	}

	/**
	 *	Tests Method 'getSections'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSections()
	{
		$assertion	= array( "section1", "section2" );
		$creation	= $this->list->getSections();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'removeEntry'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveEntry()
	{
		$this->list->removeEntry( "entry11", "section1" );
		$assertion	= array( "entry12" );
		$creation	= $this->list->getEntries( "section1" );
		self::assertEquals( $assertion, $creation );

		$this->list->removeEntry( "entry12" );
		$assertion	= [];
		$creation	= $this->list->getEntries( "section1" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'removeEntry'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveEntryException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->list->removeEntry( "invalid", "section1" );
	}

	/**
	 *	Tests Method 'removeSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveSection()
	{
		$this->list->removeSection( "section1" );
		$assertion	= array( "section2" );
		$creation	= $this->list->getSections();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'removeSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveSectionException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->list->removeSection( "invalid" );
	}

	/**
	 *	Setup for every Test.
	 *	@return		void
	 */
	protected function setUp(): void
	{
		$this->list	= new SectionList();
		$this->list->addEntry( 'entry11', 'section1' );
		$this->list->addEntry( 'entry12', 'section1' );
		$this->list->addEntry( 'entry21', 'section2' );
		$this->list->addEntry( 'entry22', 'section2' );
		$this->list->addEntry( 'entry23', 'section2' );
	}

	/**
	 *	Cleanup after every Test.
	 *	@return		void
	 */
	protected function tearDown(): void
	{
	}
}
