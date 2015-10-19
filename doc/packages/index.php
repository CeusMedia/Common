<?php
require_once( "../../autoload.php" );

$pathJpgraph	= '../../../../jpgraph-3.5.0b1/src/';
$pathClasses	= '../../src/';
$refresh		= isset( $_GET['refresh'] );

define( 'JPGRAPH_PATH', $pathJpgraph );
require_once( JPGRAPH_PATH.'jpgraph.php' );
require_once( JPGRAPH_PATH.'jpgraph_pie.php');
require_once( JPGRAPH_PATH.'jpgraph_pie3d.php' );

require_once 'PackageGraphView.php';
$graph	= new PackageGraphView( $pathClasses );
$graph->baseCss		= 'http://cdn.int1a.net/css/';
$graph->baseJs		= 'http://cdn.int1a.net/js/';
print( $graph->buildView( $refresh ) );
?>
