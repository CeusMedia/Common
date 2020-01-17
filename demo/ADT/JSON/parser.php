<?php
require_once __DIR__.'/../../../vendor/autoload.php';
new UI_DevOutput();

$parser	= new ADT_JSON_Parser();

try{
	$result	= $parser->parse( '[invalid_json' );
	print_m( $result );
}
catch( Exception $e ){
	print_m( $e->getMessage() );
	print_m( $parser->getMessage() );
	print_m( $parser->getInfo() );
}

