<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	Processor for resizing, scaling and rotating an image.
 *
 *	Copyright (c) 2010-2025 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\Image;

use CeusMedia\Common\UI\Image;
use InvalidArgumentException;
use OutOfRangeException;
use ReflectionFunction;

/**
 *	Processor for resizing, scaling and rotating an image.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Processing
{
	/**	@var		Image			$image			Image resource object */
	protected Image $image;

	/**	@param		integer			$maxMegaPixel	Maximum megapixels */
	public int $maxMegaPixels		= 0;

	/**
	 *	Constructor.
	 *	Sets initial image resource object.
	 *	@access		public
	 *	@param		Image			$image			Image resource object
	 *	@param		integer			$maxMegaPixels	Maximum megapixels, default: 0 - unlimited
	 *	@return		void
	 */
	public function __construct( Image $image, int $maxMegaPixels = 0 )
	{
		$this->image			= $image;
		$this->maxMegaPixels	= $maxMegaPixels;
	}

	/**
	 *	Crop image.
	 *	@access		public
	 *	@param		integer		$startX			Left margin
	 *	@param		integer		$startY			Top margin
	 *	@param		integer		$width			New width
	 *	@param		integer		$height			New height
	 *	@return		boolean						Image has been copped
	 *	@throws		InvalidArgumentException	if left margin is not an integer value
	 *	@throws		InvalidArgumentException	if top margin is not an integer value
	 *	@throws		InvalidArgumentException	if width is not an integer value
	 *	@throws		InvalidArgumentException	if height is not an integer value
	 *	@throws		OutOfRangeException			if width is lower than 1
	 *	@throws		OutOfRangeException			if height is lower than 1
	 *	@throws		OutOfRangeException			if resulting image has more mega pixels than allowed
	 */
	public function crop( int $startX, int $startY, int $width, int $height ): bool
	{
		if( $width < 1 )
			throw new OutOfRangeException( 'Width must be at least 1' );
		if( $height < 1 )
			throw new OutOfRangeException( 'Height must be at least 1' );
		$image	= new Image;
		$image->create( $width, $height );
		$image->setType( $this->image->getType() );

		imagecopy( $image->getResource(), $this->image->getResource(), 0, 0, $startX, $startY, $width, $height );
		//  replace held image resource object by result
		$this->image->setResource( $image->getResource() );
		return TRUE;
	}

	/**
	 *	Flips image horizontally or vertically.
	 *	@access		public
	 *	@param		integer		$mode		0: horizontally, 1: vertically
	 *	@return		boolean		Image has been flipped
	 */
	public function flip( int $mode = 0 ): bool
	{
		$image	= new Image;
		$width	= $this->image->getWidth();
		$height	= $this->image->getHeight();
		$image->create( $width, $height );
		if( $mode == 0 ){
			imagecopyresampled(
				$image->getResource(),	$this->image->getResource(),
				0, 0,
				0, ( $height - 1),
				$width, $height,
				$width, 0 - $height
			);
		}
		else{
			imagecopyresampled(
				$image->getResource(),	$this->image->getResource(),
				0, 0,
				( $width - 1), 0,
				$width, $height,
				0 - $width, $height
			);
		}
		//  replace held image resource object by result
		$this->image->setResource( $image->getResource() );
		return TRUE;
	}

	/**
	 *	Resizes image.
	 *	@access		public
	 *	@param		integer		$width			New width
	 *	@param		integer		$height			New height
	 *	@param		boolean		$interpolate	Flag: use interpolation
	 *	@return		boolean						Image has been resized
	 *	@throws		InvalidArgumentException	if width is not an integer value
	 *	@throws		InvalidArgumentException	if height is not an integer value
	 *	@throws		OutOfRangeException			if width is lower than 1
	 *	@throws		OutOfRangeException			if height is lower than 1
	 *	@throws		OutOfRangeException			if resulting image has more mega pixels than allowed
	 */
	public function resize( int $width, int $height, bool $interpolate = TRUE ): bool
	{
		if( $width < 1 )
			throw new OutOfRangeException( 'Width must be at least 1' );
		if( $height < 1 )
			throw new OutOfRangeException( 'Height must be at least 1' );
		if( $this->image->getWidth() == $width && $this->image->getHeight() == $height )
			return FALSE;
		if( $this->maxMegaPixels && $width * $height > $this->maxMegaPixels * 1024 * 1024 )
			throw new OutOfRangeException( 'Larger than '.$this->maxMegaPixels.'MP ('.$width.'x'.$height.')' );

		$image	= new Image;
		$image->create( $width, $height );
		$image->setType( $this->image->getType() );

		//  combine parameters from:
		$parameters	= array_merge(
			//  target and source resources
			array( $image->getResource(), $this->image->getResource() ),
			//  target and source start coordinates
			array( 0, 0, 0, 0 ),
			//  target width and height
			array( $width, $height ),
			//  source width and height
			array( $this->image->getWidth(), $this->image->getHeight() )
		);

		//  function to use depending on interpolation
		$function = $interpolate ? 'imagecopyresampled' : 'imagecopyresized';
		//  reflect function
		$reflection	= new ReflectionFunction( $function );
		//  call function with parameters
		$reflection->invokeArgs( $parameters );

		//  replace held image resource object by result
		$this->image->setResource( $image->getResource() );
		return TRUE;
	}

	/**
	 *	Rotates image clockwise.
	 *	Resulting image may have different dimensions.
	 *	@access		public
	 *	@param		integer		$angle			Angle to rotate (0-360)
	 *	@param		integer		$bgColor		Background color
	 *	@return		void
	 */
	public function rotate( int $angle, int $bgColor = 0 ): void
	{
		$bgColor	= $this->image->colorTransparent;
		$this->image->setResource( imagerotate( $this->image->getResource(), -$angle, $bgColor ) );
	}

	/**
	 *	Scales image by factors.
	 *	If no factor for height is given, it will be the same as for width.
	 *	@access		public
	 *	@param		integer		    $width			Factor for width
	 *	@param		integer|NULL	$height			Factor for height
	 *	@param		boolean		    $interpolate	Flag: use interpolation
	 *	@return		boolean		    				Image has been scaled
	 *	@throws		OutOfRangeException				if resulting image has more mega pixels than allowed
	 */
	public function scale( int $width, int $height = NULL, bool $interpolate = TRUE ): bool
	{
		if( is_null( $height ) )
			$height	= $width;
		if( $width == 1 && $height == 1 )
			return FALSE;
		$width	= (int) round( $this->image->getWidth() * $width );
		$height	= (int) round( $this->image->getHeight() * $height );
		return $this->resize( $width, $height, $interpolate );
	}

	/**
	 *	Scales image down to a maximum size if larger than limit.
	 *	@access		public
	 *	@param		integer		$width			Maximum width
	 *	@param		integer		$height			Maximum height
	 *	@param		boolean		$interpolate	Flag: use interpolation
	 *	@return		boolean						Image has been scaled
	 */
	public function scaleDownToLimit( int $width, int $height, bool $interpolate = TRUE ): bool
	{
		$sourceWidth	= $this->image->getWidth();
		$sourceHeight	= $this->image->getHeight();
		if( $sourceWidth <= $width && $sourceHeight <= $height )
			return FALSE;
		$scale = 1;
		if( $sourceWidth > $width )
			$scale	*= $width / $sourceWidth;
		if( $sourceHeight * $scale > $height )
			$scale	*= $height / ( $sourceHeight * $scale );
		$width	= (int) round( $sourceWidth * $scale );
		$height	= (int) round( $sourceHeight * $scale );
		return $this->resize( $width, $height, $interpolate );
	}

	/**
	 *	Scale image to fit into a size range.
	 *	Reduces to maximum size after possibly enlarging to minimum size.
	 *	Range maximum has higher priority.
	 *	For better resolution this method will first maximize and than minimize if both is needed.
	 *	@access		public
	 *	@param		integer		$minWidth		Minimum width
	 *	@param		integer		$minHeight		Minimum height
	 *	@param		integer		$maxWidth		Maximum width
	 *	@param		integer		$maxHeight		Maximum height
	 *	@param		boolean		$interpolate	Flag: use interpolation
	 *	@return		boolean						Image has been scaled
	 */
	public function scaleToRange( int $minWidth, int $minHeight, int $maxWidth, int $maxHeight, bool $interpolate = TRUE ): bool
	{
		$width	= $this->image->getWidth();
		$height	= $this->image->getHeight();
		if( $width < $minWidth || $height < $minHeight )
			return $this->scaleUpToLimit( $minWidth, $minHeight, $interpolate );
		else if( $width > $maxWidth || $height > $maxHeight )
			return $this->scaleDownToLimit( $maxWidth, $maxHeight, $interpolate );
		return FALSE;
	}

	/**
	 *	Scales image up to a minimum size if smaller than limit.
	 *	@access		public
	 *	@param		integer		$width			Minimum width
	 *	@param		integer		$height			Minimum height
	 *	@param		boolean		$interpolate	Flag: use interpolation
	 *	@return		boolean						Image has been scaled
	 *	@throws		OutOfRangeException			if resulting image has more mega pixels than allowed
	 */
	public function scaleUpToLimit( int $width, int $height, bool $interpolate = TRUE ): bool
	{
		$sourceWidth	= $this->image->getWidth();
		$sourceHeight	= $this->image->getHeight();
		if( $sourceWidth >= $width && $sourceHeight >= $height )
			return FALSE;
		$scale	= 1;
		if( $sourceWidth < $width )
			$scale	*= $width / $sourceWidth;
		if( $sourceHeight * $scale < $height )
			$scale	*= $height / ( $sourceHeight * $scale );
		$width	= (int) round( $sourceWidth * $scale );
		$height	= (int) round( $sourceHeight * $scale );
		return $this->resize( $width, $height, $interpolate );
	}
}
