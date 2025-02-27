<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	UnitTest for Request Header Field.
 *	@package		net.http.request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net\HTTP\Header;

use CeusMedia\Common\Net\HTTP\Header\Field;
use CeusMedia\Common\Net\HTTP\Header\Section;
use CeusMedia\CommonTest\BaseCase;

/**
 *	UnitTest for Request Header Field.
 *	@package		net.http.request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class SectionTest extends BaseCase
{
	protected $section;

	public function setUp(): void
	{
		$this->section	= Section::getInstance()
			->addFieldPair( 'expires', time() + 60 )
			->addFieldPair( 'key', 'value' )
			->addFieldPair( 'date', date( 'r' ) );
	}

	public function tearDown(): void
	{
		$this->section	= NULL;
	}

	public function testAddField()
	{
		$expires1	= time() + 60;
		$expires2	= time() + 120;
		$date		= date( 'r' );
		$section	= new Section();

		$section->addField( new Field( 'expires', $expires1 ) );
		$assertion	= array(
			new Field( 'expires', $expires1 ),
		);
		self::assertEquals( $assertion, $section->getFields() );

		$section->addField( new Field( 'expires', $expires2 ) );
		$assertion	= array(
			new Field( 'expires', $expires1 ),
			new Field( 'expires', $expires2 ),
		);
		self::assertEquals( $assertion, $section->getFields() );

		$section->addField( new Field( 'date', $date ) );
		$assertion	= array(
			new Field( 'expires', $expires1 ),
			new Field( 'expires', $expires2 ),
			new Field( 'date', $date ),
		);
		self::assertEquals( $assertion, $section->getFields() );
	}

	public function testAddFieldPair()
	{
		$expires1	= time() + 60;
		$expires2	= time() + 120;
		$date		= date( 'r' );
		$section	= new Section();

		$section->addFieldPair( 'expires', $expires1 );
		$assertion	= array(
			new Field( 'expires', $expires1 ),
		);
		self::assertEquals( $assertion, $section->getFields() );

		$section->addFieldPair( 'expires', $expires2 );
		$assertion	= array(
			new Field( 'expires', $expires1 ),
			new Field( 'expires', $expires2 ),
		);
		self::assertEquals( $assertion, $section->getFields() );

		$section->addFieldPair( 'date', $date );
		$assertion	= array(
			new Field( 'expires', $expires1 ),
			new Field( 'expires', $expires2 ),
			new Field( 'date', $date ),
		);
		self::assertEquals( $assertion, $section->getFields() );
	}

	public function testAddFields()
	{
		$expires1	= time() + 60;
		$expires2	= time() + 120;
		$date1		= date( 'r' );
		$date2		= date( 'c' );
		$section	= new Section();

		$section->addFields( array(
			new Field( 'expires', $expires1 ),
			new Field( 'date', $date1 ),
		) );
		$assertion	= array(
			new Field( 'expires', $expires1 ),
			new Field( 'date', $date1 ),
		);
		self::assertEquals( $assertion, $section->getFields() );

		$section->addFields( array(
			new Field( 'expires', $expires2 ),
			new Field( 'date', $date2 ),
		) );
		$assertion	= array(
			new Field( 'expires', $expires1 ),
			new Field( 'expires', $expires2 ),
			new Field( 'date', $date1 ),
			new Field( 'date', $date2 ),
		);
		self::assertEquals( $assertion, $section->getFields() );
	}

	/**
	 * @return void
	 * @covers	\CeusMedia\Common\Net\HTTP\Header\Section::getField
	 */
	public function testGetField()
	{
		$expires	= time() + 60;
		$section	= new Section();
		$section->addFieldPair( 'expires', $expires );
		$section->addFieldPair( 'key', 'value' );

		$assertion	= array(
			new Field( 'expires', $expires ),
			new Field( 'key', 'value' ),
		);
		$actual		= $section->getField( 'expires' );
		self::assertNotNull( $actual );
		self::assertEquals( $expires, $actual->getValue() );

		$actual		= $section->getField( 'key' );
		self::assertNotNull( $actual );
		self::assertEquals( 'value', $actual->getValue() );

		self::assertNull( $section->getField( 'invalid' ) );
	}

	public function testGetFieldsByName(): void
	{
		$actual	= $this->section->getFieldsByName( 'key' );

		self::assertIsArray( $actual );
		self::assertCount( 1, $actual );
		self::assertEquals( 'value', $actual[0]->getValue() );

		$actual	= $this->section->getFieldsByName( 'date' );
		self::assertIsArray( $actual );
		self::assertCount( 1, $actual );

		$actual	= $this->section->getFieldsByName( 'ExPiReS' );
		self::assertIsArray( $actual );
		self::assertCount( 1, $actual );
		self::assertTrue( $actual[0]->getValue() > time() );

		$actual	= $this->section->getFieldsByName( 'invalid' );
		self::assertIsArray( $actual );
		self::assertCount( 0, $actual );
	}

	public function testGetFields()
	{
		$expires	= time() + 60;
		$section	= new Section();
		$section->addFieldPair( 'expires', $expires );
		$section->addFieldPair( 'key', 'value' );

		$assertion	= array(
			new Field( 'expires', $expires ),
			new Field( 'key', 'value' ),
		);
		$creation	= $section->getFields();
		self::assertEquals( $assertion, $creation );
	}

	public function testGetSectionedFields()
	{
		$expected	= [
			'general'	=> [
				'date'		=> [new Field( 'date', date( 'r' ) )],
			],
			'entity'	=> [
				'expires'	=> [new Field( 'expires', time() + 60 )],
			],
			'others'	=> [
				'key'		=> [new Field( 'key', 'value' )],
			],
		];
		self::assertEquals( $expected, $this->section->getSectionedFields() );
	}

	public function testHasField()
	{
		self::assertTrue( $this->section->hasField( 'key' ) );
		self::assertTrue( $this->section->hasField( 'DATE' ) );
		self::assertTrue( $this->section->hasField( 'eXpIrEs' ) );
		self::assertFalse( $this->section->hasField( 'invalid' ) );
	}

	public function testRemoveByName(): void
	{
		$this->section->removeByName( 'date' );
		self::assertTrue( $this->section->hasField( 'key' ) );
		self::assertFalse( $this->section->hasField( 'DATE' ) );
		self::assertFalse( $this->section->hasField( 'date' ) );
		self::assertTrue( $this->section->hasField( 'eXpIrEs' ) );
		self::assertFalse( $this->section->hasField( 'invalid' ) );
	}
}
