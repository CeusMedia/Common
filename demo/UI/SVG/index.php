<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

use CeusMedia\Common\UI\SVG\Chart;
use CeusMedia\Common\UI\SVG\ChartData;

require_once dirname( __DIR__, 3 ).'/vendor/autoload.php';
ini_set('display_errors', TRUE );

$chartData  = [
	new ChartData( 1, 'Take 1' ),
	new ChartData( 2, 'Take 2' ),
	new ChartData( 3, 'Take 3' ),
];
$graphOptions    = ["x" => 50, "y" => 50, "legend" => TRUE, 'animated' => TRUE];

$chart      = new Chart( $chartData );
$chart->buildPieGraph( $graphOptions );
//$chart->buildBarAcross( $graphOptions );
$chart->save( 'test.svg' );
print( '<img src="test.svg" width="518" height="400"/>' );
