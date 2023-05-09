<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
require_once __DIR__.'/../../vendor/autoload.php';

use CeusMedia\Common\UI\DevOutput;
use CeusMedia\Common\CLI;
use CeusMedia\Common\CLI\Question;

new DevOutput;

try{
	CLI::checkIsCli();

	$input		= new Question( "What is your name", Question::TYPE_STRING, 'Mr. Unknown' );
	CLI::out( 'Result: '.$input->ask() );
	CLI::out();

	$range		= new Question( "How much is the fish?", Question::TYPE_INTEGER, 5 );
	$range->setRange( 1, 10 );
	CLI::out( 'Result: '.$range->ask() );
	CLI::out();

	$choice		= new Question( "Which one?", Question::TYPE_STRING, 'second', array( 'first', 'second', 'third' ) );
	CLI::out( 'Result: '.$choice->ask() );
	CLI::out();

	$decision	= new Question( "You want that?", Question::TYPE_BOOLEAN, 'y' );
	CLI::out( 'Result: '.$decision->ask() );
	CLI::out();
}
catch( Exception $e ){
	CLI::error( 'Exception: '.$e->getMessage() );
}
