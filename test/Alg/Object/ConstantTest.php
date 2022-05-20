<?php
/**
 *	TestUnit of Clock.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Clock.
 *	@package		Tests.
 *	@extends		Test_Case
 *	@uses			Alg_Time_Clock
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
final class Test_Alg_Object_ConstantTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$className		= 'Test_Alg_Object_ConstantExample1';
		$this->object	= new Alg_Object_Constant( $className );
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
		$this->assertEquals( $assertion, $creation );

		$assertion		= array(
			'1'		=> 1,
		);
		$creation		= $this->object->getAll( 'A' );
		$this->assertEquals( $assertion, $creation );
		$creation		= $this->object->getAll( 'A_' );
		$this->assertEquals( $assertion, $creation );

		$assertion		= array(
			'1'		=> 1,
			'2'		=> 2,
		);
		$creation		= $this->object->getAll( 'B' );
		$this->assertEquals( $assertion, $creation );
		$creation		= $this->object->getAll( 'B_' );
		$this->assertEquals( $assertion, $creation );

		$assertion		= array(
			'1'		=> 1,
			'2'		=> 2,
			'3'		=> 3,
		);
		$creation		= $this->object->getAll( 'C' );
		$this->assertEquals( $assertion, $creation );
		$creation		= $this->object->getAll( 'C_' );
		$this->assertEquals( $assertion, $creation );

		$assertion		= array();
		$creation		= $this->object->getAll( 'D' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetKeyByValue()
	{
		$assertion		= 'C_3';
		$creation		= $this->object->getKeyByValue( 3 );
		$this->assertEquals( $assertion, $creation );

		$assertion		= '3';
		$creation		= $this->object->getKeyByValue( 3, 'C' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetKeyByValueException1()
	{
		$this->expectException( 'RangeException' );
		$this->object->getKeyByValue( 3, 'A' );
	}

	public function testGetKeyByValueException2()
	{
		$this->expectException( 'RangeException' );
		$this->object->getKeyByValue( 1 );
	}

	public function testGetValue()
	{
		$assertion		= '3';
		$creation		= $this->object->getValue( 'C_3' );
		$this->assertEquals( $assertion, $creation );

		$assertion		= '3';
		$creation		= $this->object->getValue( '3', 'C' );
		$this->assertEquals( $assertion, $creation );
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

class Test_Alg_Object_ConstantExample1
{
	const A_1		= 1;
	const B_1		= 1;
	const B_2		= 2;
	const C_1		= 1;
	const C_2		= 2;
	const C_3		= 3;
}
