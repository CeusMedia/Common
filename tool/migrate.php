<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/Migration/Modifier.php';
require_once __DIR__.'/Migration/Applier.php';
new UI_DevOutput();

use CeusMedia_Common_Tool_Migration_Modifier as Modifier;
use CeusMedia_Common_Tool_Migration_Applier as Applier;

$path	= "src/Alg/Object";
$path	= "src/Alg";
$path	= "test";
$path	= "src";

$modifiers	= array(
//	array( Modifier::class, 'updateLineBreak' ),
//	array( Modifier::class, 'clearEndingPhpTagInLines' ),
//	array( Modifier::class, 'updateCopyrightYearInLines' ),
//	array( Modifier::class, 'clearDocVersionInLines' ),
//	array( Modifier::class, 'breakCommentsInLines' ),
//	array( Modifier::class, 'updateTestSetUpAndTearDown' ),
	array( Modifier::class, 'removeIndentsInEmptyLines' ),
//	array( Modifier::class, '' ),
);

$applier	= new Applier();
$applier->setRootFolder( new FS_Folder( realpath( __DIR__.'/../'.$path ) ) );
$applier->setModifiers( $modifiers );
$stats		= $applier->apply();

remark( 'Files changed: '.$stats->nrFilesChanged.' of '.$stats->nrFiles );
remark();
