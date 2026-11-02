<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Alg\Search;

use CeusMedia\Common\Alg\Search\Interpolation;
use CeusMedia\Common\Alg\Search\Strange;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Alg\Search\Interpolation and Strange.
 *	@package		Tests.Alg.Search
 */
class InterpolationTest extends BaseCase
{
	public function testInterpolation(): void
	{
		$search	= new Interpolation();
		self::assertSame( 4, $search->search( [1, 2, 3, 4, 5], 5 ) );
		self::assertSame( 0, $search->search( [1, 2, 3, 4, 5], 1 ) );
		self::assertSame( 0, $search->search( [2, 2, 2], 2 ) );
		self::assertSame( -1, $search->search( [1, 3, 5], 4 ) );
		self::assertSame( -1, $search->search( [1, 3, 5], 9 ) );
		self::assertSame( -1, $search->search( [], 1 ) );
		self::assertSame( 0, $search->search( [7], 7 ) );
		self::assertSame( 5, $search->search( [1, 2, 4, 8, 16, 32, 64], 32 ) );
	}

	public function testTernarySearch(): void
	{
		$search	= new Strange();
		$list	= [1, 2, 3, 4, 5, 6, 7];
		foreach( $list as $index => $value )
			self::assertSame( $index, $search->search( $list, $value ) );
		self::assertSame( -1, $search->search( [2, 3], 1 ) );
		self::assertSame( -1, $search->search( [2, 3], 4 ) );
		self::assertSame( -1, $search->search( [], 1 ) );
	}
}
