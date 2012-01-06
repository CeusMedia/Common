<?php
require_once dirname( __FILE__ )."/../autoload.php5";
//print( 'init loaders at '.date( 'H:i:s' )."\n" );
$loaderLib	= new CMC_Loader();																//  get new Loader Instance
$loaderLib->setExtensions( 'php,php5' );																	//  set allowed Extension
$loaderLib->setPath( dirname( __FILE__ ) );													//  set fixed Library Path
$loaderLib->setLogFile( TRUE );
#$loaderLib->setVerbose( TRUE );
$loaderLib->setPrefix( 'Test_' );
$loaderLib->registerAutoloader();

$__config	= parse_ini_file( dirname( __FILE__ )."/../cmClasses.ini", TRUE );

Test_Case::$config = $__config;

//  --  LOADERS  --  //
/*function import( $class )
{
	while( preg_match( "@^-@", $class ) )
		$class	= preg_replace( "@^(-)*-@", "\\1../", $class ); 
    $filename   = str_replace( ".", "/", $class ).".php5";
	if( !file_exists( $filename ) )
		throw new InvalidArgumentException( 'Class "'.$filename.'" is not existing.' );
    include_once( $filename );
}
import( 'de.ceus-media.ui.DevOutput' );
import( 'de.ceus-media.throwException' );
require_once( 'test/MockAntiProtection.php' );
*/
?>
