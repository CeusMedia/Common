<?php
class Go_DocCreator
{
	public function __construct( $arguments )
	{
		$config		= Go_Library::getConfigData();
		require_once dirname( dirname( __FILE__ ) ).'/autoload.php5';
		$path	= $config['docCreator']['pathTool'];
		if( !file_exists( $path ) )
			throw new Exception( 'Tool "DocCreator" is not installed' );
		CMC_Loader::registerNew( 'php5', 'DocCreator_', $path."classes/" );
		$file	= dirname( dirname( __FILE__ ) )."/doc.xml";
		$runner	= new DocCreator_Core_Runner( $file );
		$runner->main();

#		$creator	= new DocCreator_Core_ConsoleRunner( $file );									//  open new starter
	}
}
?>
