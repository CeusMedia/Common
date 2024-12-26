<?php
declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Exception;

use CeusMedia\Common\Exception\Logic as LogicException;
use CeusMedia\CommonTest\BaseCase;

class LogicTest extends BaseCase
{
	public function testSubject(): void
	{
		$e	= LogicException::create( 'msg' )
			->setSubject( 'invalidLogic' );

		self::assertEquals( 'invalidLogic', $e->getSubject() );
	}

	public function testResource_ConstructorWithResource(): void
	{
		$e	= new LogicException( 'msg', 1, NULL, 'invalidLogic' );

		self::assertEquals( 'invalidLogic', $e->getSubject() );
	}
}