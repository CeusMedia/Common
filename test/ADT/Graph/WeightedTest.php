<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\ADT\Graph;

use CeusMedia\Common\ADT\Graph\Weighted;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of ADT\Graph\Weighted (undirected).
 *	@package		Tests.ADT.Graph
 */
class WeightedTest extends BaseCase
{
	public function testAddNodeReturnsExisting(): void
	{
		$graph	= new Weighted();
		$a		= $graph->addNode( 'a' );
		self::assertSame( $a, $graph->addNode( 'a' ) );
		self::assertSame( 1, $graph->getNodeSize() );
	}

	public function testCycles(): void
	{
		$graph	= new Weighted();
		$a	= $graph->addNode( 'a' );
		$b	= $graph->addNode( 'b' );
		$c	= $graph->addNode( 'c' );
		$graph->addEdge( $a, $b, 1 );
		$graph->addEdge( $b, $c, 2 );
		self::assertFalse( $graph->hasCycle() );
		self::assertTrue( $graph->isForrest() );
		self::assertTrue( $graph->isCoherent() );
		self::assertSame( 3, $graph->getPathValue( $a, $c ) );

		$graph->addEdge( $c, $a, 5 );
		self::assertTrue( $graph->hasCycle() );
		self::assertFalse( $graph->isForrest() );
	}

	public function testLoop(): void
	{
		$graph	= new Weighted();
		$a	= $graph->addNode( 'a' );
		self::assertFalse( $graph->hasLoop() );
		$graph->addEdge( $a, $a, 1 );
		self::assertTrue( $graph->hasLoop() );
		self::assertTrue( $graph->hasCycle() );
	}

	public function testRemoveNodeKeepsOrder(): void
	{
		$graph	= new Weighted();
		foreach( ['c', 'a', 'b', 'd'] as $name )
			$graph->addNode( $name );
		$graph->removeNode( $graph->getNode( 'a' ) );
		self::assertSame( ['c', 'b', 'd'], array_map( static fn( $node ) => $node->getNodeName(), $graph->getNodes() ) );
	}
}
