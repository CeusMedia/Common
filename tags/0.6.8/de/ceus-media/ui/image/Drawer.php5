<?php
/**
 *	Basic Image Creation.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@uses			UI_Image_Printer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
import( 'de.ceus-media.ui.image.Printer' );
/**
 *	Basic Image Creation.
 *	@category		cmClasses
 *	@package		ui.image
 *	@uses			UI_Image_Printer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class UI_Image_Drawer
{
	protected $image;
	protected $type	= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Resource		Image Resource, can be created with UI_Image_Creator
	 *	@return		void
	 */
	public function __construct( $image )
	{
		$this->setImage( $image );
	}
	
	public function getColor( $red, $green, $blue, $alpha = 0 )
	{
		return imagecolorallocatealpha( $this->image, $red, $green, $blue, $alpha );
	}

	public function drawBorder( $color, $width = 1 )
	{
		for( $i = 0; $i < $width; $i++ )
			$this->drawRectangle( 0 + $i, 0 + $i, imagesx( $this->image ) - $i - 1, imagesy( $this->image ) - $i - 1, $color );
	}
	
	public function drawRectangle( $x0, $y0, $x1, $y1, $color )
	{
		return imagerectangle( $this->image, $x0, $y0, $x1, $y1, $color );
	}

	public function drawLine( $x0, $y0, $x1, $y1, $color )
	{
		return imageline( $this->image, $x0, $y0, $x1, $y1, $color );
	}
	
	public function drawPixel( $x, $y, $color )
	{
		return imagesetpixel( $this->image, $x, $y, $color );		
	}
	
	public function drawString( $x, $y, $text, $size, $color )
	{
		return imagestring( $this->image, $size, $x, $y, $text, $color );
	}

	public function fill( $color )
	{
		return imagefilledrectangle( $this->image, 0, 0, imagesx( $this->image ) - 1, imagesy( $this->image ) - 1, $color );		
	}
	
	public function fillRectangle( $x0, $y0, $x1, $y1, $color )
	{
		return imagefilledrectangle( $this->image, $x0, $y0, $x1, $y1, $color );		
	}
	
/*	public function isSet()
	{
		return isset( $this->image );
	}
*/	
	/**
	 *	Sets Image Handler.
	 *	@access		public
	 *	@param		Resource		Image Handler
	 *	@return		void
	 */
	public function setImage( $image )
	{
		$this->image = $image;
	}
	
	public function getImage()
	{
		return $this->image;
	}
	
	public function show( $quality = 100 )
	{
		UI_Image_Printer::showImage( $this->image, $this->type, $quality );
	}
}
?>