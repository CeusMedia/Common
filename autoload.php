<?php

$pathSrc	= dirname( __FILE__ ) . "/src/";

if( !defined( 'CMC_PATH' ) )
	define('CMC_PATH', $pathSrc);
if( !defined( 'CMC_PATH' ) ){
	$config 	= parse_ini_file( 'Common.ini', TRUE );
	define( 'CMC_VERSION', $config['project']['version'] );
}

/* PSR-0 */
require_once $pathSrc . 'FS/Autoloader/Psr0.php';

$loader = new FS_Autoloader_Psr0();
$loader->setIncludePath( $pathSrc );
$loader->register();

/* PSR-4 - use this for v0.9
require_once $pathSrc . 'FS/Autoloader/Psr4.php';
$loader = new \CeusMedia\Common\FS\Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace( 'CeusMedia\Common', $pathSrc );*/

class_exists( 'UI_DevOutput' );
