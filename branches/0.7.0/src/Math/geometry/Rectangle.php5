<?php
/**
 *	...
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
 *	@package		math.geometry
 *	@uses			Math_Geometry_Triangle
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
import( 'de.ceus-media.math.geometry.Triangle' );
/**
 *	...
 *	@category		cmClasses
 *	@package		math.geometry
 *	@uses			Math_Geometry_Triangle
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 *	@todo			Code Documentation
 */
class Math_Geometry_Rectangle
{
	public function __construct( $a, $b )
	{
		$this->a = $a;
		$this->b = $b;
	}
	
	public function getVolume()
	{
		return $this->a * $this->b;	
	}
	
	public function getOutline()
	{
		return 2 *( $this->a + $this->b );
	}
	
	public function diagonal()
	{
		$t = new Math_Geometry_Triangle();
		$c = $t->pythagoras( $this->a, $this->b );
		return $c;
	}
}
?>