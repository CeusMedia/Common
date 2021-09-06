<?php
/**
 *	TestUnit of Alg_PREG_Match.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			22.12.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Alg_PREG_Match.
 *	@package		Tests.
 *	@extends		Test_Case
 *	@uses			Alg_Preg_Match
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			22.12.2008
 *	@version		0.1
 */
class Test_Alg_PREG_MatchTest extends Test_Case
{
	/**
	 *	Tests Exception of Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAcceptException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		Alg_PREG_Match::accept( 0.1, "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAcceptException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		Alg_PREG_Match::accept( "not_relevant", 0.1 );
	}

	/**
	 *	Tests Exception of Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAcceptException3()
	{
		$this->expectException( 'InvalidArgumentException' );
		Alg_PREG_Match::accept( "not_relevant", "not_relevant", 0.1 );
	}

	/**
	 *	Tests Exception of Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAcceptException4()
	{
		$this->expectException( 'InvalidArgumentException' );
		Alg_PREG_Match::accept( "[A-z", "haystack" );
	}

	/**
	 *	Tests Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAccept()
	{
		$assertion	= TRUE;
		$creation	= Alg_PREG_Match::accept( "es", "test" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= Alg_PREG_Match::accept( "^es", "test" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= Alg_PREG_Match::accept( '^[a-z]+$', "TEST", "i" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= Alg_PREG_Match::accept( '\S+', "12/ab", "i" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet()
	{
		$assertion	= "es";
		$creation	= Alg_PREG_Match::get( "es", "test" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= Alg_PREG_Match::get( "^es", "test" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TEST";
		$creation	= Alg_PREG_Match::get( '^[a-z]+$', "TEST", "i" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "12/ab";
		$creation	= Alg_PREG_Match::get( '\S+', "12/ab", "i" );
		$this->assertEquals( $assertion, $creation );
	}
}
