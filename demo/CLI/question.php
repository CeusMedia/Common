<?php
require_once __DIR__.'/../../vendor/autoload.php';
new UI_DevOutput;

try{
	CLI::checkIsCLi();

	$input		= new CLI_Question( "What is your name", CLI_Question::TYPE_STRING, 'Mr. Unknown' );
	CLI::out( 'Result: '.$input->ask() );
	CLI::out();

	$range		= new CLI_Question( "How much is the fish?", CLI_Question::TYPE_INTEGER, 5 );
	$range->setRange( 1, 10 );
	CLI::out( 'Result: '.$range->ask() );
	CLI::out();

	$choice		= new CLI_Question( "Which one?", CLI_Question::TYPE_STRING, 'second', array( 'first', 'second', 'third' ) );
	CLI::out( 'Result: '.$choice->ask() );
	CLI::out();

	$decision	= new CLI_Question( "You want that?", CLI_Question::TYPE_BOOLEAN, 'y' );
	CLI::out( 'Result: '.$decision->ask() );
	CLI::out();
}
catch( Exception $e ){
	CLI::error( 'Exception: '.$e->getMessage() );
}
