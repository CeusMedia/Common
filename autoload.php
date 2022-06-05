<?php

/**
 *	Usually, this library should be installed using composer.
 *	Including the composer autoloader will be enough.
 *
 *	So, this script use only helpful, if this library needs to be available
 *	without the composer autoloader.
 *	The PSR4 autoloader within this library will be used to enable autoloading
 *	for this library, omly.
 */

use CeusMedia\Common\FS\Autoloader\Psr4 as Loader;

$pathSrc	= __DIR__ . "/src/";

/*if( !defined( 'CMC_PATH' ) ){
	define('CMC_PATH', $pathSrc);
	$config 	= parse_ini_file( __DIR__.'/Common.ini', TRUE );
	define( 'CMC_VERSION', $config['project']['version'] );
}*/

require_once $pathSrc . 'FS/Autoloader/Psr4.php';

$loader = Loader::getInstance()->register()
	->addNamespace( 'CeusMedia\Common', $pathSrc );

require_once $pathSrc . 'compat8.php';

require_once $pathSrc . 'global.php';
