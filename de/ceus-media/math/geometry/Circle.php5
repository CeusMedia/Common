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
 *	@package		math.geometry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	...
 *	@package		math.geometry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 *	@todo			Code Documentation
 */
class Math_Geometry_Circle
{
	protected $radius;

	public function __construct( $radius )
	{
		$this->radius = $radius;
	}
	
	public function getVolume()
	{
		$value = M_PI * pow( $this->radius, 2 );
		return $value;
	}
	
	public function getOutline()
	{
		$value = 2 * M_PI * $this->radius;
		return $value;
	}
}
?>