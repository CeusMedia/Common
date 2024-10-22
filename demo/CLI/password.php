<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
require_once __DIR__.'/../../vendor/autoload.php';

use CeusMedia\Common\CLI\Password;

$input	= new Password();
$p		= $input->ask();
print( 'Given: '.$p.PHP_EOL );

$p	= Password::getInstance( 'Test 2:' )->ask();
print( 'Given: '.$p.PHP_EOL );
