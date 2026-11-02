<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Alg\Sort;

use CeusMedia\Common\Alg\Sort\Bubble;
use CeusMedia\Common\Alg\Sort\Gnome;
use CeusMedia\Common\Alg\Sort\Insertion;
use CeusMedia\Common\Alg\Sort\MapList;
use CeusMedia\Common\Alg\Sort\Quick;
use CeusMedia\Common\Alg\Sort\Selection;
use CeusMedia\CommonTest\BaseCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 *	TestUnit of sort algorithms in Alg\Sort.
 *	@package		Tests.Alg.Sort
 */
class SortTest extends BaseCase
{
	public static function provideLists(): array
	{
		return [
			'mixed'			=> [[5, 3, 8, 1, 9, 2, 3]],
			'sorted'		=> [[1, 2, 3, 4]],
			'reversed'		=> [[4, 3, 2, 1]],
			'single'		=> [[7]],
			'empty'			=> [[]],
			'duplicates'	=> [[2, 2, 1, 1]],
			'keys'			=> [[3 => 'c', 1 => 'a', 2 => 'b']],
		];
	}

	#[DataProvider( 'provideLists' )]
	public function testSort( array $list ): void
	{
		$expected	= array_values( $list );
		sort( $expected );
		self::assertSame( $expected, Bubble::sort( $list ) );
		self::assertSame( $expected, ( new Insertion() )->sort( $list ) );
		self::assertSame( $expected, Selection::sort( $list ) );
		$copy	= array_values( $list );
		Quick::sort( $copy );
		self::assertSame( $expected, $copy );
		$copy	= array_values( $list );
		Gnome::sort( $copy );
		self::assertSame( $expected, $copy );
	}

	public function testMapList(): void
	{
		$data	= [
			['v' => 1.5, 'n' => 'a'],
			['v' => 1.2, 'n' => 'b'],
			['v' => 1.5, 'n' => 'c'],
			['v' => 1.9, 'n' => 'd'],
		];
		$sorted	= MapList::sort( $data, 'v' );
		self::assertSame( ['b', 'a', 'c', 'd'], array_column( $sorted, 'n' ) );
		$sorted	= MapList::sortByMultipleColumns( $data, ['v' => MapList::DIRECTION_ASC, 'n' => MapList::DIRECTION_DESC] );
		self::assertSame( ['b', 'c', 'a', 'd'], array_column( $sorted, 'n' ) );
		$sorted	= MapList::sort( $data, 'v', MapList::DIRECTION_DESC );
		self::assertSame( ['d', 'a', 'c', 'b'], array_column( $sorted, 'n' ) );
	}
}
