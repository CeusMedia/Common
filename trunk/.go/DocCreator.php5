<?php
class Go_DocCreator
{
	public function __construct( $arguments, $configFile, $config )
	{
		$path	= $config['docCreator']['pathTool'];
		$file	= dirname( dirname( __FILE__ ) )."/doc.ini";
		require_once( $path.'/classes/DocCreatorStarter.php5' );

		$starter	= new DocCreatorStarter;										//  open new starter
		$starter->setProjectConfigFile( $file );								//  set project's config file for DocCreator
		$starter->start();															//  run starter 
	}
}
?>