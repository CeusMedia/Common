<?php
/**
 *	UnitTest for Request Header Field.
 *	@package		net.http.request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	UnitTest for Request Header Field.
 *	@package		net.http.request
 *	@uses			Net_HTTP_Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *
 */
class Test_Net_HTTP_Header_SectionTest extends Test_Case
{
	protected $section;

	public function setUp(): void
	{
		$this->section	= Net_HTTP_Header_Section::instantiate()
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
		$section	= new Net_HTTP_Header_Section();

		$section->addField( new Net_HTTP_Header_Field( 'expires', $expires1 ) );
		$assertion	= array(
			new Net_HTTP_Header_Field( 'expires', $expires1 ),
		);
		$this->assertEquals( $assertion, $section->getFields() );

		$section->addField( new Net_HTTP_Header_Field( 'expires', $expires2 ) );
		$assertion	= array(
			new Net_HTTP_Header_Field( 'expires', $expires1 ),
			new Net_HTTP_Header_Field( 'expires', $expires2 ),
		);
		$this->assertEquals( $assertion, $section->getFields() );

		$section->addField( new Net_HTTP_Header_Field( 'date', $date ) );
		$assertion	= array(
			new Net_HTTP_Header_Field( 'date', $date ),
			new Net_HTTP_Header_Field( 'expires', $expires1 ),
			new Net_HTTP_Header_Field( 'expires', $expires2 ),
		);
		$this->assertEquals( $assertion, $section->getFields() );
	}

	public function testAddFieldPair()
	{
		$expires1	= time() + 60;
		$expires2	= time() + 120;
		$date		= date( 'r' );
		$section	= new Net_HTTP_Header_Section();

		$section->addFieldPair( 'expires', $expires1 );
		$assertion	= array(
			new Net_HTTP_Header_Field( 'expires', $expires1 ),
		);
		$this->assertEquals( $assertion, $section->getFields() );

		$section->addFieldPair( 'expires', $expires2 );
		$assertion	= array(
			new Net_HTTP_Header_Field( 'expires', $expires1 ),
			new Net_HTTP_Header_Field( 'expires', $expires2 ),
		);
		$this->assertEquals( $assertion, $section->getFields() );

		$section->addFieldPair( 'date', $date );
		$assertion	= array(
			new Net_HTTP_Header_Field( 'date', $date ),
			new Net_HTTP_Header_Field( 'expires', $expires1 ),
			new Net_HTTP_Header_Field( 'expires', $expires2 ),
		);
		$this->assertEquals( $assertion, $section->getFields() );
	}

	public function testAddFields()
	{
		$expires1	= time() + 60;
		$expires2	= time() + 120;
		$date1		= date( 'r' );
		$date2		= date( 'c' );
		$section	= new Net_HTTP_Header_Section();

		$section->addFields( array(
			new Net_HTTP_Header_Field( 'expires', $expires1 ),
			new Net_HTTP_Header_Field( 'date', $date1 ),
		) );
		$assertion	= array(
			new Net_HTTP_Header_Field( 'date', $date1 ),
			new Net_HTTP_Header_Field( 'expires', $expires1 ),
		);
		$this->assertEquals( $assertion, $section->getFields() );

		$section->addFields( array(
			new Net_HTTP_Header_Field( 'expires', $expires2 ),
			new Net_HTTP_Header_Field( 'date', $date2 ),
		) );
		$assertion	= array(
			new Net_HTTP_Header_Field( 'date', $date1 ),
			new Net_HTTP_Header_Field( 'date', $date2 ),
			new Net_HTTP_Header_Field( 'expires', $expires1 ),
			new Net_HTTP_Header_Field( 'expires', $expires2 ),
		);
		$this->assertEquals( $assertion, $section->getFields() );
	}

	public function testGetField()
	{
		$expires	= time() + 60;
		$section	= new Net_HTTP_Header_Section();
		$section->addFieldPair( 'expires', $expires );
		$section->addFieldPair( 'key', 'value' );

		$assertion	= array(
			new Net_HTTP_Header_Field( 'expires', $expires ),
			new Net_HTTP_Header_Field( 'key', 'value' ),
		);
		$creation	= $section->getFields();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetFields()
	{
		$expires	= time() + 60;
		$section	= new Net_HTTP_Header_Section();
		$section->addFieldPair( 'expires', $expires );
		$section->addFieldPair( 'key', 'value' );

		$assertion	= array(
			new Net_HTTP_Header_Field( 'expires', $expires ),
			new Net_HTTP_Header_Field( 'key', 'value' ),
		);
		$creation	= $section->getFields();
		$this->assertEquals( $assertion, $creation );
	}
}
