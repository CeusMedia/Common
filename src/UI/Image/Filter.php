<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	Image filter.
 *
 *	Copyright (c) 2010-2023 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\Image;

use CeusMedia\Common\UI\Image;

/**
 *	Image filter.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://www.php.net/manual/en/function.imagefilter.php
 *	@see			http://www.tuxradar.com/practicalphp/11/2/15
 */
class Filter
{
	/**	@var		Image		$resource		Image resource object */
	protected $image;

	public function __construct( Image $image )
	{
		$this->image	= $image;
	}

	/**
	 *	Blurs the image using the Gaussian method.
	 *	@access		public
	 *	@return		boolean
	 */
	public function blurGaussian(): bool
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_GAUSSIAN_BLUR );
	}

	/**
	 *	Blurs the image.
	 *	@access		public
	 *	@return		boolean
	 */
	public function blurSelective(): bool
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_SELECTIVE_BLUR );
	}

	/**
	 *	Changes the brightness of the image.
	 *	@access		public
	 *	@param		integer		$level		Value between -255 and 255
	 *	@return		boolean
	 */
	public function brightness( int $level ): bool
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_BRIGHTNESS, $level );
	}

	/**
	 *	Adds or subtracts colors.
	 *	@access		public
	 *	@param		integer		$red		Red component, value between -255 and 255
	 *	@param		integer		$green		Green component, value between -255 and 255
	 *	@param		integer		$blue		Blue component, value between -255 and 255
	 *	@param		integer		$alpha		Alpha channel, value between 0 (opaque) and 127 (transparent)
	 *	@return		boolean
	 */
	public function colorize( int $red, int $green, int $blue, int $alpha = 0 ): bool
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_COLORIZE, $red, $green, $blue, $alpha );
	}

	/**
	 *	Changes the contrast of the image.
	 *	@access		public
	 *	@param		integer		$level		Value up to 100
	 *	@return		boolean
	 */
	public function contrast( int $level ): bool
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_CONTRAST, $level );
	}

	/**
	 *	Uses edge detection to highlight the edges in the image.
	 *	@access		public
	 *	@return		boolean
	 */
	public function detectEdges(): bool
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_EDGEDETECT );
	}

	/**
	 *	Embosses the image.
	 *	@access		public
	 *	@return		boolean
	 */
	public function emboss(): bool
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_EMBOSS );
	}

	public function gamma( int $level ): bool
	{
		return imagegammacorrect( $this->image->getResource(), 1.0, (double) $level );
	}

	/**
	 *	Converts the image into grayscale.
	 *	@access		public
	 *	@return		boolean
	 */
	public function grayscale(): bool
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_GRAYSCALE );
	}

	/**
	 *	Reverses all colors of the image.
	 *	@access		public
	 *	@return		boolean
	 */
	public function negate(): bool
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_NEGATE );
	}

	/**
	 *	Applies pixelation effect to the image.
	 *	@access		public
	 *	@param		integer		$size		Block size in pixels
	 *	@param		boolean		$effect		Flag: activate advanced pixelation effect
	 *	@return		boolean
	 */
	public function pixelate( int $size, bool $effect = FALSE ): bool
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_PIXELATE, $size, (int) $effect );
	}

	/**
	 *	Uses mean removal to achieve a "sketchy" effect.
	 *	@access		public
	 *	@return		boolean
	 */
	public function removeMean(): bool
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_MEAN_REMOVAL );
	}

	/**
	 *	Makes the image smoother.
	 *	@access		public
	 *	@param		integer		$weight		Level of smoothness
	 *	@return		boolean
	 */
	public function smooth( int $weight ): bool
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_SMOOTH, $weight );
	}
}
