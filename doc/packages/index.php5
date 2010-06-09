<?php
require_once( "../../autoload.php5" );

$pathJpgraph	= '../../../../jpgraph/3.0.7/src/';
$pathClasses	= '../../src/';
$refresh		= isset( $_GET['refresh'] );

define( 'JPGRAPH_PATH', $pathJpgraph );
require_once( JPGRAPH_PATH.'jpgraph.php' );
require_once( JPGRAPH_PATH.'jpgraph_pie.php');
require_once( JPGRAPH_PATH.'jpgraph_pie3d.php' );

require_once 'PackageGraphView.php5';
$graph	= new PackageGraphView( $pathClasses );
#$graph->baseCss		= '//localhost/ceusmedia/css/';
#$graph->baseJs		= '//localhost/ceusmedia/js/';
print( $graph->buildView( $refresh ) );
?>