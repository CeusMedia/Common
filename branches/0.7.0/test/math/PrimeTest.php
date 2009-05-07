<?php
/**
 *	TestUnit of Math_Prime.
 *	@package		Tests.math
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Math_Prime
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.math.Prime' );
/**
 *	TestUnit of Math_Prime.
 *	@package		Tests.math
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Math_Prime
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
class Math_PrimeTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Tests Method 'getPrimes'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPrimes()
	{
		$assertion	= array( 2, 3, 5, 7 );
		$creation	= Math_Prime::getPrimes( 10 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47 );
		$creation	= Math_Prime::getPrimes( 50 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isPrime'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsPrime()
	{
		$assertion	= FALSE;
		$creation	= Math_Prime::isPrime( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= Math_Prime::isPrime( 2 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= Math_Prime::isPrime( 3 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= Math_Prime::isPrime( 4 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= Math_Prime::isPrime( 1001 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= Math_Prime::isPrime( 1009 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPrimeFactors'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPrimeFactors()
	{
		$assertion	= array( 2 );
		$creation	= Math_Prime::getPrimeFactors( 2 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 2, 2 );
		$creation	= Math_Prime::getPrimeFactors( 4 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 7, 11, 13 );
		$creation	= Math_Prime::getPrimeFactors( 1001 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 2, 3, 167 );
		$creation	= Math_Prime::getPrimeFactors( 1002 );
		$this->assertEquals( $assertion, $creation );
	}
}
?>