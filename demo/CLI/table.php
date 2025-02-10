<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
require_once __DIR__.'/../../vendor/autoload.php';

use CeusMedia\Common\CLI\Output\Table as CliTable;

$data	= [
	[ 1, 'a', 'AAA' ],
	[ 24, 'bbb', 'BB' ],
	[ 369, 'ccccc', 'C' ],
];

$t = new CliTable();
$t->setData( $data );
$t->setSizeMode( CliTable::SIZE_MODE_MIN );
$t->setBorderStyle( CliTable::BORDER_STYLE_MIXED );
print( $t->render() );
