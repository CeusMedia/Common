<?php
require_once dirname( __FILE__ )."/../autoload.php5";
//print( 'init loaders at '.date( 'H:i:s' )."\n" );

$loaderTest	= new CMC_Loader();																//  get new Loader Instance
$loaderTest->setExtensions( 'php,php5' );																	//  set allowed Extension
$loaderTest->setPath( dirname( __FILE__ ) );													//  set fixed Library Path
#$loaderLib->setLogFile( TRUE );
#$loaderLib->setVerbose( TRUE );
$loaderTest->setPrefix( 'Test_' );
$loaderTest->registerAutoloader();

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
