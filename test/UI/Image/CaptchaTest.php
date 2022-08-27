<?php
/**
 *	TestUnit of UI_Image_Captcha.
 *	@package		Tests.ui
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			05.05.2008
 *
 */
declare( strict_types = 1 );

namespace CeusMedia\Common\Test\UI\Image;

use CeusMedia\Common\Test\BaseCase;
use CeusMedia\Common\UI\Image\Captcha;

/**
 *	TestUnit of UI_Image_Captcha.
 *	@package		Tests.ui
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			05.05.2008
 *
 */
class CaptchaTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		if( !extension_loaded( 'gd' ) )
			$this->markTestSkipped( 'Missing gd support' );
		$this->path		= dirname( __FILE__ )."/";
		$this->captcha	= new Captcha();
		$this->captcha->font	= $this->path."tahoma.ttf";
		$this->captcha->width	= 150;
		$this->captcha->angle	= 45;
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		@unlink( $this->path."captcha.created.jpg" );
	}

	/**
	 *	Tests Method 'generateWord'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGenerateWord()
	{
		$word		= $this->captcha->generateWord();

		$assertion	= TRUE;
		$creation	= is_string( $word );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= (bool) preg_match( "@[0-9]@", $word );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= (bool) preg_match( "@[A-Z]@", $word );
		$this->assertEquals( $assertion, $creation );

		$captcha	= new Captcha();
		$captcha->useLarges	= TRUE;
		$captcha->useDigits	= TRUE;
		$captcha->length	= 50;
		$captcha->font		= $this->path."tahoma.ttf";
		$word		= $captcha->generateWord();

		$assertion	= TRUE;
		$creation	= is_string( $word );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= (bool) preg_match( "@[A-Z]|[0-9]@", $word );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'generateImage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGenerateImageDifference()
	{
		$result		= $this->captcha->generateImage( "abc123", $this->path."captcha.created.jpg" );

		$assertion	= TRUE;
		$creation	= is_int( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $result > 0;
		$this->assertEquals( $assertion, $creation );

		$oldImage	= file_get_contents( $this->path."captcha.created.jpg" );
		$result		= $this->captcha->generateImage( "abc123", $this->path."captcha.created.jpg" );
		$newImage	= file_get_contents( $this->path."captcha.created.jpg" );

		$assertion	= TRUE;
		$creation	= is_int( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $result > 0;
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $newImage	!= $oldImage;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'generateImage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGenerateImageConstant()
	{
		$this->captcha->angle	= 0;
		$this->captcha->offsetX	= 0;
		$this->captcha->offsetY	= 0;

		$result		= $this->captcha->generateImage( "abc123", $this->path."captcha.created.jpg" );

		$assertion	= TRUE;
		$creation	= is_int( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $result > 0;
		$this->assertEquals( $assertion, $creation );

		$oldImage	= file_get_contents( $this->path."captcha.created.jpg" );
		$result		= $this->captcha->generateImage( "abc123", $this->path."captcha.created.jpg" );
		$newImage	= file_get_contents( $this->path."captcha.created.jpg" );

		$assertion	= TRUE;
		$creation	= is_int( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $result > 0;
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $newImage	== $oldImage;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'generateImage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGenerateImageException1()
	{
		$this->captcha->textColor	= "not_an_array";
		$this->expectException( 'InvalidArgumentException' );
		$this->captcha->generateImage( "not_relevant", "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'generateImage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGenerateImageException2()
	{
		$this->captcha->textColor	= array( 1, 2 );
		$this->expectException( 'InvalidArgumentException' );
		$this->captcha->generateImage( "not_relevant", "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'generateImage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGenerateImageException3()
	{
		$this->captcha->background	= "not_an_array";
		$this->expectException( 'InvalidArgumentException' );
		$this->captcha->generateImage( "not_relevant", "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'generateImage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGenerateImageException4()
	{
		$this->captcha->background	= array( 1, 2 );
		$this->expectException( 'InvalidArgumentException' );
		$this->captcha->generateImage( "not_relevant", "not_relevant" );
	}
}
