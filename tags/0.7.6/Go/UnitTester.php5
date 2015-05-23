<?php
require_once dirname( __FILE__ ).'/Library.php5';
class Go_UnitTester
{
	public function __construct( $className = NULL )
	{
		if( !empty( $className ) )
			return $this->runTestOfClass( trim( $className ) );
		 $this->runAllTests();
	}

	protected function runAllTests()
	{
		remark( "Reading Class Files:\n" );
		$data	= Go_Library::listClasses( dirname( dirname ( __FILE__ ) ).'/src/' );
		$number	= count( $data['files'] );
		$length	= strlen( $number );
		for( $i=0; $i<$number; $i++ )
		{
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
		$config		= Go_Library::getConfigData();
		foreach( $config['unitTestOptions'] as $key => $value )
			$command	.= " --".$key." ".$value;
		print( "\nRunning Unit Tests:\n\r" );
		$command	.= " Test";
		passthru( $command );
	}

	protected function runTestOfClass( $className )
	{
		$parts		= explode( "_", $className );
		$fileKey	= array_pop( $parts );
		$suffix		= $fileKey == "All" ? "Tests" : "Test";
		while( $parts )
			$fileKey	= array_pop( $parts )."/".$fileKey;

		$testClass	= "Test_".$className.$suffix;
		$testFile	= "Test/".$fileKey.$suffix.".php";
		if( !file_exists( $testFile ) )
			throw new RuntimeException( 'Test Class File "'.$testFile.'" is not existing' );
		echo "\nTesting Class: ".$className."\n\n";
		
		passthru( "phpunit ".$testClass, $return );
	} 
}
?>
