#!/usr/bin/php
<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

require_once __DIR__.'/../vendor/autoload.php';

use CeusMedia\Common\CLI;
use CeusMedia\Common\CLI\Color as CliColor;
use CeusMedia\Common\FS\Folder;
use CeusMedia\CommonTool\Migration\Applier;
use CeusMedia\CommonTool\Migration\Modifier;

$places	= array(
	'src'	=> (object) array(
		'active'	=> TRUE,
		'path'		=> 'src',
		'modifiers'	=> array(
			array( Modifier::class, 'updateLineBreak' ),
			array( Modifier::class, 'clearEndingPhpTagInLines' ),
			array( Modifier::class, 'removeIndentsInEmptyLines' ),
			array( Modifier::class, 'updateCopyrightYearInLines', '201\d', '2020' ),
			array( Modifier::class, 'clearDocVersionInLines' ),
		//	array( Modifier::class, 'breakCommentsInLines' ),
		)
	),
	'test'	=> (object) array(
		'active'	=> TRUE,
		'path'		=> 'test',
		'modifiers'	=> array(
			array( Modifier::class, 'updateLineBreak' ),
			array( Modifier::class, 'clearEndingPhpTagInLines' ),
			array( Modifier::class, 'removeIndentsInEmptyLines' ),
			array( Modifier::class, 'updateCopyrightYearInLines', '201\d', '2020' ),
			array( Modifier::class, 'clearDocVersionInLines' ),
//			array( Modifier::class, 'breakCommentsInLines' ),
			array( Modifier::class, 'updateTestSetUpAndTearDown' ),
		)
	),
	'demo'	=> (object) array(
		'active'	=> TRUE,
		'path'		=> 'demo',
		'modifiers'	=> array(
			array( Modifier::class, 'updateLineBreak' ),
			array( Modifier::class, 'clearEndingPhpTagInLines' ),
			array( Modifier::class, 'removeIndentsInEmptyLines' ),
			array( Modifier::class, 'updateCopyrightYearInLines', '201\d', '2020' ),
			array( Modifier::class, 'clearDocVersionInLines' ),
//			array( Modifier::class, 'breakCommentsInLines' ),
		)
	),
);

new CeusMedia\Common\UI\DevOutput();
$cliColor	= new CliColor();

foreach( $places as $placeKey => $placeData ){
	if( empty( $placeData->active ) )
		continue;
	CLI::out();
	CLI::out( $cliColor->asInfo( 'Place: '.$placeKey ) );
	$folder		= new Folder( realpath( __DIR__.'/../'.$placeData->path ) );
	$applier	= new Applier();
	$applier->setRootFolder( $folder );
	$applier->setModifiers( $placeData->modifiers );
	$stats		= $applier->apply();
	CLI::out( 'Files changed: '.$stats->nrFilesChanged.' of '.$stats->nrFiles );
}

CLI::out();
CLI::out();
