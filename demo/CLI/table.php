<?php
require_once __DIR__.'/../../vendor/autoload.php';

$data	= [
	[ 1, 'a', 'AAA' ],
	[ 24, 'bbb', 'BB' ],
	[ 369, 'ccccc', 'C' ],
];

$t = new CLI_Output_Table();
$t->setData( $data );
$t->setSizeMode( CLI_Output_Table::SIZE_MODE_MIN );
$t->setBorderStyle( CLI_Output_Table::BORDER_STYLE_MIXED );
print( $t->render() );
