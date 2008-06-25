<?php
/**
 *	@package		math.geometry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	@package		math.geometry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
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