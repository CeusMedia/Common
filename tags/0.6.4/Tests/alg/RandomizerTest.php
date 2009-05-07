<?php
/**
 *	TestUnit of Alg_Randomizer.
 *	@package		Tests.alg
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Randomizer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.alg.Randomizer' );
/**
 *	TestUnit of Alg_Randomizer.
 *	@package		Tests.alg
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Randomizer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.05.2008
 *	@version		0.1
 */
class Tests_Alg_RandomizerTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->randomizer	= new Alg_Randomizer();
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
		$string		= $this->randomizer->get( 45 );
		
		$list		= array();
		foreach( str_split( $string ) as $sign )
		{
			if( in_array( $sign, $list ) )
				$this->fail( 'String is not unique' );
			$list[]	= $sign;
		}
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
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->randomizer->get( "not_an_integer" );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLengthException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
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
		$this->setExpectedException( 'RuntimeException' );
		$this->randomizer->get( 1 );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLengthException4()
	{
		$this->setExpectedException( 'UnderflowException' );
		$this->randomizer->get( 200 );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStrengthException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->randomizer->get( 6, "not_an_integer" );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStrengthException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->randomizer->get( 6, 101 );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStrengthException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
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
		
		$this->setExpectedException( 'RuntimeException' );
		$this->randomizer->get( 5, 30 );
	}
}
?>