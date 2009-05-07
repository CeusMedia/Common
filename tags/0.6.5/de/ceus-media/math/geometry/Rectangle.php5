<?php
/**
 *	@package		math.geometry
 *	@uses			Math_Geometry_Triangle
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
import( 'de.ceus-media.math.geometry.Triangle' );
/**
 *	@package		math.geometry
 *	@uses			Math_Geometry_Triangle
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
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