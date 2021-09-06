<?php
/**
 *	TestUnit of UI_Image_Error.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Inverter.
 *	@package		Tests.ui.image
 *	@extends		Test_Case
 *	@uses			UI_Image_Error
 *	@uses			FS_File_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
class Test_UI_Image_ErrorTest extends Test_Case
{
	public function setUp(): void
	{
		if( !extension_loaded( 'gd' ) )
			$this->markTestSkipped( 'Missing gd support' );

		$this->path	= dirname( __FILE__ )."/";
	}

	public function testConstruct()
	{
		$fileName	= $this->path."assertError.png";
		@unlink( $this->path."targetError.png" );

		ob_start();
		UI_Image_Error::$sendHeader = FALSE;
		new UI_Image_Error( "Test Text" );
		file_put_contents( $this->path."targetError.png", ob_get_clean() );


		$image	= imagecreatefrompng( $this->path."targetError.png" );
		$this->assertEquals( 200, imagesx( $image ) );
		$this->assertEquals( 20, imagesy( $image ) );

		@unlink( $this->path."targetError.png" );
	}
}
