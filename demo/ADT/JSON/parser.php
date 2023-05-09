<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
require_once __DIR__.'/../../../vendor/autoload.php';

use CeusMedia\Common\UI\DevOutput;
use CeusMedia\Common\ADT\JSON\Parser as JsonParser;

new DevOutput();

$parser	= new JsonParser();

try{
	$result	= $parser->parse( '[invalid_json' );
	print_m( $result );
}
catch( Exception $e ){
	print_m( $e->getMessage() );
	print_m( $parser->getMessage() );
	print_m( $parser->getInfo() );
}
