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
require_once '../autoload.php5';
require_once( 'PHPUnit/Framework/TestCase.php' ); 
/**
 *	TestUnit of Inverter.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_Error
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
class UI_Image_ErrorTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
	}
	
	public function testConstruct()
	{
		$fileName	= $this->path."assertError.png";
		$assertion	= file_get_contents( $fileName );
		
		ob_start();
		UI_Image_Error::$sendHeader = FALSE;
		new UI_Image_Error( "Test Text" );
		$creation	= ob_get_clean();
		$this->assertEquals( $assertion, $creation );
	}
}
?>