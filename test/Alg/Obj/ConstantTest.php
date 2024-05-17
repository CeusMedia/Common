<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Constant.
 *	@package		Tests.Alg.Obj
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Obj;

use CeusMedia\Common\Alg\Obj\Constant;
use CeusMedia\Common\Exception\Data\Ambiguous as AmbiguousDataException;
use CeusMedia\CommonTest\BaseCase;


/**
 *	TestUnit of Constant.
 *	@package		Tests.Alg.Obj
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
final class ConstantTest extends BaseCase
{
	protected $object;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$className		= ConstantExample1::class;
		$this->object	= new Constant( $className );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	public function testGetAll()
	{
		$assertion		= array(
			'A_1'		=> 1,
			'B_1'		=> 1,
			'B_2'		=> 2,
			'C_1'		=> 1,
			'C_2'		=> 2,
			'C_3'		=> 3,
		);
		$creation		= $this->object->getAll();
		self::assertEquals( $assertion, $creation );

		$assertion		= array(
			'1'		=> 1,
		);
		$creation		= $this->object->getAll( 'A' );
		self::assertEquals( $assertion, $creation );
		$creation		= $this->object->getAll( 'A_' );
		self::assertEquals( $assertion, $creation );

		$assertion		= array(
			'1'		=> 1,
			'2'		=> 2,
		);
		$creation		= $this->object->getAll( 'B' );
		self::assertEquals( $assertion, $creation );
		$creation		= $this->object->getAll( 'B_' );
		self::assertEquals( $assertion, $creation );

		$assertion		= array(
			'1'		=> 1,
			'2'		=> 2,
			'3'		=> 3,
		);
		$creation		= $this->object->getAll( 'C' );
		self::assertEquals( $assertion, $creation );
		$creation		= $this->object->getAll( 'C_' );
		self::assertEquals( $assertion, $creation );

		$assertion		= [];
		$creation		= $this->object->getAll( 'D' );
		self::assertEquals( $assertion, $creation );
	}

	public function testGetKeyByValue()
	{
		$assertion		= 'C_3';
		$creation		= $this->object->getKeyByValue( 3 );
		self::assertEquals( $assertion, $creation );

		$assertion		= '3';
		$creation		= $this->object->getKeyByValue( 3, 'C' );
		self::assertEquals( $assertion, $creation );
	}

	public function testGetKeyByValueException1()
	{
		$this->expectException( 'RangeException' );
		$this->object->getKeyByValue( 3, 'A' );
	}

	public function testGetKeyByValueException2()
	{
		$this->expectException( AmbiguousDataException::class );
		$this->object->getKeyByValue( 1 );
	}

	public function testGetValue()
	{
		$assertion		= '3';
		$creation		= $this->object->getValue( 'C_3' );
		self::assertEquals( $assertion, $creation );

		$assertion		= '3';
		$creation		= $this->object->getValue( '3', 'C' );
		self::assertEquals( $assertion, $creation );
	}

	public function testGetValueException1()
	{
		$this->expectException( 'DomainException' );
		$this->object->getValue( 'A_2' );
	}

	public function testGetValueException2()
	{
		$this->expectException( 'DomainException' );
		$this->object->getValue( '2', 'A' );
	}
}

class ConstantExample1
{
	const A_1		= 1;
	const B_1		= 1;
	const B_2		= 2;
	const C_1		= 1;
	const C_2		= 2;
	const C_3		= 3;
}
