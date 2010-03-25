<?php
/**
 *	TestUnit of UI_Image_Error.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_Error
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.ui.image.Error' );
import( 'de.ceus-media.file.Reader' );
/**
 *	TestUnit of Inverter.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_Error
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
class Tests_UI_Image_ErrorTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
	}
	
	public function testConstruct()
	{
		$fileName	= $this->path."assertError.png";
		
		ob_start();
		UI_Image_Error::$sendHeader = FALSE;
		new UI_Image_Error( "Test Text" );
		file_put_contents( $this->path."targetError.png", ob_get_clean() );

		$file	= new File_Reader( $this->path."targetError.png" );
		$this->assertTrue( $file->equals( $this->path."assertError.png" ) );
		
		@unlink( $this->path."targetError.png" );
	}
}
?>