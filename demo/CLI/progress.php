<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
require_once __DIR__.'/../../vendor/autoload.php';

use CeusMedia\Common\CLI\Output\Progress;

$total		= 100;

$progress = new Progress();
$progress->setTotal( $total );
$progress->start();
for( $i=1; $i<$total; $i++ ){
	$progress->update( $i );
	usleep( 125000 );
}
$progress->finish();
