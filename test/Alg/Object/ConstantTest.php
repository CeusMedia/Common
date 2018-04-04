<?php
/**
 *	TestUnit of Clock.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
require_once dirname( dirname( __DIR__ ) ).'/initLoaders.php';
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
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$className		= 'Test_Alg_Object_ConstantExample1';
		$this->object	= new Alg_Object_Constant( $className );
	}

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
	}

	public function testGetAll(){
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
	}

	public function testGetAllByPrefix(){
		$assertion		= array(
			'1'		=> 1,
		);
		$creation		= $this->object->getAllByPrefix( 'A' );
		$this->assertEquals( $assertion, $creation );
		$creation		= $this->object->getAllByPrefix( 'A_' );
		$this->assertEquals( $assertion, $creation );

		$assertion		= array(
			'1'		=> 1,
			'2'		=> 2,
		);
		$creation		= $this->object->getAllByPrefix( 'B' );
		$this->assertEquals( $assertion, $creation );
		$creation		= $this->object->getAllByPrefix( 'B_' );
		$this->assertEquals( $assertion, $creation );

		$assertion		= array(
			'1'		=> 1,
			'2'		=> 2,
			'3'		=> 3,
		);
		$creation		= $this->object->getAllByPrefix( 'C' );
		$this->assertEquals( $assertion, $creation );
		$creation		= $this->object->getAllByPrefix( 'C_' );
		$this->assertEquals( $assertion, $creation );

		$assertion		= array();
		$creation		= $this->object->getAllByPrefix( 'D', FALSE );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAllByPrefixRangeException(){
		$this->setExpectedException( 'RangeException' );
		$this->object->getKeyByValue( 3, 'A' );
	}

	public function testGetKeyByValue(){
		$assertion		= 'C_3';
		$creation		= $this->object->getKeyByValue( 3 );
		$this->assertEquals( $assertion, $creation );
	}

	public function getKeyByValueAndPrefix(){
		$assertion		= '3';
		$creation		= $this->object->getKeyByValue( 3, 'C' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetKeyByValueAndPrefixException1(){
		$this->setExpectedException( 'RangeException' );
		$this->object->getKeyByValue( 3, 'A' );
	}
}

class Test_Alg_Object_ConstantExample1{
	const A_1		= 1;
	const B_1		= 1;
	const B_2		= 2;
	const C_1		= 1;
	const C_2		= 2;
	const C_3		= 3;
}
