<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Delegation.
 *	@package		Tests.Alg.Obj
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Obj;

use CeusMedia\Common\Alg\Obj\Delegation;
use CeusMedia\CommonTest\BaseCase;

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


	public function testCall(): void
	{
		$delegation	= new Delegation();
		$delegation->addClass( DelegationTestClass::class, [3] );
		$actual		= $delegation->doSomething( 6 );

		self::assertEquals( 18, $actual );
	}

	public function testCallException1(): void
	{
		$this->expectException( 'BadMethodCallException' );
		$this->expectExceptionMessage( 'Method "notExisting" is not existing in added objects');
		$delegation	= new Delegation();
		$delegation->addClass( DelegationTestClass::class, [3] );
		/** @noinspection PhpUndefinedMethodInspection */
		$delegation->notExisting( 6 );
	}

	public function testCallException2(): void
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
