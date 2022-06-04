<?php
declare( strict_types = 1 );
/**
 *	TestUnit of Predicate Validator.
 *	@package		Tests.Alg.Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\Alg\Validation;

use CeusMedia\Common\Alg\Validation\PredicateValidator;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Predicate Validator.
 *	@package		Tests.Alg.Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class PredicateValidatorTest extends BaseCase
{
	public function setUp(): void
	{
		$this->validator	= new PredicateValidator;
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
	}

	public function testIsClassException()
	{
		$this->expectException( 'BadMethodCallException' );
		$this->validator->isClass( "123abc", "notexisting" );
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
