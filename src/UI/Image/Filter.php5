<?php
/**
 *	Image filter.
 *
 *	Copyright (c) 2010-2012 Christian Würker (ceusmedia.com)
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
 *	@package		UI.Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Image filter.
 *	@category		cmClasses
 *	@package		UI.Image
 *	@uses			UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@see			http://www.php.net/manual/en/function.imagefilter.php
 *	@see			http://www.tuxradar.com/practicalphp/11/2/15
 */
class UI_Image_Filter
{
	/**	@var		UI_Image		$resource		Image resource object */
	protected $image;

	public function __construct( UI_Image $image )
	{
		$this->image	= $image;
	}

	/**
	 *	Blurs the image using the Gaussian method.
	 *	@access		public
	 *	@return		boolean
	 */
	public function blurGaussian()
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_GAUSSIAN_BLUR );
	}

	/**
	 *	Blurs the image.
	 *	@access		public
	 *	@return		boolean
	 */
	public function blurSelective()
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_SELECTIVE_BLUR );
	}

	/**
	 *	Changes the brightness of the image.
	 *	@access		public
	 *	@param		integer		$level		Value between -255 and 255
	 *	@return		boolean
	 */
	public function brightness( $level )
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_BRIGHTNESS, $level );
	}

	/**
	 *	Adds or subtracts colors.
	 *	@access		public
	 *	@param		integer		$red		Red component, value between -255 and 255
	 *	@param		integer		$red		Green component, value between -255 and 255
	 *	@param		integer		$red		Blue component, value between -255 and 255
	 *	@param		integer		$alpha		Alpha channel, value between 0 (opacue) and 127 (transparent)
	 *	@return		boolean
	 */
	public function colorize( $red, $green, $blue, $alpha = 0 )
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_COLORIZE, $red, $green, $blue, $alpha );
	}

	/**
	 *	Changes the contrast of the image.
	 *	@access		public
	 *	@param		integer		$level		Value up to 100
	 *	@return		boolean
	 */
	public function contrast( $level )
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_CONTRAST, $level );
	}

	public function gamma( $level )
	{
		return imagegammacorrect( $this->image->getResource(), 1.0, (double) $level );
	}

	/**
	 *	Uses edge detection to highlight the edges in the image.
	 *	@access		public
	 *	@return		boolean
	 */
	public function detectEdges()
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_EDGEDETECT );
	}

	/**
	 *	Embosses the image.
	 *	@access		public
	 *	@return		boolean
	 */
	public function emboss()
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_EMBOSS );
	}

	/**
	 *	Converts the image into grayscale.
	 *	@access		public
	 *	@return		boolean
	 */
	public function grayscale()
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_GRAYSCALE );
	}

	/**
	 *	Reverses all colors of the image.
	 *	@access		public
	 *	@return		boolean
	 */
	public function negate()
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

	public function pixelate( $size, $effect = FALSE )
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_PIXELATE, $size, $effect );
	}

	/**
	 *	Uses mean removal to achieve a "sketchy" effect.
	 *	@access		public
	 *	@return		boolean
	 */
	public function removeMean()
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_MEAN_REMOVAL );
	}

	/**
	 *	Makes the image smoother.
	 *	@access		public
	 *	@param		integer		$weight		Level of smoothness
	 *	@return		boolean
	 */
	public function smooth( $weight )
	{
		return imagefilter( $this->image->getResource(), IMG_FILTER_SMOOTH, $weight );
	}
}
?>