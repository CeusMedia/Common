<?php
require_once dirname( __FILE__ ).'/Library.php5';
class Go_UnitTester
{
	public function __construct( $className = NULL )
	{
		if( !empty( $className ) )
			return $this->runTestOfClass( trim( $className ) );
		 $this->runnAllTests();
	}

	protected function runAllTests()
	{
		remark( "Reading Class Files:\n" );
		$data	= Go_Library::listClasses( dirname( dirname ( __FILE__ ) ).'/src/' );
		foreach( $data['files'] as $file )
		{
			require_once( $file );
			echo '.';
		}
		remark( "\n" );

		$command	= "phpunit";
		foreach( $config['unitTestOptions'] as $key => $value )
			$command	.= " --".$key." ".$value;
		print( "\nRunning Unit Tests:\n\r" );
		$command	.= " Test_AllTests";
		passthru( $command );
	}

	protected function runTestOfClass( $className )
	{
		$parts		= explode( "_", $className );
		$fileKey	= array_pop( $parts );
		$suffix		= $fileKey == "All" ? "Tests" : "Test";
		while( $parts )
			$fileKey	= strtolower( array_pop( $parts ) )."/".$fileKey;

		$testClass	= "Test_".$className.$suffix;
		$testFile	= "Test/".$fileKey.$suffix.".php";
		if( !file_exists( $testFile ) )
			throw new RuntimeException( 'Test Class File "'.$testFile.'" is not existing' );
		echo "\nTesting Class: ".$className."\n\n";
		
		passthru( "phpunit ".$testClass, $return );
	} 
}
?>
