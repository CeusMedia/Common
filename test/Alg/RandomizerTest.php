<?php
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

		$assertion	= TRUE;
		$creation	= is_string( $string );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= strlen( $string );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= (bool) preg_match( "@^[a-zA-Z0-9]$@", $string );
		$this->assertEquals( $assertion, $creation );

		$this->randomizer->useLarges	= FALSE;
		$this->randomizer->useDigits	= FALSE;
		$string		= $this->randomizer->get( 20 );

		$assertion	= TRUE;
		$creation	= is_string( $string );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 20;
		$creation	= strlen( $string );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= (bool) preg_match( "@^[a-z]{20}$@", $string );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithStrength()
	{
		$string		= $this->randomizer->get( 15, 30 );

		$assertion	= TRUE;
		$creation	= is_string( $string );
		$this->assertEquals( $assertion, $creation );
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
	public function testGetLarge()
	{
		$this->randomizer->unique	= FALSE;
		$string		= $this->randomizer->get( 240 );

		$assertion	= TRUE;
		$creation	= is_string( $string );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 240;
		$creation	= strlen( $string );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLengthException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->randomizer->get( "not_an_integer" );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLengthException2()
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
	public function testGetStrengthException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->randomizer->get( 6, "not_an_integer" );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStrengthException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->randomizer->get( 6, 101 );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStrengthException3()
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
