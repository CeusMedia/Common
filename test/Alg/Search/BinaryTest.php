<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Alg\Search\Binary.
 *	@package		Tests.Alg.Search
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Search;

use CeusMedia\Common\Alg\Search\Binary;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Alg\Search\Binary.
 *	@package		Tests.Alg.Search
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
final class BinaryTest extends BaseCase
{
	public function testSearchReturnsPosition(): void
	{
		$list	= [2, 3, 5, 7, 11, 13, 17];
		$search	= new Binary();
		foreach( $list as $position => $value )
			self::assertSame( $position, $search->search( $list, $value ) );
	}

	public function testSearchNotFound(): void
	{
		$search	= new Binary();
		self::assertSame( -1, $search->search( [2, 3, 5], 4 ) );
		self::assertSame( -1, $search->search( [], 4 ) );
	}
}
