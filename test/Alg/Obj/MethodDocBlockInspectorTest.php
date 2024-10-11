<?php

/**
 *	TestUnit of MethodDocBlockInspector.
 *	@package		Tests.Alg.Obj
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Obj;

use CeusMedia\Common\Alg\Obj\MethodDocBlockInspector;
use CeusMedia\CommonTest\BaseCase;
use ReflectionException;

/**
 *	TestUnit of MethodDocBlockInspector.
 *	@package		Tests.Alg.Obj
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class MethodDocBlockInspectorTest extends BaseCase
{
	public function testGet(): void
	{
		$className	= MethodDocBlockInspector::class;
		$lines		= MethodDocBlockInspector::get( $className, 'get' );
		$expected	= [
			'Uses reflection on class method to get doc block lines.',
			'@param		string		$className		Class name',
			'@param		string		$methodName		Method name',
			'@return		array',
			'@throws		ReflectionException',
		];

		self::assertSame( $expected, $lines );
	}

	public function testGet_ReflectionException(): void
	{
		$this->expectException( ReflectionException::class );
		MethodDocBlockInspector::get( 'InvalidClass', 'invalidMethod' );
	}
}