<?php
/**
 *	Processor for resizing, scaling and rotating an image.
 *
 *	Copyright (c) 2010-2018 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Processor for resizing, scaling and rotating an image.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@uses			UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_Image_Processing
{
	/**	@var		UI_Image		$image			Image resource object */
	protected $image;
	
	/**	@param		integer			$maxMegaPixel	Maxiumum megapixels */
	public $maxMegaPixels			= 0;

	/**
	 *	Constructor.
	 *	Sets initial image resource object.
	 *	@access		public
	 *	@param		UI_Image		$image			Image resource object
	 *	@param		integer			$maxMegaPixel	Maxiumum megapixels, default: 0 - unlimited
	 *	@return		void
	 */
	public function __construct( UI_Image $image, $maxMegaPixels = 0 )
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
	 *	@return		boolean		Image has been copped
	 *	@throws		InvalidArgumentException if left margin is not an integer value
	 *	@throws		InvalidArgumentException if top margin is not an integer value
	 *	@throws		InvalidArgumentException if width is not an integer value
	 *	@throws		InvalidArgumentException if height is not an integer value
	 *	@throws		OutOfRangeException if width is lower than 1
	 *	@throws		OutOfRangeException if height is lower than 1
	 *	@throws		OutOfRangeException if resulting image has more mega pixels than allowed
	 */
	public function crop( $startX, $startY, $width, $height )
	{
		if( !is_int( $startX ) )
			throw new InvalidArgumentException( 'X start value must be integer' );
		if( !is_int( $startY ) )
			throw new InvalidArgumentException( 'Y start value must be integer' );
		if( !is_int( $width ) )
			throw new InvalidArgumentException( 'Width must be integer' );
		if( !is_int( $height ) )
			throw new InvalidArgumentException( 'Height must be integer' );
		if( $width < 1 )
			throw new OutOfRangeException( 'Width must be atleast 1' );
		if( $height < 1 )
			throw new OutOfRangeException( 'Height must be atleast 1' );
		$image	= new UI_Image;
		$image->create( $width, $height );
		$image->setType( $this->image->getType() );

		imagecopy( $image->getResource(), $this->image->getResource(), 0, 0, $startX, $startY, $width, $height );
		$this->image->setResource( $image->getResource() );											//  replace held image resource object by result
		return TRUE;
	}

	/**
	 *	Flips image horizontally or vertically.
	 *	@access		public
	 *	@param		integer		$mode		0: horizontally, 1: vertically
	 *	@return		boolean		Image has been flipped
	 */
	public function flip( $mode = 0 ){
		$image	= new UI_Image;
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
		$this->image->setResource( $image->getResource() );											//  replace held image resource object by result
		return TRUE;
	}

	/**
	 *	Resizes image.
	 *	@access		public
	 *	@param		integer		$width			New width
	 *	@param		integer		$height			New height
	 *	@param		boolean		$interpolate	Flag: use interpolation
	 *	@return		boolean		Image has been resized
	 *	@throws		InvalidArgumentException if width is not an integer value
	 *	@throws		InvalidArgumentException if height is not an integer value
	 *	@throws		OutOfRangeException if width is lower than 1
	 *	@throws		OutOfRangeException if height is lower than 1
	 *	@throws		OutOfRangeException if resulting image has more mega pixels than allowed
	 */
	public function resize( $width, $height, $interpolate = TRUE )
	{
		if( !is_int( $width ) )
			throw new InvalidArgumentException( 'Width must be integer' );
		if( !is_int( $height ) )
			throw new InvalidArgumentException( 'Height must be integer' );
		if( $width < 1 )
			throw new OutOfRangeException( 'Width must be atleast 1' );
		if( $height < 1 )
			throw new OutOfRangeException( 'Height must be atleast 1' );
		if( $this->image->getWidth() == $width && $this->image->getHeight() == $height )
			return FALSE;
		if( $this->maxMegaPixels && $width * $height > $this->maxMegaPixels * 1024 * 1024 )
			throw new OutOfRangeException( 'Larger than '.$this->maxMegaPixels.'MP ('.$width.'x'.$heigth.')' );

		$image	= new UI_Image;
		$image->create( $width, $height );
		$image->setType( $this->image->getType() );

		$parameters	= array_merge(																	//  combine parameters from:
			array( $image->getResource(), $this->image->getResource() ),							//  target and source resources
			array( 0, 0, 0, 0 ),																	//  target and source start coordinates
			array( $width, $height ),																//  target width and height
			array( $this->image->getWidth(), $this->image->getHeight() )							//  source width and height
		);

		$function = $interpolate ? 'imagecopyresampled' : 'imagecopyresized';						//  function to use depending on interpolation
		$reflection	= new ReflectionFunction( $function );											//  reflect function
		$reflection->invokeArgs( $parameters );														//  call function with parameters

		$this->image->setResource( $image->getResource() );											//  replace held image resource object by result
		return TRUE;
	}

	/**
	 *	Rotates image clockwise.
	 *	Resulting image may have different dimensions.
	 *	@access		public
	 *	@param		integer		$angle			Angle to rotate (0-360)
	 *	@param		integer		$bgColor		Background color
	 *	@param		integer		$transparency	Flag: use transparency
	 *	@return		void
	 */
	public function rotate( $angle, $bgColor = 0, $ignoreTransparent = 0 )
	{
		$bgColor	= $this->image->colorTransparent;
		$this->image->setResource( imagerotate( $this->image->getResource(), -$angle, $bgColor ) );
	}

	/**
	 *	Scales image by factors.
	 *	If no factor for height is given, it will be the same as for width.
	 *	@access		public
	 *	@param		integer		$width			Factor for width
	 *	@param		integer		$height			Factor for height
	 *	@param		boolean		$interpolate	Flag: use interpolation
	 *	@return		boolean		Image has been scaled
	 *	@throws		OutOfRangeException if resulting image has more mega pixels than allowed
	 */
	public function scale( $width, $height = NULL, $interpolate = TRUE )
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
	 *	@return		boolean		Image has been scaled
	 */
	public function scaleDownToLimit( $width, $height, $interpolate = TRUE )
	{
		if( !is_int( $width ) )
			throw new InvalidArgumentException( 'Width must be integer' );
		if( !is_int( $height ) )
			throw new InvalidArgumentException( 'Height must be integer' );
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
	 *	@return		boolean		Image has been scaled
	 */
	public function scaleToRange( $minWidth, $minHeight, $maxWidth, $maxHeight, $interpolate = TRUE )
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
	 *	@param		integer		$width		Minimum width
	 *	@param		integer		$height		Minimum height
	 *	@param		boolean		$interpolate	Flag: use interpolation
	 *	@return		boolean		Image has been scaled
	 *	@throws		OutOfRangeException if resulting image has more mega pixels than allowed
	 */
	public function scaleUpToLimit( $width, $height, $interpolate = TRUE )
	{
		if( !is_int( $width ) )
			throw new InvalidArgumentException( 'Width must be integer' );
		if( !is_int( $height ) )
			throw new InvalidArgumentException( 'Height must be integer' );
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
		return $this->resize( $width, $height, $interpolate, $this->maxMegaPixels );
	}
}
?>
