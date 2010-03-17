<?php
/**
 *	TestUnit of Alg_PREG_Match.
 *	@package		Tests.
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Alg_PREG_Match.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Preg_Match
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2008
 *	@version		0.1
 */
class Test_Alg_PREG_MatchTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Tests Exception of Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAcceptException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Alg_PREG_Match::accept( 0.1, "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAcceptException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Alg_PREG_Match::accept( "not_relevant", 0.1 );
	}

	/**
	 *	Tests Exception of Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAcceptException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Alg_PREG_Match::accept( "not_relevant", "not_relevant", 0.1 );
	}

	/**
	 *	Tests Exception of Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAcceptException4()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
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
}
?>