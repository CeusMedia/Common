<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Delegation.
 *	@package		Tests.Alg.Obj
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\Alg\Obj;

use CeusMedia\Common\Alg\Obj\Delegation;
use CeusMedia\Common\Alg\Obj\MethodFactory;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Delegation.
 *	@package		Tests.Alg.Obj
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
final class DelegationTest extends BaseCase
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
		$delegation	= new Delegation();
		$delegation->addClass( DelegationTestClass::class, [3] );
		$actual		= $delegation->doSomething( 6 );

		$this->assertEquals( 18, $actual );
	}

	public function testCallException1()
	{
		$this->expectException( 'BadMethodCallException' );
		$this->expectExceptionMessage( 'Method "notExisting" is not existing in added objects');
		$delegation	= new Delegation();
		$delegation->addClass( DelegationTestClass::class, [3] );
		$delegation->notExisting( 6 );
	}

	public function testCallException2()
	{
		$this->expectException( 'RuntimeException' );
		$this->expectExceptionMessage( 'Method "doSomething" is already set');
		$delegation	= new Delegation();
		$delegation->addClass( DelegationTestClass::class, [2] );
		$delegation->addClass( DelegationTestClass::class, [2] );
	}
}

class DelegationTestClass
{
	public $a;
	public $b;

	public function __construct( $a )
	{
		$this->a	= $a;
	}

	public function doSomething( $b ){
		$this->b	= $b;
		return $this->a * $this->b;
	}
}
