<?php
/**
 *	Gaussian Blur with 3x3 Matrix.
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.12.2005
 *	@version		0.1
 */
/**
 *	Gaussian Blur with 3x3 Matrix.
 *	@category		cmClasses
 *	@package		ui.image
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.12.2005
 *	@version		0.1
 */
class UI_Image_GaussBlur
{
	/*	@var		int			$quality		Quality of Target Image */
	protected $quality;
	/*	@var		array		$size			Sizes of Source Image */
	protected $size	= array();
	/*	@var		string		$source			Source File Name of Source Image */
	protected $source;
	/*	@var		string		$target			Target File Name of Target Image */
	protected $target;
	
	/**
	 *	Constructur.
	 *	@access		public
	 *	@param		string		$source 		File Name of Source Image
	 *	@param		string		$target 		File Name of Target Image
	 *	@param		int			$quality 		Quality of Target Image
	 *	@return		void
	 */
	public function __construct( $source, $target, $quality = 100 )
	{
		$this->setSource( $source );	
		$this->setTarget( $target );	
		$this->setQuality( $quality );	
	}

	/**
	 *	Sets the Quality of resulting Image.
	 *	@access		public
	 *	@param		int			$quality	 	Quality of resulting Image
	 *	@return		void
	 */
	public function setQuality( $quality )
	{
		$this->quality	= $quality;
	}

	/**
	 *	Sets the File Name of Source Image.
	 *	@access		public
	 *	@param		string		$source		 	File Name of Source Image
	 *	@return		void
	 */
	public function setSource( $source )
	{
		if( !file_exists( $source ) )
			throw new InvalidArgumentException( 'Image File "'.$source.'" is not existing.' );
		$size = @getimagesize( $source );
		if( !$size )
			throw new Exception( 'Source File "'.$source.'" is not a supported Image.' );
		$this->size		= $size;
		$this->source	= $source;
	}

	/**
	 *	Sets the File Name of Target Image.
	 *	@access		public
	 *	@param		string		$target		 	File Name of resulting Target Image
	 *	@return		void
	 */
	public function setTarget( $target )
	{
		$this->target	= $target;
	}
	
	/**
	 *	Blurs Image.
	 *	@access		public
	 *	@return		bool
	 */
	public function blur()
	{
		if( !count( $this->size ) )
			throw new RuntimeException( 'No Source Image set.' );

		$target	= imagecreatetruecolor( $this->size[0], $this->size[1] );
		if( function_exists( 'imageantialias' ) )
			imageantialias( $target, TRUE );
		switch( $this->size[2] )
		{
			case 1:      //GIF
				$source	= imagecreatefromgif( $this->source );
				$this->blurImage( $source, $target );
				imagegif( $target, $this->target );
				break;
			case 2:      //JPEG
				$source	= imagecreatefromjpeg( $this->source );
				$this->blurImage( $source, $target );
				imagejpeg( $target, $this->target, $this->quality );
				break;
			case 3:      //PNG
				$source	= imagecreatefrompng( $this->source );
				$this->blurImage( $source, $target );
				imagepng( $target, $this->target );
				break;
			default:
				throw new Exception( 'Image Type is no supported.' );
		}
		return true;
	}
	
	/**
	 *	Blurs Source Image with 3x3 Matrix to Target Image.
	 *	@access		private
	 *	@return		bool
	 */
	protected function blurImage( $source, &$target )
	{
		$this->_cache	= array();
		for( $x=0; $x<$this->size[0]; $x++ )
		{
			for( $y=0; $y<$this->size[1]; $y++ )
			{
				if( $x > 0 && $y > 0 && $x < $this->size[0]-1 && $y < $this->size[1]-1 )
				{
					$color	= array();
					$x0y0	= $this->getColor( $source, $x-1, $y-1 );
					$x0y1	= $this->getColor( $source, $x-1, $y );
					$x0y2	= $this->getColor( $source, $x-1, $y+1 );
					$x1y0	= $this->getColor( $source, $x, $y-1 );
					$x1y1	= $this->getColor( $source, $x, $y );
					$x1y2	= $this->getColor( $source, $x, $y+1 );
					$x2y0	= $this->getColor( $source, $x+1, $y-1 );
					$x2y1	= $this->getColor( $source, $x+1, $y );
					$x2y2	= $this->getColor( $source, $x+1, $y+1 );

					$color['red']	= $this->gaussPixel( $x0y0['red'], $x0y1['red'], $x0y2['red'], $x1y0['red'], $x1y1['red'], $x1y2['red'], $x2y0['red'], $x2y1['red'], $x2y2['red'] );
					$color['green']	= $this->gaussPixel( $x0y0['green'], $x0y1['green'], $x0y2['green'], $x1y0['green'], $x1y1['green'], $x1y2['green'], $x2y0['green'], $x2y1['green'], $x2y2['green'] );
					$color['blue']	= $this->gaussPixel( $x0y0['blue'], $x0y1['blue'], $x0y2['blue'], $x1y0['blue'], $x1y1['blue'], $x1y2['blue'], $x2y0['blue'], $x2y1['blue'], $x2y2['blue'] );
				}
				else
					$color	= $this->getColor( $source, $x, $y );
				$color	= imagecolorallocate( $target, $color['red'], $color['green'], $color['blue'] );
				imagesetpixel( $target, $x, $y, $color );
			}
		}
	}
	
	/**
	 *	Blurs Source Image with 3x3 Matrix to Target Image.
	 *	@access		protected
	 *	@param		int			$x0y0			Color of Pixel x0y0
	 *	@param		int			$x0y1			Color of Pixel x0y0
	 *	@param		int			$x0y2			Color of Pixel x0y0
	 *	@param		int			$x1y0			Color of Pixel x0y0
	 *	@param		int			$x1y1			Color of Pixel x0y0
	 *	@param		int			$x1y2			Color of Pixel x0y0
	 *	@param		int			$x2y0			Color of Pixel x0y0
	 *	@param		int			$x2y1			Color of Pixel x0y0
	 *	@param		int			$x2y2			Color of Pixel x0y0
	 *	@return		bool
	 */
	protected function gaussPixel( $x0y0, $x0y1, $x0y2, $x1y0, $x1y1, $x1y2, $x2y0, $x2y1, $x2y2 ) 
	{
		$gauss	= 1/16 * ( $x0y0 + 2*$x0y1 + $x0y2 + 2*$x1y0 + 4*$x1y1 + 2*$x1y2 + $x2y0 + 2*$x2y1 + $x2y2 );
		return $gauss;
		return $gauss >= 0 ? $gauss : 255;
	}
	
	/**
	 *	Returns Color of Pixel in Source Image.
	 *	@access		protected
	 *	@param		Resource	$source			Source Image
	 *	@param		int			$x				X-Axis
	 *	@param		int			$y				Y-Axis
	 *	@return		int
	 */
	protected function getColor( $source, $x, $y )
	{
		$color	= imagecolorat( $source, $x, $y );
		$color	= imagecolorsforindex( $source, $color );
		return $color;
	}
}
?>