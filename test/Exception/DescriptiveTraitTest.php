<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_Lock.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Exception;

use CeusMedia\Common\Exception\Runtime;
use CeusMedia\CommonTest\BaseCase;

class DescriptiveTraitTest extends BaseCase
{
	public function testJson(): void
	{
		$e = Runtime::create( 'mesg1', 1 )
			->setDescription( 'desc1' )
			->setSuggestion( 'sugg1' );

		self::assertEquals( 'desc1', $e->getDescription() );
		self::assertEquals( 'sugg1', $e->getSuggestion() );

		$actual	= json_decode( $e->getJson(), TRUE );

		self::assertArrayHasKey( 'description', $actual );
		self::assertEquals( 'desc1', $actual['description'] );

		self::assertArrayHasKey( 'description', $actual );
		self::assertEquals( 'sugg1', $actual['suggestion'] );
	}
}