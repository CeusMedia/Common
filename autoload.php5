<?php
require_once( dirname( __FILE__ ).'/src/Loader.php5' );

/*  --  cmClasses AutoLoader  -- */
define( 'PATH_CMCLASSES', dirname( __FILE__ )."/src/" );

$__loaderLib	= new CMC_Loader();																	//  get new Loader Instance
$__loaderLib->setExtensions( 'php5' );																//  set allowed Extension
$__loaderLib->setPath( PATH_CMCLASSES );															//  lower Class Path
#$__loaderLib->setLowerPath( TRUE );
$__loaderLib->setVerbose( FALSE );
#$__loaderLib->setPrefix( 'CMC' );																	//  later: set Class Name Prefix (classes are not prefixed yet) || NOTE: kriss:this is not working at the moment because the autoloader needs to remove the pending underscore aswell. 
$__loaderLib->registerAutoloader();
define( 'CMC_LOADED', TRUE );

/*  --  Standard Local AutoLoader  -- */
#$__loaderLocal	= new CMC_Loader( 'php5,php,php.inc' );												//  autoload Classes in current path


function import(){}
new UI_DevOutput;

?>
