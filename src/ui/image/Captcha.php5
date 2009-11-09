<?php
/**
 *	Simple CAPTCHA Generator.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		ui.image
 *	@uses			Alg_Randomizer
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.05.2005
 *	@version		0.6
 */
import( 'de.ceus-media.alg.Randomizer' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Simple CAPTCHA Generator.
 *	@category		cmClasses
 *	@package		ui.image
 *	@uses			Alg_Randomizer
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.05.2005
 *	@version		0.6
 */
class UI_Image_Captcha
{
	/**	@var		bool		$useDigits		Flag: use Digits */
	public $useDigits			= FALSE;
	/**	@var		bool		$useLarges		Flag: use large Letters */
	public $useLarges			= FALSE;
	/**	@var		bool		$useSmalls		Flag: use small Letters */
	public $useSmalls			= TRUE;
	/**	@var		bool		$useSigns		Flag: use Signs */
	public $useSigns			= FALSE;
	/**	@var		bool		$unique			Flag: every Sign may only appear once in randomized String */
	public $unique				= FALSE;
	/**	@var		int			$length			Number of CAPTCHA Signs */
	public $length				= 4;
	/**	@var		string		$font			File Name of True Type Font to use */
	public $font				= "tahoma.ttf";
	/**	@var		int			$fontSize		Font Size */
	public $fontSize			= 14;
	/**	@var		int			$width			Width of CAPTCHA Image */
	public $width				= 100;
	/**	@var		int			$height			Height of CAPTCHA Image */
	public $height				= 40;
	/**	@var		int			$angle			Angle of maximal Rotation in ° */
	public $angle				= 50;
	/**	@var		int			$offsetX		Maximum Offset in X-Axis */
	public $offsetX				= 5;
	/**	@var		int			$offsetY		Maximum Offset in Y-Axis */
	public $offsetY				= 10;
	/**	@var		array		$textColor		List of RGB Values of Text */
	public $textColor			= array( 0, 0, 0 );
	/**	@var		array		$background		List of RGB Values of Background */
	public $background			= array( 255, 255, 255 );
	/**	@var		int			$quality		Quality of JPEG Image in % */
	public $quality				= 90;

	/**
	 *	Generates CAPTCHA Word.
	 *	@access		public
	 *	@return		string
	 */
	public function generateWord()
	{
		$rand	= new Alg_Randomizer();
		$rand->useSmalls	= $this->useSmalls;
		$rand->useLarges	= $this->useLarges;
		$rand->useDigits	= $this->useDigits;
		$rand->useSigns		= $this->useSigns;
		$rand->unique		= $this->unique;
		return $rand->get( $this->length );
	}
	
	/**
	 *	Generates Captcha Image for Captcha Word.
	 *	@access		public
	 *	@param		string		$word		Captcha Word
	 *	@param		string		$fileName	File Name to write Captcha Image to
	 *	@return		int
	 */
	public function generateImage( $word, $fileName )
	{
		if( !( is_array( $this->textColor ) && count( $this->textColor ) == 3 ) )
			throw new InvalidArgumentException( 'Text Color must be an Array of 3 decimal Values.' );
		if( !( is_array( $this->background ) && count( $this->background ) == 3 ) )
			throw new InvalidArgumentException( 'Background Color must be an Array of 3 decimal Values.' );

		$image		= imagecreate( $this->width, $this->height );
		$backColor	= imagecolorallocate( $image, $this->background[0], $this->background[1], $this->background[2] );
		$frontColor	= imagecolorallocate( $image, $this->textColor[0], $this->textColor[1], $this->textColor[2] );
	
		for( $i=0; $i<strlen( $word ); $i++ )
		{
			//  --  ANGLE  --  //
			$angle	= 0;
			if( $this->angle )
			{
				$rand	= 2 * rand() / getrandmax() - 1;											//  randomize Float between -1 and 1
				$angle	= round( $rand * $this->angle, 0 );											//  calculate rounded Angle
			}

			//  --  POSITION X  --  //
			$offset	= 0;
			if( $this->offsetX )
			{
				$rand	= 2 * rand() / getrandmax() - 1;											//  randomize Float between -1 and 1
				$offset	= round( $rand * $this->offsetX, 0 );										//  calculate rounded Offset
			}
			$posX	= $i * 20 + $offset + 10;

			//  --  POSITION Y  --  //
			$offset	= 0;
			if( $this->offsetY )
			{
				$rand	= 2 * rand() / getrandmax() - 1;											//  randomize Float between -1 and 1
				$offset	= round( $rand * $this->offsetY, 0 );										//  calculate rounded Offset
			}
			$posY	= $offset + round( $this->height / 2, 0 ) + 5;

			$char	= $word[$i];
			imagettftext( $image, $this->fontSize, $angle, $posX, $posY, $frontColor, $this->font, $char );
		}
		ob_start();
		imagejpeg( $image, NULL, $this->quality );
		return File_Writer::save( $fileName, ob_get_clean() );
	}
}
?>