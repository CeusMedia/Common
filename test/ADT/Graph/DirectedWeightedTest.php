<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of ADT\Graph\DirectedWeighted.
 *	@package		Tests.ADT.Graph
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\ADT\Graph;

use CeusMedia\Common\ADT\Graph\DirectedWeighted;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of ADT\Graph\DirectedWeighted.
 *	@package		Tests.ADT.Graph
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class DirectedWeightedTest extends BaseCase
{
	public function testTraverseBreadth(): void
	{
		$graph	= new DirectedWeighted();
		$a	= $graph->addNode( 'a' );
		$b	= $graph->addNode( 'b' );
		$c	= $graph->addNode( 'c' );
		$d	= $graph->addNode( 'd' );
		$e	= $graph->addNode( 'e' );
		$graph->addEdge( $a, $b, 2 );
		$graph->addEdge( $a, $c, 5 );
		$graph->addEdge( $b, $d, 1 );
		$graph->addEdge( $c, $d, 1 );
		$graph->addEdge( $e, $a, 1 );

		$expected	= ['a' => 0, 'b' => 2, 'c' => 5, 'd' => 3];
		self::assertSame( $expected, $graph->traverseBreadth( $a ) );
		self::assertSame( ['d' => 0], $graph->traverseBreadth( $d ) );
	}

	public function testTraverseBreadthWithCycle(): void
	{
		$graph	= new DirectedWeighted();
		$a	= $graph->addNode( 'a' );
		$b	= $graph->addNode( 'b' );
		$c	= $graph->addNode( 'c' );
		$graph->addEdge( $a, $b, 1 );
		$graph->addEdge( $b, $c, 1 );
		$graph->addEdge( $c, $a, 1 );

		self::assertSame( ['a' => 0, 'b' => 1, 'c' => 2], $graph->traverseBreadth( $a ) );
		self::assertSame( ['b' => 0, 'c' => 1, 'a' => 2], $graph->traverseBreadth( $b ) );
	}
}
