<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
require_once __DIR__.'/../../vendor/autoload.php';

use CeusMedia\Common\CLI\Password;

$input	= new Password();
$p		= $input->get();

print( 'Given: '.$p.PHP_EOL );
