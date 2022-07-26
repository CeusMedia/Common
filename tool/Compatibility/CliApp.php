<?php
namespace CeusMedia\Common\Tool\Compatibility;

class CliApp
{
	protected $rootPath;

	public function __construct( $arguments )
	{
		$this->rootPath	= dirname( __DIR__, 2 ).'/';
		$this->dispatch( $arguments[1] ?? '' );
	}

	protected function dispatch( string $action )
	{
		$compat	= new Worker( $this->rootPath );
		switch( $action ){
			case 'compat8-show-missing':
				require_once $this->rootPath.'vendor/autoload.php';
				$count	= $compat->showMissing8();
				print( 'Classes not found: '.$count.PHP_EOL );
				break;
			case 'compat8-generate':
				$count	= $compat->generateCompat8();
				print( 'Wrote class map with '.$count.' entries.'.PHP_EOL );
				break;
			case 'compat9-show-missing':
				require_once $this->rootPath.'vendor/autoload.php';
				$count	= $compat->showMissing9();
				print( 'Classes not found: '.$count.PHP_EOL );
				break;
			case 'compat9-generate':
				$count	= $compat->generateCompat9();
				print( 'Wrote class map with '.$count.' entries.'.PHP_EOL );
				break;
			default:
				print( join( PHP_EOL, [
					'Error: No action given.'.PHP_EOL,
					'Usage: compat [ACTION]'.PHP_EOL,
					'Actions:',
					'  compat8-show-missing',
					'  compat8-generate',
					'  compat9-show-missing',
					'  compat9-generate',
				] ).PHP_EOL );
		};
	}
}
