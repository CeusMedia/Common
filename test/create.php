<?php
/**
 *	Creates a test class skeleton for a library class.
 *
 *	Usage: php test/create.php [-f|--force] <class>
 *	Class may be given relative to the library namespace (e.g. FS\File\Backup or FS_File_Backup)
 *	or absolute (e.g. CeusMedia\Common\FS\File\Backup).
 */

use CeusMedia\Common\FS\File\PHP\Test\Creator;

require_once dirname( __DIR__ ).'/vendor/autoload.php';

try{
	$arguments	= array_slice( $_SERVER['argv'], 1 );
	$force		= (bool) array_intersect( ['-f', '--force'], $arguments );
	$arguments	= array_values( array_diff( $arguments, ['-f', '--force'] ) );
	if( 0 === count( $arguments ) )
		throw new InvalidArgumentException( 'No class name given to create test class for.' );

	$basePath	= dirname( __DIR__ ).'/';
	$creator	= new Creator( 'CeusMedia\\Common', 'CeusMedia\\CommonTest', $basePath.'src', $basePath.'test' );
	$creator->setBaseClass( 'CeusMedia\\CommonTest\\BaseCase' );
	$creator->createForFile( $arguments[0], $force );
	echo 'Created test class file "'.$creator->getTargetFile().'".'.PHP_EOL;
} catch( Exception $e ){
	fwrite( STDERR, 'Error: '.$e->getMessage().PHP_EOL );
	exit( 1 );
}
