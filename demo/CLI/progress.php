<?php
require_once __DIR__.'/../../vendor/autoload.php';

$total		= 100;

$progress = new CLI_Output_Progress();
$progress->setTotal( $total );
$progress->start();
for( $i=1; $i<$total; $i++ ){
	$progress->update( $i );
	usleep( 125000 );
}
$progress->finish();
