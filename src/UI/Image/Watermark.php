<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Mark Image with another Image.
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
 *	@copyright		2005-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\Image;

use InvalidArgumentException;

/**
 *	Mark Image with another Image.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2005-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Watermark
{
	/**	@var		array		$size			Array of Information of Stamp Image */
	protected $size;

	/**	@var		string		$stamp			File Name of Stamp Image */
	protected $stamp;

	/**	@var		array		$stampSource	Image Source Stamp Image */
	protected $stampSource;

	/**	@var		int			$quality		Quality of resulting JPEG Image */
	protected $quality;

	/**	@var		string		$positionH		Horizontal Position of Stamp Image (left, center, right) */
	protected $positionH		= 'right';

	/**	@var		string		$positionV		Vertical Position of Stamp Image (top, middle, bottom) */
	protected $positionV		= 'bottom';

	/**	@var		int			$marginX		Horizontal Margin of Stamp Image */
	protected $marginX			= 0;

	/**	@var		int			$marginY		Vertical Margin of Stamp Image */
	protected $marginY			= 0;

	/**	@var		int			$alpha			Opacity of Stamp Image */
	protected $alpha;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$stamp 			File Name of Stamp Image
	 *	@param		int			$alpha 			Opacity of Stamp Image
	 *	@param		int			$quality 		Quality of resulting Image
	 *	@return		void
	 */
	public function __construct( string $stamp, int $alpha = 100, int $quality = 100 )
	{
		$this->setStamp( $stamp );
		$this->setAlpha( $alpha );
		$this->setQuality( $quality );
	}

	/**
	 *	Return Array with Coords of Stamp Image within a given Image.
	 *	@access		protected
	 *	@param		resource		$image 		Image Resource
	 *	@return		array
	 */
	protected function calculatePosition( $image ): array
	{
		switch( $this->positionH ){
			case 'left':
				$posX	= 0 + $this->marginX;
				break;
			case 'center':
				$posX	= ceil( $image->getWidth() / 2 - $this->stamp->getWidth() / 2 );
				break;
			case 'right':
			default:
				$posX	= $image->getWidth() - $this->stamp->getWidth() - $this->marginX;
				break;
		}
		switch( $this->positionV ){
			case 'top':
				$posY	= 0 + $this->marginY;
				break;
			case 'middle':
				$posY	= ceil( $image->getHeight() / 2 - $this->stamp->getHeight() / 2 );
				break;
			case 'bottom':
			default:
				$posY	= $image->getHeight() - $this->stamp->getHeight() - $this->marginY;
				break;
		}
		return [
			'x'	=> $posX,
			'y'	=> $posY
		];
	}

	/**
	 *	Marks a Image with Stamp Image.
	 *	@access		public
	 *	@param		string			$source 		File Name of Source Image
	 *	@param		string|NULL		$target 		Target Name of Target Image
	 *	@return		bool
	 */
	public function markImage( string $source, ?string $target = NULL ): bool
	{
		if( !$target )
			$target = $source;

		$creator	= new Creator();
		$creator->loadImage( $source );
		$image		= $creator->getResource();
		$type		= $creator->getType();

		$position		= $this->calculatePosition( $creator );
		$stampHeight	= $this->stamp->getHeight();
		$stampWidth		= $this->stamp->getWidth();
		$stampResource	= $this->stamp->getResource();
		imagecopymerge( $image, $stampResource, $position['x'], $position['y'], 0, 0, $stampWidth, $stampHeight, $this->alpha );

		$printer	= new Printer( $image );
		return $printer->save( $target, $type );
	}

	/**
	 *	Sets the Opacity of Stamp Image.
	 *	@access		public
	 *	@param		int		$alpha 		Opacity of Stamp Image
	 *	@return		self
	 */
	public function setAlpha( int $alpha ): self
	{
		$this->alpha	= abs( $alpha );
		return $this;
	}

	/**
	 *	Sets the Margin of Stamp Image.
	 *	@access		public
	 *	@param		int			$x 				Horizontal Margin of Stamp Image
	 *	@param		int			$y 				Vertical Margin of Stamp Image
	 *	@return		self
	 */
	public function setMargin( int $x, int $y ): self
	{
		$this->marginX	= abs( $x );
		$this->marginY	= abs( $y );
		return $this;
	}

	/**
	 *	Sets the Position of Stamp Image.
	 *	@access		public
	 *	@param		string		$horizontal 	Horizontal Position of Stamp Image (left,center,right)
	 *	@param		string		$vertical 		Vertical Position of Stamp Image (top,middle,bottom)
	 *	@return		void
	 */
	public function setPosition( string $horizontal, string $vertical )
	{
		if( in_array( $horizontal, ['left', 'center', 'right'] ) )
			$this->positionH	= $horizontal;
		else
			throw new InvalidArgumentException( 'Horizontal Position "'.$horizontal.'" must be on of (left, center, right).' );
		if( in_array( $vertical, ['top', 'middle', 'bottom'] ) )
			$this->positionV	= $vertical;
		else
			throw new InvalidArgumentException( 'Vertical Position "'.$horizontal.'" must be on of (top, middle, bottom).' );
	}

	/**
	 *	Sets the Quality of resulting Image.
	 *	@access		public
	 *	@param		int			$quality 		Quality of resulting Image
	 *	@return		self
	 */
	public function setQuality( int $quality ): self
	{
		$this->quality	= $quality;
		return $this;
	}

	/**
	 *	Sets the Stamp Image.
	 *	@access		public
	 *	@param		string		$stamp			File Name of Stamp Image
	 *	@return		self
	 */
	public function setStamp( string $stamp ): self
	{
		$this->stamp	= new Creator();
		$this->stamp->loadImage( $stamp );
		return $this;
	}
}
