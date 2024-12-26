<?php
declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Exception;

use CeusMedia\Common\Exception\IO as IOException;
use CeusMedia\CommonTest\BaseCase;

class IOTest extends BaseCase
{
	public function testResource(): void
	{
		$e	= IOException::create( 'msg', 1 )
			->setResource( 'invalidFile.png' );

		self::assertEquals( 'invalidFile.png', $e->getResource() );
	}

	public function testResource_ConstructorWithResource(): void
	{
		$e	= new IOException( 'msg', 1, NULL, 'invalidFile.png' );

		self::assertEquals( 'invalidFile.png', $e->getResource() );
	}
}