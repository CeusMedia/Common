<?php
require_once( "../useClasses.php5" );
import( 'de.ceus-media.ui.DevOutput' );
import( 'de.ceus-media.ui.ClassParser' );
import( 'de.ceus-media.alg.TestCaseCreator' );
import( 'de.ceus-media.console.ArgumentParser' );

$parser	= new Console_ArgumentParser();
$parser->parseArguments();
$arguments	= $parser->getOptions();

if( !isset( $arguments['file'] ) )
	die( "Syntax: create package_className [force] [folder]" );
$file	= $arguments['file'];
$force	= isset( $arguments['force'] ) ? (bool) $arguments['force'] : FALSE;
$folder	= isset( $arguments['folder'] ) ? (bool) $arguments['folder'] : FALSE;

$creator	= new Alg_TestCaseCreator();
if( $folder )
{
	remark( "Indexing Path..." );
	$count	= $creator->createForFolder( $file, $force );
	remark( "Called Test Case Creator for ".$count." Classes." );
}
else
{
	$creator->createForFile( $file, $force );
	remark( "Created Test Class." );
}
?>