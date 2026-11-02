<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\UI\Image\Graphviz;

use CeusMedia\Common\UI\Image\Graphviz\Graph;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of UI\Image\Graphviz\Graph.
 *	@package		Tests.UI.Image.Graphviz
 */
class GraphTest extends BaseCase
{
	public function testRender(): void
	{
		$graph	= new Graph( 'test' );
		$graph->setType( 'graph' );
		$graph->addNode( 'a', ['label' => 'Line 1\nSay "hi"'] );
		$graph->addNode( 'b' );
		$graph->addEdge( 'a', 'b' );
		$dot	= $graph->render();
		self::assertStringContainsString( 'label="Line 1\nSay \"hi\""', $dot );
		self::assertStringContainsString( 'a -- b', $dot );
		$graph->setType( 'digraph' );
		self::assertStringContainsString( 'a -> b', $graph->render() );
	}
}
