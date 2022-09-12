<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of ADT\\String.
 *	@package		Tests.adt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\ADT;

use CeusMedia\Common\ADT\String_;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Test_ADT_String.
 *	@package		Tests.adt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class StringTest extends BaseCase
{
	protected $string;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->string	= new String_( "some content" );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$string		= new String_( "construct" );
		$assertion	= "construct";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'capitalize'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCapitalize_Default()
	{
		$assertion	= "Some content";
		$creation	= $this->string->capitalize();
		$this->assertEquals( $assertion, (string) $this->string );
		$this->assertEquals( TRUE, $creation );
	}

	/**
	 *	Tests Method 'capitalize'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCapitalize_Default_NoChange()
	{
		$string		= new String_( "Some Content" );
		$assertion	= "Some Content";
		$creation	= $string->capitalize();
		$this->assertEquals( $assertion, (string) $string );
		$this->assertEquals( FALSE, $creation );
	}

	/**
	 *	Tests Method 'capitalize'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCapitalize_DefaultLimiter()
	{
		$assertion	= "Some Content";
		$creation	= $this->string->capitalize( " " );
		$this->assertEquals( $assertion, (string) $this->string );
		$this->assertEquals( TRUE, $creation );
	}

	/**
	 *	Tests Method 'capitalize'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCapitalize_Delimiter1()
	{
		$string		= new String_( "some-content" );
		$assertion	= "Some-Content";
		$creation	= $string->capitalize( "-" );
		$this->assertEquals( $assertion, (string) $string );
		$this->assertEquals( TRUE, $creation );

		$string		= new String_( "some-content" );
		$assertion	= "Some-content";
		$creation	= $string->capitalize( "#" );
		$this->assertEquals( $assertion, (string) $string );
		$this->assertEquals( TRUE, $creation );
	}

	/**
	 *	Tests Method 'capitalize'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCapitalize_Delimiter2()
	{
		$string		= new String_( "some-content some-how" );
		$assertion	= "Some-Content some-How";
		$creation	= $string->capitalize( "-" );
		$this->assertEquals( $assertion, (string) $string );
		$this->assertEquals( TRUE, $creation );

		$string		= new String_( "some-content some-how" );
		$assertion	= "Some-content some-how";
		$creation	= $string->capitalize( "#" );
		$this->assertEquals( $assertion, (string) $string );
		$this->assertEquals( TRUE, $creation );
	}

	/**
	 *	Tests Method 'capitalizeWords'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCapitalizeWords_Default1()
	{
		$assertion	= "Some Content";
		$creation	= $this->string->capitalizeWords();
		$this->assertEquals( $assertion, (string) $this->string );
		$this->assertEquals( TRUE, $creation );
	}

	/**
	 *	Tests Method 'capitalizeWords'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCapitalizeWords_Default2()
	{
		$assertion	= "Some Content";
		$creation	= $this->string->capitalizeWords( " " );
		$this->assertEquals( $assertion, (string) $this->string );
		$this->assertEquals( TRUE, $creation );
	}

	/**
	 *	Tests Method 'capitalizeWords'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCapitalizeWords_Default3()
	{
		$string		= new String_( "some  content" );
		$assertion	= "Some  Content";
		$creation	= $string->capitalizeWords( " " );
		$this->assertEquals( $assertion, (string) $string );
		$this->assertEquals( TRUE, $creation );
	}

	/**
	 *	Tests Method 'capitalizeWords'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCapitalizeWords_NoChange1()
	{
		$string		= new String_( "Some content" );
		$assertion	= "Some content";
		$creation	= $string->capitalizeWords( "-" );
		$this->assertEquals( $assertion, (string) $string );
		$this->assertEquals( FALSE, $creation );
	}

	/**
	 *	Tests Method 'capitalizeWords'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCapitalizeWords_NoChange2()
	{
		$string		= new String_( "Some-content" );
		$assertion	= "Some-content";
		$creation	= $string->capitalizeWords( " " );
		$this->assertEquals( $assertion, (string) $string );
		$this->assertEquals( FALSE, $creation );
	}

	/**
	 *	Tests Method 'count'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLength()
	{
		$assertion	= 12;
		$creation	= $this->string->getLength();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString()
	{
		$assertion	= "some content";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}
}
