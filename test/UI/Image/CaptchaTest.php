<?php
/**
 *	TestUnit of UI_Image_Captcha.
 *	@package		Tests.ui
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
declare( strict_types = 1 );

namespace CeusMedia\Common\Test\UI\Image;

use CeusMedia\Common\Test\BaseCase;
use CeusMedia\Common\UI\Image\Captcha;

/**
 *	TestUnit of UI_Image_Captcha.
 *	@package		Tests.ui
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class CaptchaTest extends BaseCase
{
	/** @var Captcha  */
	protected $captcha;

	/** @var string  */
	protected $path;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		if( !extension_loaded( 'gd' ) )
			$this->markTestSkipped( 'Missing gd support' );
		$this->path		= dirname( __FILE__ )."/assets/";
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

		$this->assertIsString( $word );
		$this->assertDoesNotMatchRegularExpression( "@[0-9]@", $word );
		$this->assertDoesNotMatchRegularExpression( "@[A-Z]@", $word );

		$captcha	= new Captcha();
		$captcha->useLarges	= TRUE;
		$captcha->useDigits	= TRUE;
		$captcha->length	= 50;
		$captcha->font		= $this->path."tahoma.ttf";
		$word		= $captcha->generateWord();

		$this->assertIsString( $word );
		$this->assertMatchesRegularExpression( "@[A-Z]|[0-9]@", $word );
	}

	/**
	 *	Tests Method 'generateImage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGenerateImageDifference()
	{
		$result		= $this->captcha->generateImage( "abc123", $this->path."captcha.created.jpg" );

		$this->assertIsInt( $result );
		$this->assertGreaterThan( 0, $result );

		$oldImage	= file_get_contents( $this->path."captcha.created.jpg" );
		$result		= $this->captcha->generateImage( "abc123", $this->path."captcha.created.jpg" );
		$newImage	= file_get_contents( $this->path."captcha.created.jpg" );

		$this->assertIsInt( $result );
		$this->assertGreaterThan( 0, $result );

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

		$this->assertIsInt( $result );
		$this->assertGreaterThan( 0, $result );

		$oldImage	= file_get_contents( $this->path."captcha.created.jpg" );
		$result		= $this->captcha->generateImage( "abc123", $this->path."captcha.created.jpg" );
		$newImage	= file_get_contents( $this->path."captcha.created.jpg" );

		$this->assertIsInt( $result );
		$this->assertGreaterThan( 0, $result );

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
