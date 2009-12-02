<?php
require_once( dirname( __FILE__ ).'/src/Loader.php5' );

/*  --  APL Library AutoLoader  -- */
$loaderLib	= new CMC_Loader( TRUE );																//  get new Loader Instance
$loaderLib->setExtensions( 'php5' );																	//  set allowed Extension
$loaderLib->setPath( dirname( __FILE__ )."/src/" );													//  set fixed Library Path

/*  --  Standard AutoLoader  -- */
$loaderLocal	= new CMC_Loader( TRUE );															//  get new Loader Instance
$loaderLocal->setExtensions( 'php5,php' );															//  set allowed Extension

function import(){}
new UI_DevOutput;

?>