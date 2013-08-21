<?php
/**
 *	Histogram image generator.
 *
 *	Copyright (c) 2012-2013 Christian Würker (ceusmedia.com)
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
 *	@copyright		2012-2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.6
 *	@version		$Id$
 */
/**
 *	Histogram image generator.
 *	@category		cmClasses
 *	@package		UI.Image
 *	@uses			UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.6
 *	@version		$Id$
 *	@todo			Code Doc
 */
class UI_Image_Histogram
{
	static public function drawChannels( $data )
	{
		$image	= new UI_Image();
		$image->create( 256, 100 );
		$drawer	= new UI_Image_Drawer( $image->getResource() );
		$alpha	= $image->getColor( 255, 255, 255, 127 );
		$drawer->fill( $alpha );
		imagealphablending( $image->getResource(), TRUE );
		$red	= $image->getColor( 255, 0, 0 );
		$green	= $image->getColor( 0, 255, 0 );
		$blue	= $image->getColor( 0, 0, 255 );
		$max	= 0;
		for( $i=0; $i<256; $i++ )
			$max	= max( $max, $data['r'][$i] + $data['g'][$i] + $data['b'][$i] );
		$max	*= 1.05;
		for( $i=0; $i<256; $i++ )
		{
			$x1	= $i;
			if( $b = floor( $data['b'][$i] / $max * 100 ) )
				$drawer->drawLine( $i, 0, $x1, $b, $blue );
			if( $g = floor( $data['g'][$i] / $max * 100 ) )
				$drawer->drawLine( $i, $b, $x1, $b + $g, $green );
			if( $r = floor( $data['r'][$i] / $max * 100 ) )
				$drawer->drawLine( $i, $g + $b, $x1, $r + $g + $b, $red );
		}
		$processor	= new UI_Image_Processing( $image );
		$processor->flip();
		return $image;
	}

	static public function drawHistrogram( $data, $colorR = 0, $colorG = 0, $colorB = 0 ){
		$max	= max( $data );
		if( !$max )
			throw new Exception( "Error: max 0" );
		$max	*= 1.05;
		for( $i=0; $i<256; $i++ )
			$data[$i]	= round( $data[$i] / $max * 100 );
		$image	= new UI_Image();
		$image->create( 256, 100 );
		$drawer	= new UI_Image_Drawer( $image->getResource() );
		$alpha	= $image->getColor( $colorR, $colorG, $colorB, 127 );
		$drawer->fill( $alpha );
		$color	= $image->getColor( $colorR, $colorG, $colorB );
		imagealphablending( $image->getResource(), FALSE );
		for($i=0; $i<256; $i++)
			if( $data[$i] )
				$drawer->drawLine( $i, 0, $i, $data[$i], $color );
		$processor	= new UI_Image_Processing( $image );
		$processor->flip();
		return $image;
	}

	static public function getData( UI_Image $image )
	{
		$pixels		= $image->getWidth() * $image->getHeight();
		if( $pixels > 2 * 1000 * 1000 )
		{
			$tempFile	= uniqid().'.image';
			$thumb	= new UI_Image_ThumbnailCreator( $image->getFileName(), $tempFile, 100 );
			$thumb->thumbizeByLimit( 1000, 1000 );
			$image	= new UI_Image( $tempFile );
			$pixels		= $image->getWidth() * $image->getHeight();
			unlink( $tempFile );
		}
		$values		= array(
			'w'	=> array_fill( 0, 256, 0 ),
			'r'	=> array_fill( 0, 256, 0 ),
			'g'	=> array_fill( 0, 256, 0 ),
			'b'	=> array_fill( 0, 256, 0 ),
		);
		$resource	= $image->getResource();
		for( $x=0; $x<$image->getWidth(); $x++ )
		{
			for( $y=0; $y<$image->getHeight(); $y++ )
			{
				$rgb	= imagecolorat( $resource, $x, $y );
				$r		= ($rgb >> 16) & 0xFF;
				$g		= ($rgb >> 8) & 0xFF;
				$b		= $rgb & 0xFF;			
				$w		= round( ( $r + $g + $b ) / 3 );
				$values['w'][$w]++;
				$values['r'][$r]++;
				$values['g'][$g]++;
				$values['b'][$b]++;
			}
		}
		return $values;
	}
}
?>
