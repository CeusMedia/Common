<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.alg.Randomizer' );
/**
 *	Simple Captcha Generator.
 *	@package		ui
 *	@subpackage		image
 *	@extends		ADT_OptionObject
 *	@uses			Alg_Randomizer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.05.2005
 *	@version		0.1
 */
/**
 *	Simple Captcha Generator.
 *	@package		ui
 *	@subpackage		image
 *	@extends		ADT_OptionObject
 *	@uses			Alg_Randomizer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.05.2005
 *	@version		0.1
 */
class Captcha extends ADT_OptionObject
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setOption( 'useSmalls', true );
		$this->setOption( 'useLarges', false );
		$this->setOption( 'useSigns', false );
		$this->setOption( 'useDigits', false );
		$this->setOption( 'length', 4 );
		$this->setOption( 'font', "tahoma.ttf" );
		$this->setOption( 'fontsize', 14 );
		$this->setOption( 'width', 100 );
		$this->setOption( 'height', 40 );
		$this->setOption( 'angles', 50 );
		$this->setOption( 'moves', 10 );
		$this->setOption( 'textcolor', array( 0, 0, 0 ) );
		$this->setOption( 'background', array( 255, 255, 255 ) );
		$this->setOption( 'quality', 90 );
	}

	/**
	 *	Generates Captcha Word.
	 *	@access		public
	 *	@return		string
	 */
	function generateWord()
	{
		$rand	= new Alg_Randomizer();
		$rand->setOption( 'useSmalls',	$this->getOption( 'useSmalls' ) );
		$rand->setOption( 'useLarges',	$this->getOption( 'useLarges' ) );
		$rand->setOption( 'useDigits',	$this->getOption( 'useDigits' ) );
		$rand->setOption( 'useSigns',	$this->getOption( 'useSigns' ) );
		$rand->setOption( 'length',		$this->getOption( 'length' ) );
		return $rand->get();
	}
	
	/**
	 *	Generates Captcha Image for Captcha Word.
	 *	@access		public
	 *	@param		string		$word		Captcha Word
	 *	@param		string		$filename		File Name to write Captcha Image to
	 *	@param		bool			$debug		Switch: Debug-Mode
	 *	@return		void
	 */
	function generateImage( $word, $filename, $debug = false )
	{
		$background	= $this->getOption( 'background' );
		$textcolor	= $this->getOption( 'textcolor' );
		$fontsize		= $this->getOption( 'fontsize' );
		$font		= $this->getOption( 'font' );
		$fh	= fopen( $filename, 'w' );
		fclose( $fh );
		$image	= imagecreate( $this->getOption( 'width' ), $this->getOption( 'height' ) );
		$bc	= imagecolorallocate( $image, $background[0], $background[1], $background[2] );
		$fc	= imagecolorallocate( $image, $textcolor[0], $textcolor[1], $textcolor[2] );

		$signs	= array( 1, -1 );
//		srand((float) microtime() * 10000000);			// for PHP <4.2.0
		for( $i=0; $i<strlen( $word ); $i++ )
		{
			$char	= $word[$i];
			$sign	= $signs[array_rand( $signs, 1 )];
			$angle	= $sign * rand( 0, $this->getOption( 'angles' ) ) - $this->getOption( 'angles' ) / 2;
			$pos_x	= $i * 20 + 10;
			$pos_y	= $sign * rand( 0, $this->getOption( 'moves' ) ) + $this->getOption( 'height' ) / 2 + 5;
			if( $debug )
				remark( "<hr/>Char -> ".$char, array(
					"Angle"	=> $angle,
					"Sign"	=> $sign,
					"Pos X"	=> $pos_x,
					"Pos Y"	=> $pos_y,
					) );
			imagettftext( $image, $fontsize, $angle, $pos_x, $pos_y, $fc, $font, $char );
		}
		imagejpeg( $image, $filename, $this->getOption( 'quality' ) );
	}
}
?>