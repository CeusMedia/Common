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
		$this->assertEquals( $assertion, $section->getFields() );

		$section->addField( new Field( 'expires', $expires2 ) );
		$assertion	= array(
			new Field( 'expires', $expires1 ),
			new Field( 'expires', $expires2 ),
		);
		$this->assertEquals( $assertion, $section->getFields() );

		$section->addField( new Field( 'date', $date ) );
		$assertion	= array(
			new Field( 'date', $date ),
			new Field( 'expires', $expires1 ),
			new Field( 'expires', $expires2 ),
		);
		$this->assertEquals( $assertion, $section->getFields() );
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
		$this->assertEquals( $assertion, $section->getFields() );

		$section->addFieldPair( 'expires', $expires2 );
		$assertion	= array(
			new Field( 'expires', $expires1 ),
			new Field( 'expires', $expires2 ),
		);
		$this->assertEquals( $assertion, $section->getFields() );

		$section->addFieldPair( 'date', $date );
		$assertion	= array(
			new Field( 'date', $date ),
			new Field( 'expires', $expires1 ),
			new Field( 'expires', $expires2 ),
		);
		$this->assertEquals( $assertion, $section->getFields() );
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
			new Field( 'date', $date1 ),
			new Field( 'expires', $expires1 ),
		);
		$this->assertEquals( $assertion, $section->getFields() );

		$section->addFields( array(
			new Field( 'expires', $expires2 ),
			new Field( 'date', $date2 ),
		) );
		$assertion	= array(
			new Field( 'date', $date1 ),
			new Field( 'date', $date2 ),
			new Field( 'expires', $expires1 ),
			new Field( 'expires', $expires2 ),
		);
		$this->assertEquals( $assertion, $section->getFields() );
	}

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
		$creation	= $section->getFields();
		$this->assertEquals( $assertion, $creation );
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
		$this->assertEquals( $assertion, $creation );
	}
}
