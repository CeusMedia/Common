<?php
/**
 *	TestUnit of Clock.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Clock.
 *	@package		Tests.
 *	@extends		Test_Case
 *	@uses			Alg_Time_Clock
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
final class Test_Alg_Object_MethodFactoryTest extends Test_Case
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
		$object	= new Test_Alg_Object_MethodFactoryTestClass( 2 );

		$factory	= new Alg_Object_MethodFactory();
		$factory->setClass( 'Test_Alg_Object_MethodFactoryTestClass', [2] );
		$factory->setMethod( 'callableMethod', [3] );
		$creation	= $factory->call();

		$this->assertEquals( 6, $creation );
	}

	public function testCallException1()
	{
		$this->expectException( 'RuntimeException' );
		$factory	= new Alg_Object_MethodFactory();
		$creation	= $factory->call();
	}

	public function testCallException2()
	{
		$this->expectException( 'RuntimeException' );
		$factory	= new Alg_Object_MethodFactory();
		$factory->setClass( 'Test_Alg_Object_MethodFactoryTestClass', [2] );
		$creation	= $factory->call();
	}

	public function testCallException3()
	{
		$this->expectException( 'BadMethodCallException' );
		$factory	= new Alg_Object_MethodFactory();
		$factory->setClass( 'Test_Alg_Object_MethodFactoryTestClass', [2] );
		$factory->setMethod( 'invalidMethod', [3] );
		$creation	= $factory->call();
	}
}

class Test_Alg_Object_MethodFactoryTestClass
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


