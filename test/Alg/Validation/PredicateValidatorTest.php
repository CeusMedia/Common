<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Predicate Validator.
 *	@package		Tests.Alg.Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Validation;

use CeusMedia\Common\Alg\Validation\PredicateValidator;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Predicate Validator.
 *	@package		Tests.Alg.Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class PredicateValidatorTest extends BaseCase
{
	protected $validator;

	public function setUp(): void
	{
		$this->validator	= new PredicateValidator;
	}

	public function testIsClass()
	{
		$creation	= $this->validator->isClass( "abc123", "alpha" );
		self::assertTrue( $creation );

		$creation	= $this->validator->isClass( "abc123", "digit" );
		self::assertFalse( $creation );

		$creation	= $this->validator->isClass( "abc123", "id" );
		self::assertTrue( $creation );

		$creation	= $this->validator->isClass( "123abc", "id" );
		self::assertFalse( $creation );
	}

	public function testIsClassException()
	{
		$this->expectException( 'BadMethodCallException' );
		$this->validator->isClass( "123abc", "notexisting" );
	}

	public function testValidate()
	{
		$creation	= $this->validator->validate( "1", "hasValue" );
		self::assertTrue( $creation );

		$creation	= $this->validator->validate( "", "hasValue" );
		self::assertFalse( $creation );

		$creation	= $this->validator->validate( "1", "isGreater", 0 );
		self::assertTrue( $creation );

		$creation	= $this->validator->validate( "1", "isGreater", 1 );
		self::assertFalse( $creation );

		$creation	= $this->validator->validate( "1", "isLess", 2 );
		self::assertTrue( $creation );

		$creation	= $this->validator->validate( "1", "isLess", 1 );
		self::assertFalse( $creation );

		$creation	= $this->validator->validate( "01.71.2008", "isAfter", time() );
		self::assertFalse( $creation );
	}
}
