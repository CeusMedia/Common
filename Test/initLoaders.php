<?php
$pathLib	= realpath(dirname(dirname(__FILE__)));
$pathSrc	= realpath(dirname(dirname(__FILE__)) . '/src');

require_once $pathSrc . '/FS/Autoloader/Psr0.php';

$loaderTest	= new \CeusMedia\Common\FS\Autoloader\Psr0;
$loaderTest->setIncludePath($pathLib);
$loaderTest->register();

$loaderSrc	= new \CeusMedia\Common\FS\Autoloader\Psr0;
$loaderSrc->setIncludePath($pathSrc);
$loaderSrc->register();

$__config	= parse_ini_file($pathLib.'/Common.ini', TRUE);
//print_m( $__config );die;
Test_Case::$config = $__config;
class_exists('UI_DevOutput');
return;

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

$__config	= parse_ini_file( $pathLibrary.'Common.ini', TRUE );
//print_m( $__config );die;

Test_Case::$config = $__config;

?>
