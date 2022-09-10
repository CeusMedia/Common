<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	Basic Image Creation.
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

/**
 *	Basic Image Creation.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Drawer
{
	protected $image;

	protected $type	= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		resource		$image      Image Resource, can be created with UI_Image_Creator
	 *	@return		void
	 */
	public function __construct( $image )
	{
		$this->setImage( $image );
	}

	public function drawBorder( int $color, int $width = 1 )
	{
		for( $i = 0; $i < $width; $i++ )
			$this->drawRectangle( $i, $i, imagesx( $this->image ) - $i - 1, imagesy( $this->image ) - $i - 1, $color );
	}

	public function drawLine( int $x0, int $y0, int $x1, int $y1, int $color ): bool
	{
		return imageline( $this->image, $x0, $y0, $x1, $y1, $color );
	}

	public function drawPixel( int $x, int $y, int $color ): bool
	{
		return imagesetpixel( $this->image, $x, $y, $color );
	}

	public function drawRectangle( $x0, int $y0, int $x1, int $y1, int $color ): bool
	{
		return imagerectangle( $this->image, $x0, $y0, $x1, $y1, $color );
	}

	public function drawString( int $x, int $y, string $text, $size, int $color ): bool
	{
		return imagestring( $this->image, $size, $x, $y, $text, $color );
	}

	public function fill( int $color ): bool
	{
		return imagefilledrectangle( $this->image, 0, 0, imagesx( $this->image ) - 1, imagesy( $this->image ) - 1, $color );
	}

	public function fillRectangle( int $x0, int $y0, int $x1, int $y1, int $color ): bool
	{
		return imagefilledrectangle( $this->image, $x0, $y0, $x1, $y1, $color );
	}

	public function getColor( int $red, int $green, int $blue, int $alpha = 0 ): ?int
	{
		$color = imagecolorallocatealpha( $this->image, $red, $green, $blue, $alpha );
        return $color ?: NULL;
	}

	public function getImage()
	{
		return $this->image;
	}

/*	public function isSet()
	{
		return isset( $this->image );
	}
*/
	/**
	 *	Sets Image Handler.
	 *	@access		public
	 *	@param		resource		$image      Image Handler
	 *	@return		void
	 */
	public function setImage( $image )
	{
		$this->image = $image;
	}

	public function show( int $quality = 100 )
	{
		Printer::showImage( $this->image, $this->type, $quality );
		die;
	}
}
