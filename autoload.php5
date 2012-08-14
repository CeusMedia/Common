<?php
/*  --  cmClasses AutoLoader  -- */
define( 'CMC_PATH', dirname( __FILE__ )."/src/" );

require_once( CMC_PATH.'Loader.php5' );

$__config	= dirname( __FILE__ ).'/cmClasses.ini';
if( file_exists( $__config ) ){
	$__config	= parse_ini_file( $__config, TRUE );
	define( 'CMC_VERSION', $__config['project']['version'] );
	define( 'CMC_AUTHOR', $__config['project']['author'] );
	define( 'CMC_COMPANY', $__config['project']['company'] );
	define( 'CMC_TITLE', $__config['project']['name'] );
	define( 'CMC_URL', $__config['project']['link'] );
}
else
	define( 'CMC_VERSION', basename( dirname( __FILE__ ) ) );


$__loaderLib	= new CMC_Loader();																	//  get new loader instance
$__loaderLib->setExtensions( 'php5' );																//  set allowed fiel extension
$__loaderLib->setPath( CMC_PATH );																	//  set class path
$__loaderLib->setVerbose( FALSE );
#$__loaderLib->setPrefix( 'CMC' );																	//  later: set class name prefix (classes are not prefixed yet) || NOTE: kriss:this is not working at the moment because the autoloader needs to remove the pending underscore aswell.
$__loaderLib->registerAutoloader();
define( 'CMC_LOADED', TRUE );

/*  --  Standard Local AutoLoader  -- */
if( defined( 'CMC_AUTOLOAD_LOCAL' ) && CMC_AUTOLOAD_LOCAL ){
	$__loaderLocal	= new CMC_Loader( 'php5,php,php.inc' );											//  autoload Classes in current path
	$__loaderLocal->registerAutoloader();
}

if( !function_exists( 'import' ) ){
	function import(){}
}
new UI_DevOutput;
?>
