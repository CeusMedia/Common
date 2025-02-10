<?php

namespace CeusMedia\CommonTool\Go;

use CeusMedia\Common\Alg\UnitFormater;

/**
 *	@deorecated not needed once Go is gone
 */
require_once dirname( __FILE__ ).'/Library.php';
class Benchmark
{
	public function __construct()
	{
		define( 'LB', "\n" );
		$path	= Library::getLibraryPath();
		require_once( $path.'autoload.php' );
		$path	= Library::getSourcePath();

		echo LB.'Memory Usage on start:';
		echo LB.'----------------------';
		$this->showMemoryUsage();
		echo LB;

		echo LB.'Loading... ';
		$start		= microtime( TRUE );
		$data	= Library::listClasses( $path );

		$number	= 0;
		foreach( $data['files'] as $file )
		{
			$number++;
			require_once( $file );
		}
		echo $number.' classes in '.round( ( microtime( TRUE ) - $start ) * 1000 ).' ms'.LB;
		echo LB.'Memory Usage after loading:';
		echo LB.'---------------------------';
		$this->showMemoryUsage();
		echo LB;
	}

	function showMemoryUsage(): void
	{
		define( 'LB', "\n" );
		$usage	= memory_get_usage();
		$limit	= (int) ini_get( 'memory_limit' ) * 1024 * 1024;
		$ratio	= round( $usage / $limit * 100, 3 )."%";
		echo LB.'Limit: '. UnitFormater::formatBytes( $limit );
		echo LB.'Usage: '. UnitFormater::formatBytes( $usage );
		echo LB.'Ratio: '.$ratio;
	}
}
