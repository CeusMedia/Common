<?php

namespace CeusMedia\CommonTool\Go;

use CeusMedia\Common\FS\Autoloader\Psr4;
use Exception;

/**
 *	@deorecated use make dev-create-docs instead!
 */
class DocCreator
{
	public function __construct( $arguments )
	{
		$root		= dirname( __FILE__, 3 ).'/';
		$config		= Library::getConfigData();
		require_once $root.'autoload.php';
		if( !isset( $config['docCreator'] ) )
			throw new Exception( 'Config file has no section "docCreator"' );
		$path	= $config['docCreator']['pathTool'];
		if( !file_exists( $path ) )
			throw new Exception( 'Tool "DocCreator" is not installed' );

		$psr4Loader	= new Psr4();
		$psr4Loader->addNamespace( 'CeusMedia\DocCreator', $path.'src' );
		$psr4Loader->addNamespace( 'CeusMedia\PhpParser', $root.'vendor/ceus-media/php-parser/src' );
		$psr4Loader->addNamespace( 'Michelf', $root.'vendor/michelf/php-markdown/Michelf' );
		$psr4Loader->register();

		$file	= $root."doc-creator.xml";
		$runner	= new \CeusMedia\DocCreator\Core\Runner( $file );
		$runner->main();

#		$creator	= new DocCreator_Core_ConsoleRunner( $file );									//  open new starter
	}
}
