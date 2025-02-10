<?php
namespace CeusMedia\CommonTool\Compatibility;

class CliApp
{
	protected string $rootPath;

	public function __construct( array $arguments )
	{
		$this->rootPath	= dirname( __FILE__, 3 ).'/';
		$this->dispatch( $arguments[1] ?? '' );
	}

	protected function dispatch( string $action ): void
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
		}
	}
}
