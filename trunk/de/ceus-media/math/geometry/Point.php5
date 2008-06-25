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
class Math_Geometry_Point
{
	protected $x;
	protected $y;
	
	public function __construct( $x, $y )
	{
		$this->setX( $x );
		$this->setY( $y );
	}

	public function setX()
	{
		$this->x = $x;
	}
	
	public function setY()
	{
		$this->y = $y;
	}
	
	public function getX()
	{
		return $this->x;
	}

	public function getY()
	{
		return $this->y;
	}
}
?>