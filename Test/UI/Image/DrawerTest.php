<?php
/**
 *	TestUnit of UI_Image_Error.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Inverter.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_Error
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
/*class Test_UI_Image_ErrorTest extends PHPUnit_Framework_TestCase
{
	public function testConstruct()
	{
		$path		= dirname( __FILE__ )."/";
		$fileName	= $path."error.png";
		$assertion	= file_get_contents( $fileName );
		
		ob_start();
		UI_Image_Error::$sendHeader = FALSE;
		new UI_Image_Error( "Test Text" );
		$creation	= ob_get_clean();
		$this->assertEquals( $assertion, $creation );
	}
}*/
?>