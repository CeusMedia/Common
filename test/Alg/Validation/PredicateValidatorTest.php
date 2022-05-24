<?php
/**
 *	TestUnit of Predicate Validator.
 *	@package		Tests.alg.validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			14.02.2008
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Predicate Validator.
 *	@package		Tests.alg.validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			14.02.2008
 */
class Test_Alg_Validation_PredicateValidatorTest extends Test_Case
{
	public function setUp(): void
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
