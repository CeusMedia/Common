<?php
class Go_DocCreator
{
	public function __construct( $arguments )
	{
		$config		= Go_Library::getConfigData();
		require_once dirname( dirname( __FILE__ ) ).'/autoload.php';
		if( !isset( $config['docCreator'] ) )
			throw new Exception( 'Config file has no section "docCreator"' );
		$path	= $config['docCreator']['pathTool'];
		if( !file_exists( $path ) )
			throw new Exception( 'Tool "DocCreator" is not installed' );
		CMC_Loader::registerNew( 'php5', 'DocCreator_', $path."classes/" );
		CMC_Loader::registerNew( 'php', 'Michelf\\', '/var/www/lib/php-markdown/Michelf/' );

		$file	= dirname( dirname( __FILE__ ) )."/doc-creator.xml";
		$runner	= new DocCreator_Core_Runner( $file );
		$runner->main();

#		$creator	= new DocCreator_Core_ConsoleRunner( $file );									//  open new starter
	}
}
?>
