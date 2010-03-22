<?php
class Go_ClassUnitTester
{
	public function __construct( $arguments )
	{
		$classKey	= $arguments[1];
		$parts		= explode( "_", $classKey );
		$fileKey	= array_pop( $parts );
		$suffix		= $fileKey == "All" ? "Tests" : "Test";
		while( $parts )
			$fileKey	= strtolower( array_pop( $parts ) )."/".$fileKey;

		$testClass	= "Test_".$arguments[1].$suffix;
		$testFile	= "test/".$fileKey.$suffix.".php";
		if( !file_exists( $testFile ) )
			throw new RuntimeException( 'Test Class File "'.$testFile.'" is not existing.' );
		echo "\nTesting Class: ".$classKey."\n\n";
		passthru( "phpunit ".$testClass, $return );
	}
}
?>
