<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	Simple CAPTCHA Generator.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\Image;

use CeusMedia\Common\Alg\Randomizer;
use CeusMedia\Common\FS\File\Writer as FileWriter;

use InvalidArgumentException;
use RuntimeException;

/**
 *	Simple CAPTCHA Generator.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			apply background color
 */
class Captcha
{
	/**	@var		bool		$useDigits		Flag: use Digits */
	public bool $useDigits		= FALSE;

	/**	@var		bool		$useLarges		Flag: use large Letters */
	public bool $useLarges		= FALSE;

	/**	@var		bool		$useSmalls		Flag: use small Letters */
	public bool $useSmalls		= TRUE;

	/**	@var		bool		$unique			Flag: every Sign may only appear once in randomized String */
	public bool $unique			= FALSE;

	/**	@var		int			$length			Number of CAPTCHA Signs */
	public int $length			= 4;

	/**	@var		string		$font			File Path of True Type Font to use */
	public string $font			= '';

	/**	@var		int			$fontSize		Font Size */
	public int $fontSize		= 14;

	/**	@var		int			$width			Width of CAPTCHA Image */
	public int $width			= 100;

	/**	@var		int			$height			Height of CAPTCHA Image */
	public int $height			= 40;

	/**	@var		int			$angle			Angle of maximal Rotation in ° */
	public int $angle			= 50;

	/**	@var		int			$offsetX		Maximum Offset in X-Axis */
	public int $offsetX			= 5;

	/**	@var		int			$offsetY		Maximum Offset in Y-Axis */
	public int $offsetY			= 10;

	/**	@var		array		$textColor		List of RGB Values of Text */
	public array $textColor		= [0, 0, 0];

	/**	@var		array		$background		List of RGB Values of Background */
	public array $background	= [255, 255, 255];

	/**	@var		int			$quality		Quality of JPEG Image in % */
	public int $quality			= 90;

	/**
	 *	Generates CAPTCHA image file and returns generated and used CAPTCHA word.
	 *	@access		public
	 *	@param		string		$fileName		Name of CAPTCHA image file to create
	 *	@return		string		CAPTCHA word rendered in image file
	 */
	public function generate( string $fileName ): string
	{
		$word	= $this->generateWord();
		$this->generateImage( $word, $fileName );
		return $word;
	}

	/**
	 *	Generates Captcha Image for Captcha Word.
 	 *	Saves image if file name is set.
	 *	Otherwise, returns binary content of image.
	 *	@access		public
	 *	@param		string			$word		Captcha Word
	 *	@param		string|NULL		$fileName	File Name to write Captcha Image to
	 *	@return		int|string
	 */
	public function generateImage( string $word, ?string $fileName = NULL )
	{
		if( !$this->font )
			throw new RuntimeException( 'No font defined' );
		if( count( $this->textColor ) !== 3 )
			throw new InvalidArgumentException( 'Text Color must be an Array of 3 decimal Values.' );
		if( count( $this->background ) !== 3 )
			throw new InvalidArgumentException( 'Background Color must be an Array of 3 decimal Values.' );

		$image		= imagecreate( $this->width, $this->height );
		$backColor	= imagecolorallocate( $image, $this->background[0], $this->background[1], $this->background[2] );
		$frontColor	= imagecolorallocate( $image, $this->textColor[0], $this->textColor[1], $this->textColor[2] );

		for( $i=0; $i<strlen( $word ); $i++ ){
			//  --  ANGLE  --  //
			$angle	= 0;
			if( $this->angle ){
				//  randomize Float between -1 and 1
				$rand	= 2 * rand() / getrandmax() - 1;
				//  calculate rounded Angle
				$angle	= round( $rand * $this->angle);
			}

			//  --  POSITION X  --  //
			$offset	= 0;
			if( $this->offsetX ){
				//  randomize Float between -1 and 1
				$rand	= 2 * rand() / getrandmax() - 1;
				//  calculate rounded Offset
				$offset	= round( $rand * $this->offsetX );
			}
			$posX	= $i * 20 + $offset + 10;

			//  --  POSITION Y  --  //
			$offset	= 0;
			if( $this->offsetY ){
				//  randomize Float between -1 and 1
				$rand	= 2 * rand() / getrandmax() - 1;
				//  calculate rounded Offset
				$offset	= (int) round( $rand * $this->offsetY );
			}
			$posY	= $offset + (int) round( $this->height / 2 ) + 5;

			$char	= $word[$i];
			imagettftext( $image, $this->fontSize, $angle, $posX, $posY, $frontColor, $this->font, $char );
		}
		ob_start();
		imagejpeg( $image, NULL, $this->quality );
		$content	= ob_get_clean();
		if( FALSE === $content )
			throw new RuntimeException( 'Generating image failed' );
		if( $fileName )
			return FileWriter::save( $fileName, $content );
		return $content;
	}

	/**
	 *	Generates CAPTCHA Word.
	 *	@access		public
	 *	@return		string
	 */
	public function generateWord(): string
	{
		$rand				= new Randomizer();
		$rand->digits		= "2345678";
		$rand->larges		= "ABCDEFGHIKLMNPQRSTUVWXYZ";
		$rand->smalls		= "abcdefghiklmnpqrstuvwxyz";
		$rand->useSmalls	= $this->useSmalls;
		$rand->useLarges	= $this->useLarges;
		$rand->useDigits	= $this->useDigits;
		$rand->useSigns		= FALSE;
		$rand->unique		= $this->unique;
		return $rand->get( $this->length );
	}
}
