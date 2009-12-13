<?php
require_once( "../../autoload.php5" );

$pathJpgraph	= '../../../../jpgraph/2.3.4/';
$pathClasses	= '../../src/';
$refresh		= isset( $_GET['refresh'] );

define( 'JPGRAPH_PATH', $pathJpgraph );

import( 'PackageGraphView' );
$graph	= new PackageGraphView( $pathClasses );
#$graph->baseCss		= '//localhost/ceusmedia/css/';
#$graph->baseJs		= '//localhost/ceusmedia/js/';
print( $graph->buildView( $refresh ) );
?>