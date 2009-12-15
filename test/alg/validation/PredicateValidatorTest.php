<?php
/**
 *	TestUnit of Predicate Validator.
 *	@package		Tests.alg.validation
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Validation_PredicateValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of Predicate Validator.
 *	@package		Tests.alg.validation
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Validation_PredicateValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
class Test_Alg_Validation_PredicateValidatorTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$this->validator	= new Alg_Validation_PredicateValidator;
	}
	
	public function testIsClass()
	{
		$assertion	= true;
		$creation	= $this->validator->isClass( "abc123", "alpha" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->validator->isClass( "abc123", "digit" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= $this->validator->isClass( "abc123", "id" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->validator->isClass( "123abc", "id" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		try{
			$creation	= $this->validator->isClass( "123abc", "notexisting" );
			$this->fail( 'An Exception has not been thrown.' );
		}
		catch( Exception $e )
		{
		}
	}

	public function testValidate()
	{
		$assertion	= true;
		$creation	= $this->validator->validate( "1", "hasValue" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->validator->validate( "", "hasValue" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= $this->validator->validate( "1", "isGreater", 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->validator->validate( "1", "isGreater", 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= $this->validator->validate( "1", "isLess", 2 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->validator->validate( "1", "isLess", 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->validator->validate( "01.71.2008", "isAfter", time() );
		$this->assertEquals( $assertion, $creation );
	}
}
?>