<?php
$pathTests		= dirname( __FILE__ ).DIRECTORY_SEPARATOR;
$pathLibrary	= dirname( $pathTests ).DIRECTORY_SEPARATOR;
require_once $pathLibrary.'autoload.php5';
//print( 'init loaders at '.date( 'H:i:s' )."\n" );

$loaderTest	= new CMC_Loader();													//  get new Loader Instance
$loaderTest->setExtensions( 'php5,php' );										//  set allowed Extension
$loaderTest->setPath( $pathTests );									//  set fixed Library Path
#$loaderTest->setVerbose( TRUE );												//  show autoload attempts
$loaderTest->setPrefix( 'Test_' );												//  set prefix class prefix
$loaderTest->registerAutoloader();												//  apply this autoloader

$__config	= parse_ini_file( $pathLibrary.'cmClasses.ini', TRUE );
//print_m( $__config );die;

Test_Case::$config = $__config;
?>
