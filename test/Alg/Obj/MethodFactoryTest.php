<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Method Factory.
 *	@package		Tests.Alg.Obj
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Obj;

use CeusMedia\Common\Alg\Obj\MethodFactory;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Clock.
 *	@package		Tests.Alg.Obj
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
final class MethodFactoryTest extends BaseCase
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


	public function testCall()
	{
		$object	= new MethodFactoryTestClass( 2 );

		$factory	= new MethodFactory();
		$factory->setClass( MethodFactoryTestClass::class, [2] );
		$factory->setMethod( 'callableMethod', [3] );
		$creation	= $factory->call();

		self::assertEquals( 6, $creation );
	}

	public function testCallException1()
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
	}
}

class MethodFactoryTestClass
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
