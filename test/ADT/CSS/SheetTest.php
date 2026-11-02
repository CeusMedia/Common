<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\ADT\CSS;

use CeusMedia\Common\ADT\CSS\Property;
use CeusMedia\Common\ADT\CSS\Rule;
use CeusMedia\Common\ADT\CSS\Sheet;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of ADT\CSS\Sheet and Rule.
 *	@package		Tests.ADT.CSS
 */
class SheetTest extends BaseCase
{
	public function testGetMissingProperty(): void
	{
		$sheet	= new Sheet();
		$sheet->set( 'a', 'color', 'red' );
		self::assertSame( 'red', $sheet->get( 'a', 'color' )?->getValue() );
		self::assertNull( $sheet->get( 'a', 'margin' ) );
		self::assertNull( $sheet->get( 'b', 'color' ) );
	}

	public function testRemoveKeepsIndices(): void
	{
		$rule	= new Rule( 'a', [new Property( 'a', '1' ), new Property( 'b', '2' ), new Property( 'c', '3' )] );
		$rule->removePropertyByKey( 'a' );
		self::assertSame( 'b', $rule->getPropertyByIndex( 0 )->getKey() );
		self::assertSame( 'c', $rule->getPropertyByIndex( 1 )->getKey() );
	}
}
