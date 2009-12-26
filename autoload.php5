<?php
require_once( dirname( __FILE__ ).'/src/Loader.php5' );

/*  --  cmClasses AutoLoader  -- */
define( 'PATH_CMCLASSES', dirname( __FILE__ )."/src/" );

$loaderLib	= new CMC_Loader();																		//  get new Loader Instance
$loaderLib->setExtensions( 'php5' );																//  set allowed Extension
$loaderLib->setPath( PATH_CMCLASSES );																//  set fixed Library Path
#$loaderLib->setPrefix( 'CMC' );																	//  later: set Class Name Prefix (classes are not prefixed yet) || NOTE: kriss:this is not working at the moment because the autoloader needs to remove the pending underscore aswell. 
define( 'CMC_LOADED', TRUE );

/*  --  Standard Local AutoLoader  -- */
#new CMC_Loader( 'php5,php,php.inc' );																		//  get new Loader Instance

function import(){}
new UI_DevOutput;

?>