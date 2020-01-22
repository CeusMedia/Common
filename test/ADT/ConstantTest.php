<?php
/**
 *	TestUnit of Constant Class
 *	@package		adt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Constant Class
 *	@package		Tests.adt
 *	@extends		Test_Case
 *	@uses			ADT_Constant
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
class Test_ADT_ConstantTest extends Test_Case
{
	public function testGetAll()
	{
		$assertion	= array();
		$creation	= ADT_Constant::getAll( 'INVALID_PREFIX_' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'INI_USER'				=> 1,
			'INI_PERDIR'			=> 2,
			'INI_SYSTEM'			=> 4,
			'INI_ALL'				=> 7,
			'INI_SCANNER_NORMAL'	=> 0,
			'INI_SCANNER_RAW'		=> 1,
			'INI_SCANNER_TYPED'		=> 2,
		);
		$creation	= ADT_Constant::getAll( 'INI_' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'INI_USER'		=> 1,
			'INI_PERDIR'	=> 2,
			'INI_SYSTEM'	=> 4,
			'INI_ALL'		=> 7,
		);
		$creation	= ADT_Constant::getAll( 'INI_', 'SCANNER_' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAllException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		ADT_Constant::getAll( 'I' );
	}

	public function testGetByKeyValue()
	{
		$assertion	= 'JSON_ERROR_NONE';
		$creation	= ADT_Constant::getKeyByValue( 'JSON_ERROR', JSON_ERROR_NONE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'JSON_ERROR_DEPTH';
		$creation	= ADT_Constant::getKeyByValue( 'JSON_ERROR_', JSON_ERROR_DEPTH );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetByKeyValueException1()
	{
		$this->expectException( 'RangeException' );
		ADT_Constant::getKeyByValue( 'JSON', -1 );
	}

	public function testGetByKeyValueException2()
	{
		$this->expectException( 'RangeException' );
		ADT_Constant::getKeyByValue( 'JSON', 1 );
	}
}
