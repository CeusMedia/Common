<?php
function import( $classKey )
{
#	return TRUE;
	if( preg_match( "/ceus-media/i", $classKey ) )
		return;
	$fileName	= str_replace( ".", "/", $classKey ).".php5";
	if( !@file_get_contents( $fileName, TRUE ) )
		throw new RuntimeException( 'Invalid file: '.$fileName );
	require_once( $fileName );
}
$loaderLib	= create_function( '$className','
	$pathLib	= str_replace( "\\\\", "/", dirname( __FILE__ ) )."/src/";
	$fileName	= str_replace( "_","/", $className );
	$fileName	= $pathLib.$fileName.".php5";
#	echo "<br/>autoload lib: try ".$fileName;
	if( !file_exists( $fileName ) )
		return FALSE;
	require_once( $fileName );' );
$loaderLocal	= create_function( '$className','
	$fileName	= str_replace( "_","/", $className );
	$fileName	= $fileName.".php5";
#	echo "<br/>autoload local: try ".$fileName;
	if( !file_exists( $fileName ) )
		return FALSE;
	require_once( $fileName );' );
spl_autoload_register( $loaderLib );
spl_autoload_register( $loaderLocal );
new UI_DevOutput;
?>