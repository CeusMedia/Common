<?php
/**
 *	Rotates an Image.
 *
 *	Copyright (c) 2009-2012 Christian Würker (ceusmedia.com)
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
 *	@copyright		2009-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.08.2009
 *	@version		$Id$
 */
/**
 *	Rotates an Image.
 *	@category		cmClasses
 *	@package		UI.Image
 *	@extends		UI_Image_Modifier
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.08.2009
 *	@version		$Id$
 */
class UI_Image_Rotator extends UI_Image_Modifier
{	
	/**
	 *	Invertes Source Image.
	 *	@access		public
	 *	@param		int			$angle			Rotation angle in degrees
	 *	@param		int			$type			Output format type
	 *	@return		bool
	 */
	public function rotate( $angle, $type = NULL )
	{
		if( !$this->sourceUri )
			throw new RuntimeException( 'No source image set' );

#		if( function_exists( 'imageantialias' ) )
#			imageantialias( $this->target, TRUE );

		$this->target	= imagerotate( $this->source, $angle, 0 );
		if( $this->targetUri )
			return $this->saveImage( $type );
		return TRUE;
	}

	/**
	 *	Rotates an Image statically.
	 *	@access	public
	 *	@static
	 *	@param		string		$imageUri		URI of Image File		
	 *	@param		int			$angle			Rotation angle in degrees
	 *	@param		int			$quality		JPEG Quality in percent
	 */
	public static function rotateImage( $imageUri, $angle, $quality = 100 )
	{
		$modifier	= new UI_Image_Rotator( $imageUri, $imageUri, $quality );
		return $modifier->rotate( $angle );
	}
}
?>