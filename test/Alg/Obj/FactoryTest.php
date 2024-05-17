<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of (Object) Factory.
 *	@package		Tests.Alg.Obj
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Obj;

use CeusMedia\Common\Alg\Obj\Factory as ObjectFactory;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of (Object) Factory.
 *	@package		Tests.Alg.Obj
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
final class FactoryTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}


	public function testCreate()
	{
		$object	= new ObjectFactoryTestClass( 2 );

		$factory	= new ObjectFactory();
		$creation	= $factory->create( ObjectFactoryTestClass::class, [-1] );

		self::assertEquals( -3, $creation->callableMethod( 3 ) );
	}

/*	public function testCallException1()
	{
		$this->expectException( 'RuntimeException' );
		$factory	= new MethodFactory();
		$creation	= $factory->call();
	}

	public function testCallException2()
	{
		$this->expectException( 'RuntimeException' );
		$factory	= new MethodFactory();
		$factory->setClass( MethodFactoryTestClass::class, [2] );
		$creation	= $factory->call();
	}

	public function testCallException3()
	{
		$this->expectException( 'BadMethodCallException' );
		$factory	= new MethodFactory();
		$factory->setClass( MethodFactoryTestClass::class, [2] );
		$factory->setMethod( 'invalidMethod', [3] );
		$creation	= $factory->call();
	}*/
}

class ObjectFactoryTestClass
{
	public $a;
	public $b;

	public function __construct( $a )
	{
		$this->a	= $a;
	}

	public function callableMethod( $b ){
		$this->b	= $b;
		return $this->a * $this->b;
	}
}
