<?php
/**
 *	TestUnit of UI_Image_Captcha.
 *	@package		Tests.ui
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_Captcha
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.05.2008
 *	@version		0.1
 */
require_once '../autoload.php5';
require_once( 'PHPUnit/Framework/TestCase.php' ); 
/**
 *	TestUnit of UI_Image_Captcha.
 *	@package		Tests.ui
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_Captcha
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.05.2008
 *	@version		0.1
 */
class UI_Image_CaptchaTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= dirname( __FILE__ )."/";
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->captcha	= new UI_Image_Captcha();
		$this->captcha->font	= $this->path."tahoma.ttf";
		$this->captcha->width	= 150;
		$this->captcha->angle	= 45;
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
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

		$captcha	= new UI_Image_Captcha();
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
		$this->setExpectedException( 'InvalidArgumentException' );
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
		$this->setExpectedException( 'InvalidArgumentException' );
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
		$this->setExpectedException( 'InvalidArgumentException' );
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
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->captcha->generateImage( "not_relevant", "not_relevant" );
	}
}
?>