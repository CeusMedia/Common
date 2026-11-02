<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\XML;

use CeusMedia\Common\XML\Parser;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of XML\Parser.
 *	@package		Tests.XML
 */
class ParserTest extends BaseCase
{
	public function testToArrayKeepsSpacesAroundEntities(): void
	{
		$data	= ( new Parser() )->toArray( "<r><a x=\"1\">a &amp; b</a><b>\n  text  \n</b></r>" );
		$children	= $data[0]['children'];
		self::assertSame( 'a & b', $children[0]['content'] );
		self::assertSame( ['x' => '1'], $children[0]['attributes'] );
		self::assertSame( 'text', $children[1]['content'] );
	}

	public function testToObject(): void
	{
		$node	= ( new Parser() )->toObject( '<r><a>a &lt; b</a><c><d>x</d></c></r>' );
		self::assertSame( 'r', $node->getNodeName() );
		self::assertSame( 'a < b', $node->getChildByIndex( 0 )->getContent() );
		self::assertSame( 'x', $node->getChildByIndex( 1 )->getChildByIndex( 0 )->getContent() );
	}
}
