<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of UI_Image_Error.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\UI\Image;

use CeusMedia\Common\UI\Image\Error;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Inverter.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ErrorTest extends BaseCase
{
	/** @var string  */
	protected $path;

	public function setUp(): void
	{
		if( !extension_loaded( 'gd' ) )
			$this->markTestSkipped( 'Missing gd support' );

		$this->path	= dirname( __FILE__ )."/assets/";
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
		self::assertEquals( 200, imagesx( $image ) );
		self::assertEquals( 20, imagesy( $image ) );

		@unlink( $this->path."targetError.png" );
	}
}
