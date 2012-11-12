<?php
/*  --  cmClasses AutoLoader  -- */
if( !defined( 'CMC_PATH' ) )
	define( 'CMC_PATH', dirname( __FILE__ )."/src/" );

require_once( CMC_PATH.'Loader.php5' );

$__config	= dirname( __FILE__ ).'/cmClasses.ini';
if( file_exists( $__config ) ){
	$__config	= parse_ini_file( $__config, TRUE );
	$constants	= array(
		'CMC_VERSION'	=> 'version',
		'CMC_AUTHOR'	=> 'author',
		'CMC_COMPANY'	=> 'company',
		'CMC_TITLE	'	=> 'name',
		'CMC_URL'		=> 'link',
	);
	foreach( $constants as $constantName => $configProjectKey )
		if( !defined( $constantName ) )
			define( $constantName, $__config['project'][$configProjectKey] );
}
else
	if( !defined( 'CMC_VERSION' ) )
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
