<?php

namespace CeusMedia\CommonTool\Go;

/**
 *	@deorecated use make dev-test-unit instead!
 */
require_once __DIR__.'/Library.php';
class UnitTester
{
	public function __construct( $className = NULL )
	{
		if( !empty( $className ) )
			return $this->runTestOfClass( trim( $className ) );
		 $this->runAllTests();
	}

	protected function runAllTests(): void
	{
		remark( "Reading Class Files:\n" );
		$data	= Library::listClasses( dirname( __FILE__, 3 ).'/src/' );
		$number	= count( $data['files'] );
		$length	= strlen( $number );
		for( $i=0; $i<$number; $i++ ){
			require_once( $data['files'][$i] );
			if( !( $i % 60 ) ){
				$percent	= str_pad( round( $i / $number * 100 ), 3, ' ', STR_PAD_LEFT );
				$current	= str_pad( $i, $length, ' ', STR_PAD_LEFT );
				echo " ".$current." / ".$number." (".$percent."%)\n";
			}
			echo '.';
		}
		remark( "\n" );

		$command	= "phpunit";
		$config		= Library::getConfigData();
		foreach( $config['unitTestOptions'] as $key => $value )
			$command	.= " --".$key." ".$value;
		print( "\nRunning Unit Tests:\n\r" );
		$command	.= " Test";
		passthru( $command );
	}

	protected function runTestOfClass( $className ): void
	{
		$parts		= explode( "_", $className );
		$fileKey	= array_pop( $parts );
		$suffix		= $fileKey == "All" ? "Tests" : "Test";
		while( $parts )
			$fileKey	= array_pop( $parts )."/".$fileKey;

		$testClass	= "Test_".$className.$suffix;
		$testFile	= "Test/".$fileKey.$suffix.".php";
		if( !file_exists( $testFile ) )
			throw new \RuntimeException( 'Test Class File "'.$testFile.'" is not existing' );
		echo "\nTesting Class: ".$className."\n\n";

		passthru( "phpunit ".$testClass, $return );
	}
}
