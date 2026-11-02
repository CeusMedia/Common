<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\UI\SVG;

use CeusMedia\Common\UI\JS\CodeMirror;
use CeusMedia\Common\UI\SVG\BarAcross;
use CeusMedia\Common\UI\SVG\Chart;
use CeusMedia\Common\UI\SVG\ChartData;
use CeusMedia\Common\UI\SVG\PieGraph;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of UI\SVG charts and UI\JS\CodeMirror.
 *	@package		Tests.UI.SVG
 */
class ChartTest extends BaseCase
{
	public function testChartsEscapeLabels(): void
	{
		$chart	= new Chart( [new ChartData( 1, '<script>a</script>' ), new ChartData( 3, 'B & C' )] );
		$html	= ( new BarAcross( $chart ) )->build( [] );
		self::assertStringNotContainsString( '<script>', $html );
		self::assertStringContainsString( 'B &amp; C', $html );
		self::assertEqualsWithDelta( 75.0, $chart->data[1]->percent, 0.001 );
	}

	public function testPieGraphWithSingleValue(): void
	{
		$chart	= new Chart( [new ChartData( 5, 'all' )] );
		self::assertStringContainsString( '<circle', ( new PieGraph( $chart ) )->build( [] ) );
	}

	public function testChartWithZeroValues(): void
	{
		$chart	= new Chart( [new ChartData( 0, 'a' )] );
		self::assertSame( 0.0, $chart->data[0]->percent );
	}

	public function testEncapsulate(): void
	{
		$chart	= new Chart( [new ChartData( 1, 'a' )] );
		$svg	= $chart->encapsulate( '<g/>' );
		self::assertStringContainsString( 'xmlns="http://www.w3.org/2000/svg"', $svg );
		self::assertStringNotContainsString( 'width=', $svg );
		self::assertStringNotContainsString( 'height=', $svg );

		$svg	= $chart->encapsulate( '<g/>', 300, 200.5 );
		self::assertStringContainsString( 'width="300"', $svg );
		self::assertStringContainsString( 'height="200.5"', $svg );
	}

	public function testCodeMirror(): void
	{
		$script	= ( new CodeMirror() )->setReadOnly()->build( 'textarea[name="code"]' );
		self::assertStringContainsString( '"readOnly":true', $script );
		self::assertStringNotContainsString( '"readonly"', $script );
		self::assertStringContainsString( '$("textarea[name=\u0022code\u0022]")', $script );
	}
}
