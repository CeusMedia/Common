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
import( 'de.ceus-media.ui.image.Modifier' );
/**
 *	Gaussian Blur with 3x3 Matrix.
 *	@category		cmClasses
 *	@package		ui.image
 *	@extends		UI_Image_Modifier
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.12.2005
 *	@version		0.1
 */
class UI_Image_GaussBlur extends UI_Image_Modifier
{
	/**
	 *	Blurs Image.
	 *	@access		public
	 *	@param		int			$type			Output format type
	 *	@return		bool
	 */
	public function blur( $type = NULL )
	{
		if( !$this->sourceUri )
			throw new RuntimeException( 'No source image set' );

		$this->target	= imagecreatetruecolor( $this->sourceInfo[0], $this->sourceInfo[1] );
		if( function_exists( 'imageantialias' ) )
			imageantialias( $this->target, TRUE );

		$this->_cache	= array();
		for( $x=0; $x<$this->sourceInfo[0]; $x++ )
		{
			for( $y=0; $y<$this->sourceInfo[1]; $y++ )
			{
				if( $x > 0 && $y > 0 && $x < $this->sourceInfo[0]-1 && $y < $this->sourceInfo[1]-1 )
				{
					$color	= array();
					$x0y0	= $this->getColor( $x-1, $y-1 );
					$x0y1	= $this->getColor( $x-1, $y );
					$x0y2	= $this->getColor( $x-1, $y+1 );
					$x1y0	= $this->getColor( $x, $y-1 );
					$x1y1	= $this->getColor( $x, $y );
					$x1y2	= $this->getColor( $x, $y+1 );
					$x2y0	= $this->getColor( $x+1, $y-1 );
					$x2y1	= $this->getColor( $x+1, $y );
					$x2y2	= $this->getColor( $x+1, $y+1 );

					$color['red']	= $this->gaussPixel( $x0y0['red'], $x0y1['red'], $x0y2['red'], $x1y0['red'], $x1y1['red'], $x1y2['red'], $x2y0['red'], $x2y1['red'], $x2y2['red'] );
					$color['green']	= $this->gaussPixel( $x0y0['green'], $x0y1['green'], $x0y2['green'], $x1y0['green'], $x1y1['green'], $x1y2['green'], $x2y0['green'], $x2y1['green'], $x2y2['green'] );
					$color['blue']	= $this->gaussPixel( $x0y0['blue'], $x0y1['blue'], $x0y2['blue'], $x1y0['blue'], $x1y1['blue'], $x1y2['blue'], $x2y0['blue'], $x2y1['blue'], $x2y2['blue'] );
				}
				else
					$color	= $this->getColor( $x, $y );
				$color	= imagecolorallocate( $this->target, $color['red'], $color['green'], $color['blue'] );
				imagesetpixel( $this->target, $x, $y, $color );
			}
		}
		if( $this->targetUri )
			return $this->saveImage( $type );
		return TRUE;
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
	 *	@param		int			$x				X-Axis
	 *	@param		int			$y				Y-Axis
	 *	@return		int
	 */
	protected function getColor( $x, $y )
	{
		$color	= imagecolorat( $this->source, $x, $y );
		$color	= imagecolorsforindex( $this->source, $color );
		return $color;
	}
}
?>