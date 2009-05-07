<?php
/**
 *	TestUnit of Alg_Preg_Match.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Preg_Match
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.alg.preg.Match' );
/**
 *	TestUnit of Alg_Preg_Match.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Preg_Match
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2008
 *	@version		0.1
 */
class Alg_Preg_MatchTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
	}

	/**
	 *	Tests Exception of Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAcceptException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Alg_Preg_Match::accept( 0.1, "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAcceptException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Alg_Preg_Match::accept( "not_relevant", 0.1 );
	}

	/**
	 *	Tests Exception of Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAcceptException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Alg_Preg_Match::accept( "not_relevant", "not_relevant", 0.1 );
	}

	/**
	 *	Tests Exception of Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAcceptException4()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Alg_Preg_Match::accept( "[A-z", "haystack" );
	}

	/**
	 *	Tests Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAccept()
	{
		$assertion	= TRUE;
		$creation	= Alg_Preg_Match::accept( "es", "test" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= Alg_Preg_Match::accept( "^es", "test" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= Alg_Preg_Match::accept( '^[a-z]+$', "TEST", "i" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= Alg_Preg_Match::accept( '\S+', "12/ab", "i" );
		$this->assertEquals( $assertion, $creation );
	}
}
?>