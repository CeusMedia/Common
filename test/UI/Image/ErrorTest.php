<?php
/**
 *	TestUnit of UI_Image_Error.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.06.2008
 *
 */
declare( strict_types = 1 );

namespace CeusMedia\Common\Test\UI\Image;

use CeusMedia\Common\Test\BaseCase;
use CeusMedia\Common\UI\Image\Error;

/**
 *	TestUnit of Inverter.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.06.2008
 *
 */
class ErrorTest extends BaseCase
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
		Error::$sendHeader = FALSE;
		new Error( "Test Text" );
		file_put_contents( $this->path."targetError.png", ob_get_clean() );


		$image	= imagecreatefrompng( $this->path."targetError.png" );
		$this->assertEquals( 200, imagesx( $image ) );
		$this->assertEquals( 20, imagesy( $image ) );

		@unlink( $this->path."targetError.png" );
	}
}
