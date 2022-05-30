<?php
declare( strict_types = 1 );
/**
 *	TestUnit of Constant Class
 *	@package		adt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test;

use CeusMedia\Common\ADT\Constant;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Constant Class
 *	@package		Tests.adt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ConstantTest extends BaseCase
{
	public function testGetAll()
	{
		$assertion	= array();
		$creation	= Constant::getAll( 'INVALID_PREFIX_' );
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
		$creation	= Constant::getAll( 'INI_' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'INI_USER'		=> 1,
			'INI_PERDIR'	=> 2,
			'INI_SYSTEM'	=> 4,
			'INI_ALL'		=> 7,
		);
		$creation	= Constant::getAll( 'INI_', 'SCANNER_' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAllException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		Constant::getAll( 'I' );
	}

	public function testGetByKeyValue()
	{
		$assertion	= 'JSON_ERROR_NONE';
		$creation	= Constant::getKeyByValue( 'JSON_ERROR', JSON_ERROR_NONE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'JSON_ERROR_DEPTH';
		$creation	= Constant::getKeyByValue( 'JSON_ERROR_', JSON_ERROR_DEPTH );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetByKeyValueException1()
	{
		$this->expectException( 'RangeException' );
		Constant::getKeyByValue( 'JSON', -1 );
	}

	public function testGetByKeyValueException2()
	{
		$this->expectException( 'RangeException' );
		Constant::getKeyByValue( 'JSON', 1 );
	}
}
