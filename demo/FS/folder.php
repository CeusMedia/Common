<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
require_once __DIR__.'/../../vendor/autoload.php';

use CeusMedia\Common\CLI;
use CeusMedia\Common\FS;
use CeusMedia\Common\FS\Folder;
use CeusMedia\Common\UI\DevOutput;
use CeusMedia\Common\UI\Text;

new DevOutput;

$cli	= new CLI();
CLI::out( 'Colors: '.$cli->getColors() );
try{

	if( 1 ){
		$f	= new Folder( __DIR__.'/../' );
		CLI::out( "Count recursive:" );
		CLI::out( Text::line( '-' ) );
		CLI::out( "- Folders: ".$f->count( FS::TYPE_FOLDER, TRUE ) );
		CLI::out( "- Files: ".$f->count( FS::TYPE_FILE, TRUE ) );
		CLI::out( "- Total: ".$f->count( FS::TYPE_ALL, TRUE ) );
		CLI::out();
	}

	if( 0 ){
		$cli->ls( __DIR__ );
/*		remark( "Listing: " );
		remark( UI_Text::line( '-' ) );
		print_m( $f->index()->getAll() );*/
	}

	if( 0 ){
		$f	= new Folder( __DIR__ );
		if( !$f->has( 'abc' ) )
			$f->createFolder( 'abc' );
		$abc	= $f->getFolder( 'abc' );
		if( !$abc->has( 'test.txt' ) )
			$abc->createFile( 'test.txt', time() );
		CLI::out( "Content: ".$abc->getFile( 'test.txt' )->getContent() );
		CLI::out( "Listing: " );
		CLI::out( $abc->index( FS::TYPE_FILE )->getAll() );
	}

	if( 0 ){
		CLI::out();
		$cli->ls( 'Test' );
	}

	$cli->charTable();

}


catch( Exception_IO $e ){
	CLI::out( 'Exception: '.sprintf( $e->getMessage().': %s', $e->getResource() ) );
}
catch( Exception $e ){
	CLI::out( 'Exception: '.$e->getMessage() );
	CLI::out( $e->getTraceAsString() );
}
