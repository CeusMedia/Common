<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Alg_Randomizer.
 *	@package		Tests.Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\Alg;

use CeusMedia\Common\Alg\Randomizer;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Alg_Randomizer.
 *	@package		Tests.Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class RandomizerTest extends BaseCase
{
	/** @var Randomizer $randomizer Randomizer instance for all tests */
	protected $randomizer;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->randomizer	= new Randomizer();
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet()
	{
		$this->randomizer->useSigns		= FALSE;
		$string		= $this->randomizer->get( 1 );

		$creation	= is_string( $string );
		$this->assertTrue( $creation );

		$assertion	= 1;
		$creation	= strlen( $string );
		$this->assertEquals( $assertion, $creation );

		$creation	= (bool) preg_match( "@^[a-zA-Z0-9]$@", $string );
		$this->assertTrue( $creation );

		$this->randomizer->useLarges	= FALSE;
		$this->randomizer->useDigits	= FALSE;
		$string		= $this->randomizer->get( 20 );

		$creation	= is_string( $string );
		$this->assertTrue( $creation );

		$assertion	= 20;
		$creation	= strlen( $string );
		$this->assertEquals( $assertion, $creation );

		$creation	= (bool) preg_match( "@^[a-z]{20}$@", $string );
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithStrength()
	{
		$strong		= $this->randomizer->get( 15, 30 );

		$this->assertIsString( $strong );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithUnique()
	{
		$this->randomizer->unique	= TRUE;
		$random		= $this->randomizer->get( 45 );
		$unique		= join( array_keys( array_flip( str_split( $random ) ) ) );
		$this->assertEquals( $unique, $random );;
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function test_get_withLargeLength()
	{
		$this->randomizer->unique	= FALSE;
		$string		= $this->randomizer->get( 240 );

		$this->assertIsString( $string );
		$this->assertEquals( 240, strlen( $string ) );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function test_get_fromString_expectTypeError()
	{
		$this->expectException( 'TypeError' );
		/** @noinspection PhpStrictTypeCheckingInspection */
		$this->randomizer->get( "not_an_integer" );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function test_get_fromInt_expectTypeError()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->randomizer->get( 0 );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLengthException3()
	{
		$this->randomizer->useSmalls	= FALSE;
		$this->randomizer->useLarges	= FALSE;
		$this->randomizer->useDigits	= FALSE;
		$this->randomizer->useSigns		= FALSE;
		$this->expectException( 'RuntimeException' );
		$this->randomizer->get( 1 );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLengthException4()
	{
		$this->expectException( 'UnderflowException' );
		$this->randomizer->get( 200 );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function test_get_withStrengthFromString_expectTypeError()
	{
		$this->expectException( 'TypeError' );
		$this->randomizer->get( 6, "not_an_integer" );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function test_get_withInvalidStrength_expectException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->randomizer->get( 6, 101 );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function test_get_withNegativeStrength_expectException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->randomizer->get( 6, -101 );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStrengthException4()
	{
		$this->randomizer->turns	= 10;

		$this->expectException( 'RuntimeException' );
		$this->randomizer->get( 5, 30 );
	}
}
