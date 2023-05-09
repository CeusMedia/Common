<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
require_once __DIR__.'/../../vendor/autoload.php';

use CeusMedia\Common\Net\Connectivity;
use CeusMedia\Common\UI\DevOutput;

new DevOutput;

$c = new Connectivity();

$c->setMethod( Connectivity::METHOD_PING );
print( 'Using ping: ' );
print_r( (bool) $c->isOnline() );
print( PHP_EOL );

$c->setMethod( Connectivity::METHOD_SOCKET );
print( 'Using sock: ' );
print_r( (bool) $c->isOnline() );
print( PHP_EOL );
