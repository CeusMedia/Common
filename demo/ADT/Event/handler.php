<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
require_once __DIR__.'/../../../vendor/autoload.php';

use CeusMedia\Common\ADT\Event\Callback;
use CeusMedia\Common\ADT\Event\Data as EventData;
use CeusMedia\Common\UI\DevOutput;
use CeusMedia\Common\ADT\Event\Handler as EventHandler;

new DevOutput();

class MyDataObject{
	public int $someInteger = 1;
};

$verbose    = TRUE;

$dataObject		= new MyDataObject();

if( $verbose ){
	remark( 'Original Event Data Object:' );
	print_m( $dataObject );
}

$callbackFunction   = function( EventData $event ){
	if( $event->arguments['verbose'] ?? FALSE ){
		remark( 'Event Data Object inside callback:' );
		print_m( $event );
	}
	if( $event->data instanceof MyDataObject ){
		$event->data->someInteger *= 2;
	}
	return TRUE;
};

$handler	= new EventHandler();

$handler->bind( 'test1', new Callback( $callbackFunction, $dataObject ) );

$handler->bind( 'test1', $callbackFunction );

$result = $handler->trigger( 'test1', NULL, ['verbose' => $verbose] );

remark('Result: '.$result);
print_m($dataObject);
