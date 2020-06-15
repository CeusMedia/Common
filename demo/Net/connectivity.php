<?php
require_once __DIR__.'/../../vendor/autoload.php';
new UI_DevOutput;

$c = new Net_Connectivity();

$c->setMethod( Net_Connectivity::METHOD_PING );
print( 'Using ping: ' );
print_r( (bool) $c->isOnline() );
print( PHP_EOL );

$c->setMethod( Net_Connectivity::METHOD_SOCKET );
print( 'Using sock: ' );
print_r( (bool) $c->isOnline() );
print( PHP_EOL );
