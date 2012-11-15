<?php
/*  --  cmClasses AutoLoader  -- */
if( !defined( 'CMC_PATH' ) )
	define( 'CMC_PATH', dirname( __FILE__ )."/src/" );

require_once( CMC_PATH.'Loader.php5' );

$___configFile	= dirname( __FILE__ ).'/cmClasses.ini';
$___constants	= array(
	'CMC_VERSION'	=> 'version',
	'CMC_AUTHOR'	=> 'author',
	'CMC_COMPANY'	=> 'company',
	'CMC_TITLE	'	=> 'name',
	'CMC_URL'		=> 'link',
);
if( !file_exists( $___configFile ) )
	throw new RuntimeException( 'Please configure cmClasses first' );
$___config		= parse_ini_file( $___configFile, TRUE );
foreach( $___constants as $___constantKey => $___constantValue )
	if( !defined( strtoupper( $___constantKey ) ) )
		define( strtoupper( $___constantKey ), $___config['project'][$___constantValue] );

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
